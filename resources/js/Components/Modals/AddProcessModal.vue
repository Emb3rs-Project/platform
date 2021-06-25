<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <TransitionRoot
    as="template"
    :show="open"
  >
    <Dialog
      as="div"
      static
      class="fixed z-10 inset-0 overflow-y-auto"
      @close="open = false"
      :open="open"
    >
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span
          class="hidden sm:inline-block sm:align-middle sm:h-screen"
          aria-hidden="true"
        >
          &#8203;
        </span>
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to="opacity-100 translate-y-0 sm:scale-100"
          leave="ease-in duration-200"
          leave-from="opacity-100 translate-y-0 sm:scale-100"
          leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <BeakerIcon
                  class="h-6 w-6 text-yellow-600"
                  aria-hidden="true"
                />
              </div>
              <div class="mt-3 sm:mt-5">
                <DialogTitle
                  as="h3"
                  class="text-lg text-center leading-6 font-medium text-gray-900"
                >
                  Add an Process
                </DialogTitle>
                <div class="mt-2">
                  <select-menu
                    v-model="selectedProcessCategory"
                    :options="processesCategories"
                    :label="'Category'"
                  ></select-menu>
                </div>
                <div class="mt-5">
                  <select-menu
                    :disabled="!processesAreAvailable"
                    v-model="selectedProcess"
                    :options="availableProcesses"
                    :label="'Process'"
                  ></select-menu>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
              <secondary-outlined-button
                type="button"
                @click="open = false"
                ref="cancelButtonRef"
                class="sm:col-start-1"
              >
                Cancel
              </secondary-outlined-button>
              <primary-button
                type="button"
                @click="onConfirmation"
                :disabled="!canAddProcess"
                class="sm:col-start-2"
              >
                Confirm
              </primary-button>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import { ref, computed, watch } from "vue";

import {
  Dialog,
  DialogOverlay,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { BeakerIcon } from "@heroicons/vue/outline";

import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";

export default {
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    BeakerIcon,
    TransitionChild,
    TransitionRoot,
    PrimaryButton,
    SecondaryOutlinedButton,
    SelectMenu,
  },

  props: {
    modelValue: {
      type: Boolean,
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
  },

  emits: ["update:modelValue", "confirmation"],

  setup(props, { emit }) {
    const availableProcesses = ref([]);
    const selectedProcessCategory = ref(null);
    const selectedProcess = ref(null);

    const processesCategories = computed(() =>
      props.processesCategories.map((p) => ({
        key: p.id,
        value: p.name,
      }))
    );

    const processesAreAvailable = computed(() => {
      if (!selectedProcessCategory.value) return false;

      const processesThatMatch = props.processes.filter(
        (p) => p.parent == selectedProcessCategory.value.key
      );

      if (!processesThatMatch.length) return false;

      return true;
    });

    const canAddProcess = computed(() => {
      if (selectedProcessCategory.value && selectedProcess.value) return true;
      return false;
    });

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    watch(selectedProcessCategory, (selectedProcessCategory) => {
      availableProcesses.value = props.processes.filter(
        (p) => p.parent == selectedProcessCategory.key
      );
    });

    const onConfirmation = () => {
      emit("confirmation", selectedProcess.value);
      open.value = false;
    };

    return {
      processesCategories,
      selectedProcessCategory,
      availableProcesses,
      selectedProcess,
      open,
      onConfirmation,
      processesAreAvailable,
      canAddProcess,
    };
  },
};
</script>
