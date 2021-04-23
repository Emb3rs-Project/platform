<template>
  <slide-over
    v-model="open"
    title="New Source"
    subtitle=" Get started by filling in the information below to create your new Source. This Source will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-green-200"
    subtitleTextColor="text-green-300"
  >
    <template #actions>
      <div class="flex justify-start w-full">
        <bullet-steps :steps="steps" class="ml-0"></bullet-steps>
      </div>

      <button
        type="button"
        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        @click="slideOverIsOpen = false"
      >
        Cancel
      </button>
      <button
        type="submit"
        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
      >
        Save
      </button>
    </template>
  </slide-over>
</template>

<script>
import { ref, watch, computed } from "vue";
// import { useForm } from "@inertiajs/inertia-vue3";

import PrimaryButton from "../../../Components/PrimaryButton.vue";
import SecondaryButton from "../../../Components/SecondaryButton.vue";
import SlideOver from "../../../Components/NewLayout/SlideOver.vue";
import BulletSteps from "../../../Components/NewLayout/Wizards/BulletSteps.vue";

export default {
  components: {
    PrimaryButton,
    SecondaryButton,
    SlideOver,
    BulletSteps,
  },
  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    templates: {
      type: Array,
      required: true,
    },
    equipments: {
      type: Array,
      required: true,
    },
    locations: {
      type: Array,
      required: true,
    },
    equipmentCategories: Array,
  },

  emits: ["update:modelValue"],

  setup(props) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });
    const steps = [
      { name: "Step 1", href: "#", status: "current" },
      { name: "Step 2", href: "#", status: "upcoming" },
      { name: "Step 3", href: "#", status: "upcoming" },
      { name: "Step 4", href: "#", status: "upcoming" },
    ];

    return {
      open,
      steps,
    };
  },
};
</script>

<style>
</style>


