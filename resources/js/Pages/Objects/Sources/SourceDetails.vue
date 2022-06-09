<template>
  <SiteHead title="Source Details" />

  <SlideOver
    type="source"
    title="Source Details"
    subtitle="Below, you can see the details that are associated to the currently selected Source."
  >
    <template #stickyTop>
      <Steps
        :steps="steps"
        class="p-4"
      />
    </template>

    <SourceDetailsStep1
      v-if="currentStep === 1"
      :instance="instance"
    />

    <SourceDetailsStep2
      v-if="currentStep === 2"
      :instance="instance"
      :equipment="equipment"
    />

    <SourceDetailsStep3
      v-if="currentStep === 3"
      :instance="instance"
      :processes="processes"
    />

    <template #actions>
      <div class="flex justify-start w-full gap-x-4">
        <!-- <BulletSteps :steps="steps" /> -->
        <div>
          <PrimaryButton
            type="button"
            @click="onPrevStep"
            :disabled="currentStep === 1"
          >
            <ChevronLeftIcon
              class="h-6 w-auto"
              aria-hidden="true"
            />
            Previous
          </PrimaryButton>
        </div>
        <div>
          <PrimaryButton
            type="button"
            @click="onNextStep"
            :disabled="currentStep === steps.length"
          >
            Next
            <ChevronRightIcon
              class="h-6 w-auto"
              aria-hidden="true"
            />
          </PrimaryButton>
        </div>

      </div>

      <SecondaryOutlinedButton
        type="button"
        @click="onCancel"
      >
        Cancel
      </SecondaryOutlinedButton>

      <PrimaryButton
        type="button"
        @click="onRouteRequest('objects.sources.edit', instance.id)"
        :disabled="startLinks"
      >
        Edit
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { computed, ref } from "vue";
import { useStore } from "vuex";

import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOvers/SlideOver.vue";
import Steps from "@/Components/Wizards/Steps.vue";
import SourceDetailsStep1 from "./SourceDetailsWizard/SourceDetailsStep1.vue";
import SourceDetailsStep2 from "./SourceDetailsWizard/SourceDetailsStep2.vue";
import SourceDetailsStep3 from "./SourceDetailsWizard/SourceDetailsStep3.vue";
import BulletSteps from "@/Components/Wizards/BulletSteps.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";

export default {
  components: {
    SiteHead,
    SlideOver,
    Steps,
    SourceDetailsStep1,
    SourceDetailsStep2,
    SourceDetailsStep3,
    BulletSteps,
    SecondaryOutlinedButton,
    ChevronLeftIcon,
    ChevronRightIcon,
    SecondaryButton,
    PrimaryButton,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
    equipment: {
      type: Array,
      required: true,
    },
    processes: {
      type: Array,
      required: true,
    },
  },

  setup() {
    const store = useStore();
    const currentStep = ref(1);

    const startLinks = computed(
      () => store.getters["map/startLinks"]
    );

    const mapStepStatus = (index) =>
      currentStep.value === index
        ? "current"
        : currentStep.value < index
        ? "upcoming"
        : "complete";

    const steps = computed(() => [
      {
        id: "Properties",
        name: "Information & Properties",
        status: mapStepStatus(1), // status: current | complete | upcoming
      },
      {
        id: "Equipment",
        name: "Equipment Information",
        status: mapStepStatus(2),
      },
      {
        id: "Processes",
        name: "Processes Information",
        status: mapStepStatus(3),
      },
    ]);

    const onNextStep = () => currentStep.value++;
    const onPrevStep = () => currentStep.value--;

    const onRouteRequest = (route, props) => {
      store.dispatch("objects/showSlide", { route, props });
    };

    const onCancel = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      currentStep,
      onRouteRequest,
      startLinks,
      steps,
      onNextStep,
      onPrevStep,
      onCancel,
    };
  },
};
</script>

<style>
</style>
