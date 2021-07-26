<template>
  <SiteHead title="Create a Source" />

  <SlideOver
    title="New Source"
    subtitle=" Get started by filling in the information below to create your new Source. This Source will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-100"
    subtitleTextColor="text-gray-200"
  >
    <SourceCreateStep1
      :templates="templates"
      :locations="locations"
      v-if="currentStep === 1"
    />

    <!-- <SourceCreateStep2
      :equipmentsCategories="equipmentsCategories"
      :equipments="equipments"
      v-if="currentStep === 2"
    />

    <SourceCreateStep3
      :processesCategories="processesCategories"
      :processes="processes"
      v-if="currentStep === 3"
    /> -->

    <template #actions>
      <div class="flex justify-start w-full">
        <BulletSteps :steps="steps" />
      </div>
      <SecondaryButton
        type="button"
        @click="onCancel"
      >
        Cancel
      </SecondaryButton>
      <SecondaryButton
        type="button"
        @click="onPreviousStep"
        :disabled="currentStep === 1"
      >
        Previous
      </SecondaryButton>
      <PrimaryButton
        type="button"
        @click="onNextStep"
        :disabled="currentStep !== steps.length"
      >
        Next
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";
// import { useForm } from "@inertiajs/inertia-vue3";

import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "../../../Components/SlideOver.vue";
import SourceCreateStep1 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep1.vue";
import SourceCreateStep2 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep2.vue";
import SourceCreateStep3 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep3.vue";

import PrimaryButton from "../../../Components/PrimaryButton.vue";
import SecondaryButton from "../../../Components/SecondaryButton.vue";
import BulletSteps from "@/Components/Wizards/BulletSteps.vue";

export default {
  components: {
    SiteHead,
    SlideOver,
    SourceCreateStep1,
    SourceCreateStep2,
    SourceCreateStep3,
    PrimaryButton,
    SecondaryButton,
    BulletSteps,
  },

  props: {
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
  },

  setup(props) {
    const store = useStore();
    const currentStep = ref(1);

    const mapStepStatus = (index) =>
      currentStep.value === index
        ? "current"
        : currentStep.value < index
        ? "upcoming"
        : "complete";

    const steps = computed(() => [
      {
        name: "Source Details",
        status: mapStepStatus(1),
      },
      {
        name: "Equipment",
        status: mapStepStatus(2),
      },
      {
        name: "Processes",
        status: mapStepStatus(3),
      },
    ]);

    const onNextStep = () => currentStep.value++;
    const onPreviousStep = () => currentStep.value--;

    const onCancel = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });
    return {
      steps,
      currentStep,
      onPreviousStep,
      onNextStep,
      onCancel,
    };
  },
};
</script>


