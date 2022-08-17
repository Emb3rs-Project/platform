<template>
  <!-- Processes -->
  <div class="flex justify-end justify-items-center p-5">
    <PrimaryButton
      type="button"
      :disabled="disabled"
      @click="onAddProcess(null)"
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
    <div class="sm:col-span-3 flex">
      <div class="bg-white shadow sm:rounded-lg w-5/6">
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
                        :options="templateProperty.property.symbolic_name !== 'equipment_selected' ? templateProperty.property.data.options : equipments"
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
                      <div v-if="key.includes(processIdx+'.-.'+templateProperty.property.symbolic_name)">
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
                <div class="flex justify-start justify-items-center p-5">
                  <PrimaryButton
                    type="button"
                    :disabled="disabled"
                    @click="addProcessModelIsVisible = true, processIndex = processIdx"
                  >
                    <PlusIcon
                      class="h-6 w-6 mr-2"
                      aria-hidden="true"
                    />
                    New Process Element
                  </PrimaryButton>
                </div>
                <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5">
                  <div
                    class="flex w-full justify-center py-2"
                    v-for="(processElement, processElementIdx) in process.processElements"
                    :key="processElementIdx"
                  >
                    <div class="w-full">
                      <PropertyDisclosure :title="processElement.value">
                        <div
                          class="my-6"
                          v-for="property in processElement.props"
                          :key="property"
                        >
                          <div v-if="property.property.inputType === 'text'">
                            <TextInput
                              v-model="processElement.data[property.property.symbolic_name]"
                              :label="property.property.name"
                              :unit="property.unit.symbol"
                              :description="property.property.description"
                              :required="property.required"
                            />
                          </div>
                          <div v-else-if="property.property.inputType === 'select'">
                            <SelectMenu
                              v-model="processElement.data[property.property.symbolic_name]"
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
                            <div v-if="key.includes(processIdx+'.'+processElementIdx+'.'+property.property.symbolic_name)">
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
                        title="Delete"
                        type="button"
                        class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        @click="onRemoveProcessElement(processIdx, processElementIdx)"
                      >
                        <TrashIcon class="text-white font-medium text-sm w-5" />
                      </button>
                    </div>
                  </div>
                </div>
              </DisclosurePanel>
            </transition>
          </Disclosure>
        </div>
      </div>
      <div class="ml-5 py-5">
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

  <AddProcessModal
    v-model="addProcessModelIsVisible"
    :processesCategories="processesCategories"
    :processes="propsProcessElement"
    @confirmation="onAddProcess"
  />
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
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AddProcessModal from "@/Components/Modals/AddProcessModal.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";

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
    PropertyDisclosure,
    TrashIcon,
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
    templates: {
      type: Array,
      required: true,
    },
    equipment: {
      type: Array,
      required: true,
    },
  },

  emits: ["completed", "incompleted"],

  setup(props, ctx) {
    const store = useStore();

    const errors = ref({});

    const processIndex = ref();

    const selectedEquipment = ref([]);
    const equipments = ref([]);
    const storeSelectedEquipment = computed(() => store.getters["source/equipment"]);
    selectedEquipment.value = window._.cloneDeep(storeSelectedEquipment.value);

    const storeTemplate = computed(() => store.getters["source/template"]);
    const disabled = computed(() => !props.templates.find((t) => t.id === storeTemplate.value.key).values.equipments);

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

    const propsProcessElement = propsProcesses.value.filter(e => e.key != 13);

    const instanceProcesses = computed(() => {
      const process = [];

      for (const _instanceProcess of props.instance.values.processes) {
        const propsProcess = propsProcesses.value.find(
          (propsProcess) => propsProcess.key === _instanceProcess.id
        );

        if (!propsProcess) continue;

        process.push({
          key: propsProcess.key,
          value: propsProcess.value,
          parent: propsProcess.parent,
          props: propsProcess.props,
          processElements: _instanceProcess.processElements ?? [],
          data: transformData(_instanceProcess.data, propsProcess.props, props.equipment, selectedEquipment.value),
        });
      }

      return process;
    });

    const storeProcesses = computed(() => store.getters["source/processes"]);

    const auxProcesses = ref(
      storeProcesses.value.length
        ? storeProcesses.value
        : instanceProcesses.value
    );
    
    const processes = ref(
      disabled.value
        ? []
        : auxProcesses
    );

    const onAddProcess = (process) => {
      const newProcess = window._.cloneDeep(process ?? propsProcesses.value.find(e => e.key == 13));

      if (!Object.keys(newProcess.props).length) return;

      for (const _templateProperty of newProcess.props) {
        newProcess.data[_templateProperty.property.symbolic_name] =
          _templateProperty.default_value;
      }

      newProcess.data = transformData(newProcess.data, newProcess.props);

      process ? processes.value[processIndex.value].processElements.push(newProcess) : processes.value.push(newProcess);
    };

    const commitSourceProcesses = window._.debounce(
      () =>
        store.commit("source/setProcesses", {
          processes: window._.cloneDeep(processes.value),
        }),
      500
    );

    watch(
      () => selectedEquipment.value,
      (options) => {
        equipments.value = [];
        options.forEach((equipment, i) => {
          equipments.value.push({
            key: equipment.identify,
            value: `${equipment.value} | ${equipment.data.name}`
          });
        });
        processes.value.map(e => e.data = transformData(e.data, e.props, props.equipment, selectedEquipment.value));
      }, 
      {
        deep: true,
        immediate: true,
      }
    );

    const onRemoveProcess = (index) => processes.value.splice(index, 1);
    const onRemoveProcessElement = (index, elementIdx) => processes.value[index].processElements.splice(elementIdx, 1);

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

        for (const [index, process] of processes.value.entries()) {
          const properties = process.props;
          validateProperies(process, properties, errors.value, `${index}.-`);

          for (const [elementIdx, element] of process.processElements.entries()) {
            validateProperies(element, element.props, errors.value, `${index}.${elementIdx}`);
          }
        }

        if (!Object.keys(errors.value).length) ctx.emit("completed");
        else ctx.emit("incompleted");
      }
    );

    return {
      errors,
      processIndex,
      addProcessModelIsVisible,
      equipments,
      processes,
      propsProcesses,
      propsProcessElement,
      disabled,
      onAddProcess,
      onRemoveProcess,
      onRemoveProcessElement,
    };
  },
};
</script>

