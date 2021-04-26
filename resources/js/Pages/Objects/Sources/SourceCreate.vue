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
      :is="wizardStepComponent"
      @completed="onStepCompleted"
    ></component>

    <template #actions>
      <div class="flex justify-start w-full">
        <bullet-steps :steps="steps" class="ml-0"></bullet-steps>
      </div>

      <div v-if="currentStep"></div>

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
        @click="navigateToNextStep"
      >
        {{ actionButtonText }}
      </button>
    </template>
  </slide-over>
</template>

<script>
import { ref, watch, computed, defineAsyncComponent } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import PrimaryButton from "../../../Components/PrimaryButton.vue";
import SecondaryButton from "../../../Components/SecondaryButton.vue";
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
    locations: {
      type: Array,
      required: true,
    },
    equipmentCategories: Array,
  },

  emits: ["update:modelValue"],

  setup(props) {
    const steps = [
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
    ];

    const currentStep = ref(steps[0]);
    const currentStepProps = ref({ instances: null });
    const actionButton = ref({ text: "", action: "" });

    const form = useForm({
      source: {
        data: {},
      },
    });

    watch(form, (form) => {
      //   console.log(form);
    });

    watch(
      currentStep,
      (currentStep) => {
        switch (currentStep.name) {
          case "Source Details":
            currentStepProps.value.instances = props.templates;
            break;
          case "Equipments":
            currentStepProps.value.instances = props.equipments;
            break;
          case "Processes":
            break;
          case "Scripts":
            break;

          default:
            break;
        }
      },
      { immediate: true }
    );

    const wizardStepComponent = computed(() =>
      defineAsyncComponent(() =>
        import(`@/Pages/${currentStep.value.component}`)
      )
    );

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const navigateToNextStep = () => {
      console.log("nextstep");
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

      currentStep.value.status = "completed";
    };

    return {
      steps,
      currentStep,
      currentStepProps,
      actionButton,
      wizardStepComponent,
      form,
      open,
      onStepCompleted,
      navigateToNextStep,
    };
  },
};
</script>


