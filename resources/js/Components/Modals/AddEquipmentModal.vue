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
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                <DatabaseIcon
                  class="h-6 w-6 text-yellow-600"
                  aria-hidden="true"
                />
              </div>
              <div class="mt-3 sm:mt-5">
                <DialogTitle
                  as="h3"
                  class="text-lg text-center leading-6 font-medium text-gray-900"
                >
                  Add an Equipment
                </DialogTitle>
                <div class="mt-2">
                  <SelectMenu
                    v-model="selectedEquipmentCategory"
                    :options="equipmentCategories"
                    label="Category"
                  ></SelectMenu>
                </div>
                <div class="mt-5">
                  <SelectMenu
                    :disabled="!equipmentAreAvailable"
                    v-model="selectedEquipment"
                    :options="availableEquipment"
                    label="Equipment"
                  ></SelectMenu>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
              <SecondaryOutlinedButton
                type="button"
                ref="cancelButtonRef"
                class="sm:col-start-1"
                @click="open = false"
              >
                Cancel
              </SecondaryOutlinedButton>
              <PrimaryButton
                type="button"
                @click="onConfirmation"
                :disabled="!canAddEquipment || processing"
                class="sm:col-start-2"
              >
                Confirm
              </PrimaryButton>
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
import { DatabaseIcon } from "@heroicons/vue/outline";

import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";

export default {
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    DatabaseIcon,
    PrimaryButton,
    SecondaryOutlinedButton,
    SelectMenu,
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    equipmentCategories: {
      type: Array,
      required: true,
    },
    equipment: {
      type: Array,
      required: true,
    },
  },

  emits: ["update:modelValue", "confirmation"],

  setup(props, { emit }) {
    const processing = ref(false);
    const availableEquipment = ref([]);
    const selectedEquipmentCategory = ref({});
    const selectedEquipment = ref({});

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const equipmentCategories = computed(() =>
      props.equipmentCategories.map((ec) => ({
        key: ec.id,
        value: ec.name,
      }))
    );

    const equipmentAreAvailable = computed(() => {
      if (!Object.keys(selectedEquipmentCategory.value).length) return false;

      const equipmentThatMatch = props.equipment.filter(
        (e) => e.parent == selectedEquipmentCategory.value.key
      );

      if (!equipmentThatMatch.length) return false;

      return true;
    });

    const canAddEquipment = computed(() => {
      if (
        Object.keys(selectedEquipmentCategory.value).length &&
        Object.keys(selectedEquipment.value).length
      )
        return true;
      return false;
    });

    

    watch(
      selectedEquipmentCategory,
      (selectedEquipmentCategory) => {
        availableEquipment.value = props.equipment.filter(
          (e) => e.parent == selectedEquipmentCategory.key
        );
      },
      { deep: true }
    );

    const onConfirmation = () => {
      processing.value = true;

      open.value = false;

      emit("confirmation", selectedEquipment.value);

      setTimeout(() => (processing.value = false), 200); // make the confirm button pressable again
    };

    return {
      equipmentCategories,
      selectedEquipmentCategory,
      availableEquipment,
      selectedEquipment,
      open,
      onConfirmation,
      equipmentAreAvailable,
      canAddEquipment,
      processing,
    };
  },
};
</script>
