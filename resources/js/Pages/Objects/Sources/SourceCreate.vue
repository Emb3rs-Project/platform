<template>
  <slide-over
    v-model="open"
    title="New Source"
    subtitle=" Get started by filling in the information below to create your new Source. This Source will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-100"
    subtitleTextColor="text-gray-200"
  >
    <component
      v-bind="currentStepProps"
      :is="stepComponent"
      v-show="stepComponent"
      @completed="onStepCompleted"
    ></component>

    <template #actions>
      <div class="flex justify-start w-full">
        <bullet-steps :steps="steps"></bullet-steps>
      </div>

      <secondary-button
        type="button"
        @click="navigateToPreviousStep"
        :disabled="currentStepIndex === 0"
      >
        Previous
      </secondary-button>
      <primary-button type="button" @click="navigateToNextStep">
        <span v-if="currentStepIndex + 1 === steps.length">Save</span>
        <span v-else>Next</span>
      </primary-button>
    </template>
  </slide-over>
</template>

<script>
import { ref, watch, computed, defineAsyncComponent } from "vue";
// import { useForm } from "@inertiajs/inertia-vue3";

import PrimaryButton from "../../../Components/NewLayout/PrimaryButton.vue";
import SecondaryButton from "../../../Components/NewLayout/SecondaryButton.vue";
import SlideOver from "../../../Components/NewLayout/SlideOver.vue";
import SelectMenu from "../../../Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "../../../Components/NewLayout/Forms/TextInput.vue";
import BulletSteps from "../../../Components/NewLayout/Wizards/BulletSteps.vue";

export default {
  components: {
    PrimaryButton,
    SecondaryButton,
    SlideOver,
    SelectMenu,
    TextInput,
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
    equipmentCategories: {
      type: Array,
      required: true,
    },
    locations: {
      type: Array,
      required: true,
    },
  },

  emits: ["update:modelValue"],

  setup(props, { emit }) {
    // status: current | complete | upcoming
    const steps = ref([
      {
        name: "Source Details",
        component: "Objects/Sources/SourceCreateWizard/Step1.vue",
        status: "current",
      },
      {
        name: "Equipments",
        component: "Objects/Sources/SourceCreateWizard/Step2.vue",
        status: "upcoming",
      },
      {
        name: "Processes",
        component: "Objects/Sources/SourceCreateWizard/Step3.vue",
        status: "upcoming",
      },
      {
        name: "Scripts",
        component: "Objects/Sources/SourceCreateWizard/Step4.vue",
        status: "upcoming",
      },
    ]);
    const currentStep = ref(steps.value[0]);
    const currentStepProps = ref({ objects: null });

    const stepComponent = computed(() => {
      // @geocfu: i really dont like how this gets updated reactively rn.
      // TODO: we MUST check it again
      if (currentStep.value)
        return defineAsyncComponent(() =>
          import(`@/Pages/${currentStep.value.component}`)
        );
    });

    watch(
      currentStep,
      (currentStep) => {
        switch (currentStep.name) {
          case "Source Details":
            currentStepProps.value.objects = props.templates;
            break;
          case "Equipments":
            currentStepProps.value.objects = props.equipments;

            break;
          case "Processes":
            currentStepProps.value.objects = props.equipments;
            break;
          case "Scripts":
            currentStepProps.value.objects = props.equipments;
            break;

          default:
            break;
        }
      },
      { immediate: true, deep: true }
    );

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const currentStepIndex = computed(() =>
      steps.value.findIndex((step) => step.name === currentStep.value.name)
    );

    const navigateToPreviousStep = () => {
      if (currentStepIndex.value !== 0) {
        steps.value[currentStepIndex.value].status = "upcoming";
        steps.value[currentStepIndex.value - 1].status = "current";
        currentStep.value = steps.value[currentStepIndex.value - 1];

        return;
      }
    };

    const navigateToNextStep = () => {
      if (currentStepIndex.value !== steps.length) {
        steps.value[currentStepIndex.value].status = "complete";
        steps.value[currentStepIndex.value + 1].status = "current";
        currentStep.value = steps.value[currentStepIndex.value + 1];

        console.log(currentStepIndex.value);
        console.log(currentStep.value);
        console.log(steps.value);

        return;
      }
    };

    const onStepCompleted = () => {
      //   const currentStepIndex = steps.findIndex(
      //     (step) => currentStep.value.name === step.name
      //   );
      //   if (currentStepIndex + 1 === steps.length) {
      //     actionButton.value.text = "Save";
      //     actionButton.value.action = "Save";
      //   } else if (currentStepIndex + 1 < steps.length) {
      //     currentStep.value = steps[currentStepIndex + 1];
      //   }
      //   currentStep.value.status = "completed";
    };

    return {
      steps,
      currentStep,
      currentStepIndex,
      currentStepProps,

      stepComponent,
      //   form,
      open,
      onStepCompleted,
      navigateToPreviousStep,
      navigateToNextStep,
    };
  },
};
</script>


