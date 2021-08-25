<template>
  <!-- Processes -->
  <div class="flex justify-end justify-items-center p-5">
    <PrimaryButton
      type="button"
      @click="addProcessModelIsVisible = true"
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
    v-for="process in processes"
    :key="process"
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
              <DisclosurePanel as="dd">
                <div
                  class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                  v-for="templateProperty in process.props"
                  :key="templateProperty"
                >
                  <div class="sm:col-span-3">
                    <div v-if="templateProperty.property.inputType === 'select'">
                      <SelectMenu
                        v-model="process.data[templateProperty.property.symbolic_name]"
                        :options="templateProperty.property.data.options"
                        :label="templateProperty.property.name"
                        :description="templateProperty.property.description"
                        :required="templateProperty.required"
                      />
                    </div>
                    <div v-else>
                      <TextInput
                        v-model="process.data[templateProperty.property.symbolic_name]"
                        :label="templateProperty.property.name"
                        :unit="templateProperty.unit.symbol"
                        :description="templateProperty.property.description"
                        :required="templateProperty.required"
                      />
                    </div>
                    <div
                      v-for="(error, key) in errors"
                      :key="key"
                    >
                      <div
                        v-for="subError in error"
                        :key="subError"
                      >
                        <JetInputError
                          v-if="key.includes(templateProperty.property.symbolic_name)"
                          :message="subError"
                          class="mt-2"
                        />
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

  <AddProcessModal
    v-model="addProcessModelIsVisible"
    :processesCategories="processesCategories"
    :processes="processes"
    @confirmation="onAddProcess"
  />
</template>

<script>
import { ref, watch, computed } from "vue";
import { useStore } from "vuex";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";
import { PlusIcon } from "@heroicons/vue/solid";

import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AddProcessModal from "@/Components/Modals/AddProcessModal.vue";
import JetInputError from "@/Jetstream/InputError.vue";

import { sortProperties } from "../helpers/sort-properties";
import { transformPropsToData } from "../helpers/transform-props-to-data";
import { transformData } from "../helpers/transform-data";
import { validateProperies } from "../helpers/validate-properties";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
    PlusIcon,
    PrimaryButton,
    AddProcessModal,
    SelectMenu,
    TextInput,
    JetInputError,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
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

    const addProcessModelIsVisible = ref(false);

    const propsProcesses = computed(() =>
      props.processes.map((p) => ({
        key: p.id,
        value: p.name,
        parent: p.category_id,
        props: sortProperties(p.template_properties),
        data: transformPropsToData(p.template_properties),
      }))
    );

    const instanceProcesses = computed(() => {
      const processes = [];

      for (const _instanceProcess of props.instance.values.processes) {
        const propsProcess = propsProcesses.value.find(
          (propsProcess) => propsProcess.key === _instanceProcess.id
        );

        if (!propsProcess) continue;

        processes.push({
          key: propsProcess.key,
          value: propsProcess.value,
          parent: propsProcess.parent,
          props: propsProcess.props,
          data: transformData(_instanceProcess.data, propsProcess.props),
        });
      }

      return processes;
    });

    const storeProcesses = computed(() => store.getters["source/processes"]);

    const processes = ref(
      storeProcesses.value.length
        ? storeProcesses.value
        : instanceProcesses.value
    );

    const onAddProcess = (process) => {
      const newProcess = window._.cloneDeep(process);

      if (!Object.keys(newProcess.props).length) return;

      for (const _templateProperty of newProcess.props) {
        newProcess.data[_templateProperty.property.symbolic_name] =
          _templateProperty.default_value;
      }

      newProcess.data = transformData(newProcess.data, newProcess.props);

      processes.value.push(newProcess);
    };

    const commitSourceProcesses = window._.debounce(
      () =>
        store.commit("source/setProcesses", {
          processes: window._.cloneDeep(processes.value),
        }),
      500
    );

    watch(processes, () => commitSourceProcesses(), {
      deep: true,
      immediate: true,
    });

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        errors.value = {};

        for (const process of processes.value) {
          const properties = process.props;

          validateProperies(process, properties, errors.value);
        }

        if (!Object.keys(errors.value).length) ctx.emit("completed");
        else ctx.emit("incompleted");
      }
    );

    return {
      errors,
      addProcessModelIsVisible,
      processes,
      onAddProcess,
    };
  },
};
</script>

