<template>
  <!-- TODO: maybe modularize it more, we'll see -->
  <!-- Processes -->
  <div class="flex justify-end justify-items-center p-5">
    <primary-button type="button" @click="addProcess">
      <BeakerIcon class="h-6 w-6 mr-2" aria-hidden="true" />
      Add Process
    </primary-button>
  </div>
  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    v-for="process in processes"
    :key="process"
  >
    <div class="sm:col-span-3">
      <Disclosure as="div" v-slot="{ open }">
        <dt class="text-lg">
          <DisclosureButton
            class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none"
          >
            <span class="font-medium text-gray-900">
              {{ process.value }}
            </span>
            <span class="ml-6 h-7 flex items-center">
              <ChevronDownIcon
                :class="[
                  open ? '-rotate-180' : 'rotate-0',
                  'h-6 w-6 transform',
                ]"
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
          <DisclosurePanel as="dd" class="mt-2 pr-12">
            <div
              class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
              v-for="property in process.props"
              :key="property"
            >
              <div>
                <label
                  class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
                >
                  {{ property.property.name }}
                </label>
              </div>
              <div class="sm:col-span-2">
                <div v-if="property.property.inputType === 'text'">
                  <text-input
                    v-model="form.process.data[property.property.symbolic_name]"
                    :unit="property.unit.symbol"
                    :placeholder="property.property.name"
                    :required="property.required"
                  >
                  </text-input>
                </div>
                <div v-else-if="property.property.inputType === 'select'">
                  <select-menu
                    v-model="form.process.data[property.property.symbolic_name]"
                    :options="property.property.data.options"
                    :required="property.required"
                  >
                  </select-menu>
                </div>
              </div>
            </div>
          </DisclosurePanel>
        </transition>
      </Disclosure>
    </div>
  </div>

  <add-process-modal
    v-model="modalIsVisible"
    :processesCategories="processesCategories"
    :processes="processes"
    @confirmation="onAddProcess"
  >
  </add-process-modal>
</template>

<script>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon, BeakerIcon } from "@heroicons/vue/outline";

import SelectMenu from "../../../../Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "../../../../Components/NewLayout/Forms/TextInput.vue";
import PrimaryButton from "../../../../Components/NewLayout/PrimaryButton.vue";
import AddProcessModal from "../../../../Components/NewLayout/Modals/AddProcessModal.vue";
// import Disclosure from "../../../../Components/NewLayout/Wizards/Disclosure.vue";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
    BeakerIcon,
    AddProcessModal,

    SelectMenu,
    TextInput,
    PrimaryButton,
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
  },

  emits: ["completed"],

  setup(props) {
    const form = useForm({
      process: {
        data: {},
      },
    });

    const processes = ref(
      props.processes.map((p) => ({
        key: p.id,
        value: p.name,
        parent: p.category_id,
        props: p.template_properties,
      }))
    );

    const modalIsVisible = ref(false);

    const addProcess = () => (modalIsVisible.value = true);

    const onAddProcess = (addedProcess) => {
      // TODO: Add it to the vuex store

      const process = processes.value.find((p) => p.key === addedProcess.key);

      const proc = {
        id: addedProcess.key,
        data: {},
        template: process,
      };

      for (const prop of process.props) {
        proc.data[prop.property.symbolic_name] = prop.default_value
          ? prop.default_value
          : "";
      }

      processes.value = [...processes.value, process];
    };

    return {
      form,
      processes,
      modalIsVisible,
      addProcess,
      onAddProcess,
    };
  },
};
</script>

