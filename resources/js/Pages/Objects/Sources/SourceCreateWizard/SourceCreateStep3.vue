<template>
  <!-- Processes -->
  <div class="flex justify-end justify-items-center p-5">
    <PrimaryButton
      type="button"
      @click="addProcessModalIsVisible = true"
    >
      <PlusIcon
        class="h-6 w-6 mr-2"
        aria-hidden="true"
      />
      New Process
    </PrimaryButton>
  </div>

  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5">
    <div
      class="flex w-full justify-center py-2"
      v-for="(process, processIdx) in processes"
      :key="processIdx"
    >
      <div class="w-full">
        <PropertyDisclosure :title="process.value">
          <div
            class="my-6"
            v-for="property in process.props"
            :key="property"
          >
            <div v-if="property.property.inputType === 'text'">
              <TextInput
                v-model="process.data[property.property.symbolic_name]"
                :label="property.property.name"
                :unit="property.unit.symbol"
                :description="property.property.description"
                :required="property.required"
              />
            </div>
            <div v-else-if="property.property.inputType === 'select'">
              <SelectMenu
                v-model="process.data[property.property.symbolic_name]"
                :options="property.property.data.options"
                :label="property.property.name"
                :description="property.property.description"
                :required="property.required"
              />
            </div>
            <div
              v-for="(error, key) in errors"
              :key="key"
            >
              <div v-if="property.property.symbolic_name === key.substr(key.indexOf('.') + 1) && +key.substr(0, key.indexOf('.')) === processIdx">
                <div
                  v-for="(e, eIdx) in error"
                  :key="eIdx"
                >
                  <JetInputError
                    :message="e"
                    class="mt-2"
                  />
                </div>
              </div>
            </div>
          </div>
        </PropertyDisclosure>
      </div>
      <div class="ml-5">
        <button
          type="button"
          class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          @click="onRemoveProcess(processIdx)"
        >
          Delete
        </button>
      </div>

    </div>

  </div>

  <add-process-modal
    v-model="addProcessModalIsVisible"
    :processesCategories="processesCategories"
    :processes="processes"
    @confirmation="onAddProcess"
  >
  </add-process-modal>
</template>

<script>
import { ref, watch, computed } from "vue";
import { useStore } from "vuex";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";
import { PlusIcon } from "@heroicons/vue/solid";

import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "../../../../Components/PrimaryButton.vue";
import AddProcessModal from "@/Components/Modals/AddProcessModal.vue";
import JetInputError from "@/Jetstream/InputError.vue";

import { sortProperties } from "../helpers/sort-properties";
import { transformPropsToData } from "../helpers/transform-props-to-data";
import { validateProperies } from "../helpers/validate-properties";

export default {
  components: {
    PropertyDisclosure,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    AddProcessModal,
    PlusIcon,
    PrimaryButton,
    ChevronDownIcon,
    TextInput,
    SelectMenu,
    JetInputError,
  },

  props: {
    processesCategories: {
      type: Array,
      required: true,
    },
    processes: {
      type: Array,
      required: true,
    },
    nextStepRequest: {
      type: Boolean,
      required: true,
    },
  },

  emits: ["completed", "incompleted"],

  setup(props, ctx) {
    const store = useStore();

    const errors = ref({});

    const addProcessModalIsVisible = ref(false);

    const propProcesses = props.processes.map((p) => ({
      key: p.id,
      value: p.name,
      parent: p.category_id,
      props: sortProperties(p.template_properties),
      data: transformPropsToData(p.template_properties),
    }));

    const storeProcesses = computed(() => store.getters["source/processes"]);

    const processes = ref(
      storeProcesses.value.length
        ? window._.cloneDeep(storeProcesses.value)
        : propProcesses
    );

    const commitProcesses = window._.debounce(
      () =>
        store.commit("source/setProcesses", {
          processes: window._.cloneDeep(processes),
        }),
      500
    );

    watch(processes, () => commitProcesses(), {
      deep: true,
      immediate: true,
    });

    const onAddProcess = (process) => {
      const newProcess = window._.cloneDeep(process);

      if (!Object.keys(newProcess.props).length === 0) return;

      newProcess.data = transformPropsToData(newProcess.props);

      processes.value.push(newProcess);
    };

    const onRemoveProcess = (index) => processes.value.splice(index, 1);

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        errors.value = {};

        for (const [index, process] of processes.value.entries()) {
          const properties = process.props;

          validateProperies(process, properties, errors.value, index);
        }

        if (!Object.keys(errors.value).length) ctx.emit("completed");
        else ctx.emit("incompleted");
      }
    );

    return {
      errors,
      addProcessModalIsVisible,
      processes,
      onAddProcess,
      onRemoveProcess,
    };
  },
};
</script>

