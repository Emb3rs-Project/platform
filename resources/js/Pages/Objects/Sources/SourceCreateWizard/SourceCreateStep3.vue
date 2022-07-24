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

  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5">
    <div
      class="flex w-full justify-center py-2"
      v-for="(process, processIdx) in selectedProcesses"
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
                :options="property.property.symbolic_name !== 'equipment_selected' ? property.property.data.options : equipments"
                :label="property.property.name"
                :description="property.property.description"
                :required="property.required"
              />
            </div>
            <div
              v-for="(error, key) in errors"
              :key="key"
            >
              <div v-if="key.includes(processIdx+'.-.'+property.property.symbolic_name)">
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
          <div class="flex justify-start justify-items-center p-5">
            <PrimaryButton
              type="button"
              :disabled="disabled"
              @click="addProcessModalIsVisible = true, processIndex = processIdx"
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

  <AddProcessModal
    v-model="addProcessModalIsVisible"
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
import PrimaryButton from "../../../../Components/PrimaryButton.vue";
import AddProcessModal from "@/Components/Modals/AddProcessModal.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";

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
    TrashIcon,
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
    selectedProcesses: {
      type: Array,
      required: false,
    },
    nextStepRequest: {
      type: Boolean,
      required: true,
    },
    templates : {
        type: Array,
        required: true
    }
  },

  emits: ["completed", "incompleted"],

  setup(props, ctx) {
    const store = useStore();

    const errors = ref({});
    const processIndex = ref();

    const addProcessModalIsVisible = ref(false);

    const propProcesses = props.processes.map((p) => ({
      key: p.id,
      value: p.name,
      parent: p.category_id,
      processElements: [],
      props: sortProperties(p.template_properties),
      data: transformPropsToData(p.template_properties),
    }));

    const storeTemplate = computed(() => store.getters["source/template"]);
    const disabled = computed(() => storeTemplate.value 
      ? !props.templates.find((t) => t.id === storeTemplate.value.key).values.processes 
      : true
    );

    const storeSelectedEquipment = computed(() => store.getters["source/selectedEquipment"]);
    const selectedEquipment = ref(window._.cloneDeep(storeSelectedEquipment.value));
    const equipments = ref([]);

    const storeProcesses = computed(() => store.getters["source/processes"]);
    const storeSelectedProcesses = computed(() => store.getters["source/selectedProcesses"]);

    const processes = ref(
      storeProcesses.value.length
        ? window._.cloneDeep(storeProcesses.value)
        : propProcesses
    );

    const propsProcessElement = processes.value.filter(e => e.key != 13);

    const selectedProcesses = ref(window._.cloneDeep(storeSelectedProcesses.value));

    const commitProcesses = window._.debounce(
      () =>
        store.commit("source/setSelectedProcesses", {
          selectedProcesses: window._.cloneDeep(selectedProcesses),
        }),
      500
    );

    watch(selectedProcesses, () => commitProcesses(), {
      deep: true,
      immediate: true,
    });

    const onAddProcess = (process) => {
      const newProcess = window._.cloneDeep(process ?? processes.value.find(e => e.key == 13));

      if (!Object.keys(newProcess.props).length === 0) return;

      newProcess.data = transformPropsToData(newProcess.props, selectedEquipment);

      process ? selectedProcesses.value[processIndex.value].processElements.push(newProcess) : selectedProcesses.value.push(newProcess);
    };

    const onRemoveProcess = (index) => selectedProcesses.value.splice(index, 1);
    const onRemoveProcessElement = (index, elementIdx) => selectedProcesses.value[index].processElements.splice(elementIdx, 1);

    watch(
      () => selectedEquipment.value,
      (options) => {
        equipments.value = [];
        options.forEach((equipment) => {
          equipments.value.push({
            key: equipment.identify,
            value: `${equipment.value} | ${equipment.data.name}`
          });
        })
      }, 
      {
        deep: true,
        immediate: true,
      }
    );

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        errors.value = {};

        for (const [index, process] of selectedProcesses.value.entries()) {
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
      addProcessModalIsVisible,
      propsProcessElement,
      processIndex,
      processes,
      selectedProcesses,
      equipments,
      disabled,
      onAddProcess,
      onRemoveProcess,
      onRemoveProcessElement,
    };
  },
};
</script>

