<template>
  <SiteHead title="Create a Source" />
  <SlideOver
    type="source"
    title="New Source"
    subtitle="Get started by filling in the information below to create your new Source. This Source will be attached to your currently selected Institution."
  >
    <template #stickyTop>
      <Steps
        :steps="steps"
        class="p-4"
      />
      <div :class="{ 'p-4': incompleteStepAlert }">
        <Alert
          v-model="incompleteStepAlert"
          type="danger"
          message="Please, fill all the required fields before proceeding to the next step."
          :dismissable="false"
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
      :equipmentCategories="equipmentCategories"
      :equipment="equipment"
      :nextStepRequest="nextStepRequest"
      :templates="templates"
      @completed="onCompleted"
      @incompleted="onIncompleted"
    />

    <SourceCreateStep3
      v-if="currentStep === 3"
      :processesCategories="processesCategories"
      :processes="processes"
      :nextStepRequest="nextStepRequest"
      :templates="templates"
      @completed="onCompleted"
      @incompleted="onIncompleted"
    />

    <template #actions>
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
import SlideOver from "@/Components/SlideOvers/SlideOver.vue";
import Steps from "@/Components/Wizards/Steps.vue";
import Alert from "@/Components/Alerts/Alert.vue";
import SourceCreateStep1 from "./SourceCreateWizard/SourceCreateStep1.vue";
import SourceCreateStep2 from "./SourceCreateWizard/SourceCreateStep2.vue";
import SourceCreateStep3 from "./SourceCreateWizard/SourceCreateStep3.vue";
import BulletSteps from "@/Components/Wizards/BulletSteps.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
  components: {
    SiteHead,
    SlideOver,
    Steps,
    Alert,
    SourceCreateStep1,
    SourceCreateStep2,
    SourceCreateStep3,
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
  },

  setup() {
    const store = useStore();

    const currentStep = ref(1);
    const nextStepRequest = ref(false);
    const incompleteStepAlert = ref(false);

    const source = computed(() => store.getters["source/source"]);
    const equipment = computed(() => store.getters["source/selectedEquipment"]);
    const processes = computed(() => store.getters["source/selectedProcesses"]);
    const template = computed(() => store.getters["source/template"]);
    const location = computed(() => store.getters["source/location"]);

    const form = useForm({
      source: {
        data: null,
      },
      equipment: [],
      processes: [],
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
      nextStepRequest.value = false;
      if (currentStep.value === steps.value.length) {
        submit();

        return;
      }

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
          const deepCopyOfFormData = window._.cloneDeep(data);

          // source.data
          const deepCopyOfSource = window._.cloneDeep(source.value);

          for (const property of template.value.properties) {
            const prop = property.property;
            const key = prop.symbolic_name;
            const dataType = prop.dataType.toLowerCase();

            if (!deepCopyOfSource.data.hasOwnProperty(key)) continue;

            if (prop.inputType === "select") {
              // if the property has a value, get it and re-assign the property as a string
              if (Object.keys(deepCopyOfSource.data[key]).length) {
                deepCopyOfSource.data[key] = deepCopyOfSource.data[key].key;
              } else {
               delete deepCopyOfSource.data[key]
              }
            }
          }
          deepCopyOfFormData.source = deepCopyOfSource;

          // equipment
          const deepCopyOfEquipment = window._.cloneDeep(equipment.value);
          if (equipment.value.length) {
            for (const [index, equip] of equipment.value.entries()) {
              if (!Object.keys(equip.data).length) continue;

              for (const property of equip.props) {
                const prop = property.property;
                const key = prop.symbolic_name;
                const dataType = prop.dataType.toLowerCase();

                if (prop.inputType === "select") {
                  // if the property has a value, get it and re-assign the property as a string
                  if (
                    Object.keys(deepCopyOfEquipment[index].data[key]).length
                  ) {
                    deepCopyOfEquipment[index].data[key] =
                      deepCopyOfEquipment[index].data[key].key;
                  } else {
                    delete deepCopyOfEquipment[index].data[key]
                  }
                }
              }
            }
          }
          if (deepCopyOfEquipment.length)
            deepCopyOfFormData.equipment = deepCopyOfEquipment.map((e) => ({
              id: e.key,
              identify: e.identify,
              category_id: e.parent,
              data: e.data,
            }));

          //processes
          const deepCopyOfProcesses = window._.cloneDeep(processes.value);
          if (processes.value.length) {
            for (const [index, process] of processes.value.entries()) {
              if (!Object.keys(process.data).length) continue;

              for (const property of process.props) {
                const prop = property.property;
                const key = prop.symbolic_name;
                const dataType = prop.dataType.toLowerCase();

                if (prop.inputType === "select") {
                  // if the property has a value, get it and re-assign the property as a string
                  if (
                    Object.keys(deepCopyOfProcesses[index].data[key]).length
                  ) {
                    deepCopyOfProcesses[index].data[key] =
                      deepCopyOfProcesses[index].data[key].key;
                  } else {
                    delete deepCopyOfProcesses[index].data[key]
                  }
                }
              }
            }
          }
          if (deepCopyOfProcesses.length)
            deepCopyOfFormData.processes = deepCopyOfProcesses.map((p) => ({
              id: p.key,
              category_id: p.parent,
              processElements: p.processElements,
              data: p.data,
            }));

          //template
          if (Object.keys(template.value).length)
            deepCopyOfFormData.template_id = template.value.key;

          //location
          if (Object.keys(location.value).length) {
            if (typeof location.value.key === "object")
              deepCopyOfFormData.location = location.value.key;
            else deepCopyOfFormData.location_id = location.value.key;
          }

          return deepCopyOfFormData;
        })
        .post(route("objects.sources.store"), {
          onSuccess: () => {
            store.dispatch("map/refreshMap");
            store.commit("objects/setNotify", {
                title: 'Source',
                text: 'Source Created Successfully',
                type: 'success'
            });
            store.dispatch("map/removeMarker", true);
            store.dispatch("objects/showSlide", { route: "objects.list" });
            store.dispatch("source/reset");
          },
        });
    };

    const onCancel = () => {
      store.dispatch("map/removeMarker", true);
      store.dispatch("map/refreshMap");
      store.dispatch("objects/showSlide", { route: "objects.list" });
    };

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
