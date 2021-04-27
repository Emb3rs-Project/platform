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
      v-model="currentStep"
      v-bind="currentStepProps"
      :is="stepComponent"
      @completed="onStepCompleted"
    ></component>

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
        type="button"
        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        @click="navigateToPreviousStep"
        :disabled="currentStepIndex === 0"
      >
        Previous
      </button>
      <button
        type="button"
        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        @click="navigateToNextStep"
      >
        <span v-if="currentStepIndex + 1 === steps.length">Save</span>
        <span v-else>Next</span>
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
    const currentStepIndex = ref(
      steps.value.findIndex((step) => step.name === currentStep.value.name)
    );
    const currentStepProps = ref({ objects: null });

    const form = useForm({
      source: {
        data: {},
      },
    });

    // watch(form, (form) => {
    //   //   console.log(form);
    // });

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
            break;

          default:
            break;
        }
      },
      { immediate: true }
    );

    const stepComponent = computed(() =>
      defineAsyncComponent(() =>
        import(`@/Pages/${currentStep.value.component}`)
      )
    );

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const navigateToPreviousStep = () => {
      if (currentStepIndex.value !== 0) {
        steps.value[currentStepIndex.value].status = "upcoming";
        steps.value[currentStepIndex.value - 1].status = "current";
        currentStep.value = steps.value[currentStepIndex.value - 1];

        return;
      }
    };

    const navigateToNextStep = () => {
      console.log("hello");
      console.log(currentStep.value.name);

      if (currentStepIndex.value !== steps.length) {
        steps.value[currentStepIndex.value].status = "complete";
        steps.value[currentStepIndex.value + 1].status = "current";
        currentStep.value = steps.value[currentStepIndex.value + 1];

        console.log(steps.value);
        console.log(currentStepIndex.value);

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
      form,
      open,
      onStepCompleted,
      navigateToPreviousStep,
      navigateToNextStep,
    };
  },
};
</script>


