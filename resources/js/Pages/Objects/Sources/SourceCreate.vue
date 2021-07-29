<template>
  <SiteHead title="Create a Source" />

  <SlideOver
    title="New Source"
    subtitle="Get started by filling in the information below to create your new Source. This Source will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-100"
    subtitleTextColor="text-gray-200"
  >
    <!-- <Steps
      :steps="steps"
      class="p-4"
    /> -->

    <SourceCreateStep1
      v-if="currentStep === 1"
      :templates="templates"
      :locations="locations"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted()"
    />

    <SourceCreateStep2
      v-if="currentStep === 2"
      :equipmentsCategories="equipmentsCategories"
      :equipments="equipments"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted()"
    />

    <SourceCreateStep3
      v-if="currentStep === 3"
      :processesCategories="processesCategories"
      :processes="processes"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted()"
    />

    <template #actions>
      <div class="flex justify-start w-full">
        <BulletSteps :steps="steps" />
      </div>

      <SecondaryOutlinedButton
        type="button"
        @click="nextStepRequest = !nextStepRequest"
      >
        TESTING
      </SecondaryOutlinedButton>

      <SecondaryButton
        type="button"
        @click="onPreviousStep"
        :disabled="currentStep === 1"
      >
        <ChevronLeftIcon class="h-6 w-auto" />
      </SecondaryButton>
      <SecondaryButton
        type="button"
        @click="onNextStep"
        :disabled="currentStep === steps.length"
      >
        <ChevronRightIcon class="h-6 w-auto" />
      </SecondaryButton>

      <PrimaryButton
        type="button"
        @click="onSubmit"
        :disabled="currentStep !== steps.length"
      >
        Save
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { ref, computed, watch } from "vue";
import { useStore } from "vuex";
// import { useForm } from "@inertiajs/inertia-vue3";

import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "../../../Components/SlideOver.vue";
import SourceCreateStep1 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep1.vue";
import SourceCreateStep2 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep2.vue";
import SourceCreateStep3 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep3.vue";

import PrimaryButton from "../../../Components/PrimaryButton.vue";
import SecondaryButton from "../../../Components/SecondaryButton.vue";
import SecondaryOutlinedButton from "../../../Components/SecondaryOutlinedButton.vue";
import BulletSteps from "@/Components/Wizards/BulletSteps.vue";
import Steps from "@/Components/Wizards/Steps.vue";

import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";

export default {
  components: {
    SiteHead,
    SlideOver,
    Steps,
    SourceCreateStep1,
    SourceCreateStep2,
    SourceCreateStep3,
    BulletSteps,
    SecondaryOutlinedButton,
    SecondaryButton,
    ChevronLeftIcon,
    ChevronRightIcon,
    PrimaryButton,
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
    const nextStepRequest = ref(false);

    const mapStepStatus = (index) =>
      currentStep.value === index
        ? "current"
        : currentStep.value < index
        ? "upcoming"
        : "complete";

    const steps = computed(() => [
      {
        id: "Step 1",
        name: "Properties",
        status: mapStepStatus(1),
      },
      {
        id: "Step 2",
        name: "Equipments",
        status: mapStepStatus(2),
      },
      {
        id: "Step 3",
        name: "Processes",
        status: mapStepStatus(3),
      },
    ]);

    const onNextStep = () => {
      if (!nextStepRequest.value) return;

      currentStep.value++;
      nextStepRequest.value = false;
    };
    const onPreviousStep = () => currentStep.value--;

    const onCompleted = () => (nextStepRequest.value = true);

    const onSubmit = () => {
      if (!nextStepRequest.value) console.error("error submitting the form");
      if (!nextStepRequest.value) return;

      console.log("SUBMITING THE FORM");
    };

    const onCancel = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      steps,
      currentStep,
      onPreviousStep,
      onNextStep,
      onCompleted,
      onSubmit,
      onCancel,
      nextStepRequest,
    };
  },
};
</script>
