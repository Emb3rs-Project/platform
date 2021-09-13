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

  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    v-for="(process, processIdx) in processes"
    :key="processIdx"
  >
    <div class="sm:col-span-3">
      <div class="bg-white overflow-hidden shadow sm:rounded-lg w-full">
        <div class="px-4 py-5 sm:p-6">
          <Disclosure
            as="div"
            v-slot="{ open }"
          >
            <dt class="text-lg">
              <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
                <span class="font-medium text-gray-900">
                  {{ process.value }}
                </span>
                <span class="ml-6 h-7 flex items-center">
                  <ChevronDownIcon
                    :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform']"
                    aria-hidden="true"
                  />
                </span>
              </DisclosureButton>
            </dt>
            <transition
              enter-active-class="transition duration-100 ease-out"
              enter-from-class="transform scale-95 opacity-0"
              enter-to-class="transform scale-100 opacity-100"
              leave-active-class="transition duration-75 ease-out"
              leave-from-class="transform scale-100 opacity-100"
              leave-to-class="transform scale-95 opacity-0"
            >
              <DisclosurePanel
                as="dd"
                class="mt-2 pr-12"
              >
                <div
                  class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                  v-for="property in process.props"
                  :key="property"
                >
                  <div class="sm:col-span-3">
                    <div v-if="property.property.inputType === 'text'">
                      <TextInput
                        v-model="process.data[property.property.symbolic_name]"
                        :unit="property.unit.symbol"
                        :placeholder="property.property.name"
                        :label="property.property.name"
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
                      >
                      </SelectMenu>
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
                </div>
              </DisclosurePanel>
            </transition>
          </Disclosure>
        </div>
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
    };
  },
};
</script>
