<template>
  <slide-over
    v-model="open"
    title="Edit Source"
    subtitle=" Get started by filling in the information below to create your new Source. This Source will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-green-200"
    subtitleTextColor="text-green-300"
  >
    <SourceEditStep1
      :instance="instance"
      :templates="templates"
      :locations="locations"
      v-if="currentStep === 1"
    />

    <SourceEditStep2
      :instance="instance"
      :equipmentsCategories="equipmentsCategories"
      :equipments="equipments"
      v-if="currentStep === 2"
    />

    <SourceEditStep3
      :instance="instance"
      :processesCategories="processesCategories"
      :processes="processes"
      v-if="currentStep === 3"
    />

    <!-- <SourceEditStep4
      :equipmentsCategories="equipmentsCategories"
      :equipments="equipments"
      :instance="instance"
      v-if="currentStep === 4"
    /> -->

    <template #actions>
      <div class="flex justify-start w-full">
        <BulletSteps :steps="steps" />
      </div>

      <SecondaryButton
        type="button"
        @click="onPreviousStep"
        :disabled="currentStep === 1"
      >

        <ChevronLeftIcon class="w-6 h-6" />
      </SecondaryButton>
      <SecondaryButton
        type="button"
        @click="onNextStep"
        :disabled="currentStep === steps.length"
      >
        <ChevronRightIcon class="w-6 h-6" />
      </SecondaryButton>
      <SecondaryOutlinedButton
        type="button"
        @click="onCancel"
      >
        Cancel
      </SecondaryOutlinedButton>
      <PrimaryButton
        type="button"
        @click="onNextStep"
        :disabled="currentStep !== steps.length"
      >
        Save
        <!-- <span v-if="currentStep === steps.length">Save</span>
        <span v-else>Next</span> -->
      </PrimaryButton>
    </template>

  </slide-over>
</template>

<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout";
import SlideOver from "@/Components/SlideOver.vue";
import SourceEditStep1 from "@/Pages/Objects/Sources/SourceEditWizard/SourceEditStep1.vue";
import SourceEditStep2 from "@/Pages/Objects/Sources/SourceEditWizard/SourceEditStep2.vue";
import SourceEditStep3 from "@/Pages/Objects/Sources/SourceEditWizard/SourceEditStep3.vue";
import SourceEditStep4 from "@/Pages/Objects/Sources/SourceEditWizard/SourceEditStep4.vue";
import BulletSteps from "@/Components/Wizards/BulletSteps.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";

export default {
  components: {
    AppLayout,
    SlideOver,
    SourceEditStep1,
    SourceEditStep2,
    SourceEditStep3,
    SourceEditStep4,
    BulletSteps,
    PrimaryButton,
    SecondaryButton,
    SecondaryOutlinedButton,
    ChevronLeftIcon,
    ChevronRightIcon,
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
    equipmentsCategories: {
      type: Array,
      required: true,
    },
    equipments: {
      type: Array,
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
    locations: {
      type: Array,
      required: true,
    },
    instance: {
      type: Object,
      required: true,
    },
  },

  emits: ["update:modelValue"],

  setup(props) {
    const store = useStore();
    console.log(props.processes);
    const currentStep = ref(1);

    const mapStepStatus = (index) =>
      currentStep.value === index
        ? "current"
        : currentStep.value < index
        ? "upcoming"
        : "complete";

    const onNextStep = () => currentStep.value++;
    const onPreviousStep = () => currentStep.value--;

    const onRouteRequest = (route, props) => {
      store.dispatch("objects/showSlide", { route, props });
    };

    const onCancel = () => {
      store.dispatch("objects/showSlide", { route: "objects.list" });
    };

    const steps = computed(() => [
      {
        name: "Details",
        status: mapStepStatus(1), // status: current | complete | upcoming
      },
      {
        name: "Equipments",
        status: mapStepStatus(2),
      },
      {
        name: "Processes",
        status: mapStepStatus(3),
      },
      {
        name: "Scripts",
        status: mapStepStatus(4),
      },
    ]);

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    return {
      currentStep,
      onNextStep,
      onPreviousStep,
      onCancel,
      onRouteRequest,
      steps,
      open,
    };
  },
};
</script>

