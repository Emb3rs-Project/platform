<template>
  <slide-over
    v-model="open"
    title="Source Details"
    subtitle="Below, you can see the details that are associated to the currently selected Source."
    headerBackground="bg-red-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <source-detail-1
      :instance="instance"
      v-if="currentStep === 1"
    ></source-detail-1>

    <template #actions>
      <div class="flex justify-start w-full">
        <bullet-steps :steps="steps"></bullet-steps>
      </div>
      <secondary-button
        type="button"
        @click="onPrevStep"
        :disabled="currentStep === 1"
      >
        <i class="fas fa-chevron-left"></i>
      </secondary-button>
      <secondary-button
        type="button"
        @click="onNextStep"
        :disabled="currentStep === steps.length"
      >
        <i class="fas fa-chevron-right"></i>
      </secondary-button>
      <secondary-outlined-button type="button" @click="open = false">
        Cancel
      </secondary-outlined-button>
      <primary-button
        @click="onRouteRequest('objects.sinks.edit', instance.id)"
      >
        Edit
      </primary-button>
    </template>
  </slide-over>
</template>

<script>
import { computed, ref } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/NewLayout/SlideOver.vue";
import SelectMenu from "@/Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "@/Components/NewLayout/Forms/TextInput.vue";
import PrimaryButton from "@/Components/NewLayout/PrimaryButton.vue";
import SecondaryButton from "@/Components/NewLayout/SecondaryButton.vue";
import SecondaryOutlinedButton from "@/Components/NewLayout/SecondaryOutlinedButton.vue";
import SourceDetail1 from "./SourceDetailWizard/SourceDetail-1";
import BulletSteps from "../../../Components/NewLayout/Wizards/BulletSteps.vue";

export default {
  components: {
    AppLayout,
    SlideOver,
    SelectMenu,
    TextInput,
    PrimaryButton,
    SecondaryButton,
    SecondaryOutlinedButton,
    SourceDetail1,
    BulletSteps,
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    instance: {
      type: Object,
      required: true,
    },
  },

  setup(props, { emit }) {
    const store = useStore();

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const onRouteRequest = (route, props) => {
      store.dispatch("objects/showSlide", { route, props });
    };

    const mapStepStatus = (index) =>
      currentStep.value === index
        ? "current"
        : currentStep.value < index
        ? "upcoming"
        : "complete";

    const onNextStep = () => currentStep.value++;
    const onPrevStep = () => currentStep.value--;

    const currentStep = ref(1);
    const steps = computed(() => [
      {
        name: "Source Details",
        component: "Objects/Sources/SourceCreateWizard/Step1.vue",
        status: mapStepStatus(1), // status: current | complete | upcoming
      },
      {
        name: "Equipments",
        component: "Objects/Sources/SourceCreateWizard/Step2.vue",
        status: mapStepStatus(2),
      },
      {
        name: "Processes",
        component: "Objects/Sources/SourceCreateWizard/Step3.vue",
        status: mapStepStatus(3),
      },
      {
        name: "Scripts",
        component: "Objects/Sources/SourceCreateWizard/Step4.vue",
        status: mapStepStatus(4),
      },
    ]);

    return {
      open,
      steps,
      onRouteRequest,
      currentStep,
      onNextStep,
      onPrevStep,
    };
  },
};
</script>

<style>
</style>
