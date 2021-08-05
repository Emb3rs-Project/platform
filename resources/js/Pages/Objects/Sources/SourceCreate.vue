<template>
  <SiteHead title="Create a Source" />

  <SlideOver
    title="New Source"
    subtitle="Get started by filling in the information below to create your new Source. This Source will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
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

    <SourceCreateStep1
      v-if="currentStep === 1"
      :templates="templates"
      :locations="locations"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted"
      @incompleted="onIncompleted"
    />

    <SourceCreateStep2
      v-if="currentStep === 2"
      :equipmentsCategories="equipmentsCategories"
      :equipments="equipments"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted"
      @incompleted="onIncompleted"
    />

    <SourceCreateStep3
      v-if="currentStep === 3"
      :processesCategories="processesCategories"
      :processes="processes"
      :nextStepRequest="nextStepRequest"
      @completed="onCompleted"
      @incompleted="onIncompleted"
    />

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
        :disabled="form.processing"
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
import SlideOver from "../../../Components/SlideOver.vue";
import SourceCreateStep1 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep1.vue";
import SourceCreateStep2 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep2.vue";
import SourceCreateStep3 from "@/Pages/Objects/Sources/SourceCreateWizard/SourceCreateStep3.vue";

import InfoAlert from "../../../Components/Alerts/InfoAlert.vue";
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
    SourceCreateStep1,
    SourceCreateStep2,
    SourceCreateStep3,
    InfoAlert,
    BulletSteps,
    ChevronLeftIcon,
    ChevronRightIcon,
    SecondaryOutlinedButton,
    SecondaryButton,
    PrimaryButton,
    Steps,
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
    const incompleteStepAlert = ref(false);

    const source = computed(() => store.getters["source/source"]);
    const equipments = computed(() => store.getters["source/equipments"]);
    const processes = computed(() => store.getters["source/processes"]);
    const scripts = computed(() => store.getters["source/scripts"]);
    const template = computed(() => store.getters["source/template"]);
    const location = computed(() => store.getters["source/location"]);

    const form = useForm({
      source: {
        data: null,
      },
      equipments: null,
      processes: null,
      scripts: null,
      template_id: null,
      location_id: null,
      location: null,
    });

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
      form
        .transform((data) => {
          // We want to transform the "to-send" data, not the original data
          const deepCopyOfData = window._.cloneDeep(data);

          const deepCopyOfSource = window._.cloneDeep(source.value);

          const sourceData = deepCopyOfSource.data;

          if (template.value.properties.length) {
            for (const property of template.value.properties) {
              const prop = property.property;
              const key = prop.symbolic_name;

              if (prop.inputType === "select") {
                // if the property has a value, get it and re-assign the property as a string
                if (Object.keys(sourceData[key]).length) {
                  sourceData[key] = sourceData[key].value;
                }
              }
            }
          }

          deepCopyOfData.source.data = sourceData;

          if (Object.keys(equipments.value).length)
            deepCopyOfData.equipments = equipments.value;

          if (Object.keys(processes.value).length)
            deepCopyOfData.processes = processes.value;

          if (Object.keys(scripts.value).length)
            deepCopyOfData.scripts = scripts.value;

          if (Object.keys(template.value).length)
            deepCopyOfData.template_id = template.value.key;

          if (Object.keys(location.value).length) {
            if (typeof location.value.key === "object")
              deepCopyOfData.location = location.value.key;
            else deepCopyOfData.location_id = location.value.key;
          }

          console.log(deepCopyOfData);

          return deepCopyOfSource;
        })
        .post(route("objects.sources.store"), {
          onSuccess: () => {
            store.dispatch("map/refreshMap");
            store.dispatch("objects/showSlide", { route: "objects.list" });
          },
        });
    };

    const onCancel = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      form,
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
