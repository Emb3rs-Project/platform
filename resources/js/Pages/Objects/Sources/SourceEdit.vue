<template>
  <SiteHead title="Edit Source" />

  <SlideOver
    title="Edit Source"
    subtitle="Get started by editing the information below to edit your Source."
    headerBackground="bg-red-700"
    dismissButtonTextColor="text-gray-100"
    subtitleTextColor="text-gray-200"
  >
    <template #stickyTop>
      <Steps
        :steps="steps"
        class="p-4"
      />
      <div :class="{ 'p-4': incompleteStepAlert }">
        <InfoAlert
          v-model="incompleteStepAlert"
          content="Please, fill all the required fields before proceeding to the next step."
        />
      </div>
    </template>

    <!-- <SourceEditStep1
      v-if="currentStep === 1"
      :instance="instance"
      :templates="templates"
      :locations="locations"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted"
      @incompleted="onIncompleted"
    />

    <SourceEditStep2
      v-if="currentStep === 2"
      :instance="instance"
      :equipmentCategories="equipmentCategories"
      :equipment="equipment"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted"
      @incompleted="onIncompleted"
    />

    <SourceEditStep3
      v-if="currentStep === 3"
      :instance="instance"
      :processesCategories="processesCategories"
      :processes="processes"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted"
      @incompleted="onIncompleted"
    /> -->

    <template #actions>
      <div class="flex justify-start w-full">
        <BulletSteps :steps="steps" />
      </div>

      <SecondaryOutlinedButton
        type="button"
        @click="onCancel"
      >
        Cancel
      </SecondaryOutlinedButton>

      <SecondaryButton
        type="button"
        @click="onPreviousStep"
        :disabled="currentStep === 1"
      >
        Back
      </SecondaryButton>

      <PrimaryButton
        type="button"
        @click="onNextStep"
      >
        <span v-if="currentStep !== steps.length">
          Next
        </span>
        <span v-else>
          Save
        </span>
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { ref, computed } from "vue";
import { useStore } from "vuex";
import { useForm } from "@inertiajs/inertia-vue3";

import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOver.vue";
import Steps from "@/Components/Wizards/Steps.vue";
import InfoAlert from "@/Components/Alerts/InfoAlert.vue";
import SourceEditStep1 from "./SourceEditWizard/SourceEditStep1.vue";
import SourceEditStep2 from "./SourceEditWizard/SourceEditStep2.vue";
import SourceEditStep3 from "./SourceEditWizard/SourceEditStep3.vue";
import BulletSteps from "@/Components/Wizards/BulletSteps.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
  components: {
    SiteHead,
    SlideOver,
    Steps,
    InfoAlert,
    SourceEditStep1,
    SourceEditStep2,
    SourceEditStep3,
    BulletSteps,
    SecondaryOutlinedButton,
    SecondaryButton,
    PrimaryButton,
  },

  props: {
    templates: {
      type: Array,
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

  setup(props) {
    console.log("yiiiiiiiiiies");
    const store = useStore();

    const currentStep = ref(1);
    const nextStepRequest = ref(false);
    const incompleteStepAlert = ref(false);

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
        name: "Equipment",
        status: mapStepStatus(2),
      },
      {
        id: "Step 3",
        name: "Processes",
        status: mapStepStatus(3),
      },
    ]);

    const onPreviousStep = () => currentStep.value--;
    const onNextStep = () => (nextStepRequest.value = true);

    const onCompleted = () => {
      if (currentStep.value === steps.value.length) {
        submit();

        return;
      }

      nextStepRequest.value = false;
      incompleteStepAlert.value = false;
      currentStep.value++;
    };
    const onIncompleted = () => {
      incompleteStepAlert.value = true;
      nextStepRequest.value = false;
    };

    const submit = () => {
      console.log("submit");
    };

    const onCancel = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      currentStep,
      nextStepRequest,
      incompleteStepAlert,
      steps,
      onPreviousStep,
      onNextStep,
      onCompleted,
      onIncompleted,
      onCancel,
    };
  },
};
</script>

