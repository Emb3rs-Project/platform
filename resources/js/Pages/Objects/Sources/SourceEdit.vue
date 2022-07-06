<template>
  <SiteHead title="Edit Source" />
  <SlideOver
    type="source"
    title="Edit Source"
    subtitle="Get started by editing the information below to edit your Source."
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
        />
      </div>
    </template>

    <SourceEditStep1
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
    Alert,
    SourceEditStep1,
    SourceEditStep2,
    SourceEditStep3,
    BulletSteps,
    SecondaryOutlinedButton,
    SecondaryButton,
    PrimaryButton,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
    templates: {
      type: Array,
      required: true,
    },
    locations: {
      type: Array,
      required: true,
    },
    equipment: {
      type: Array,
      required: true,
    },
    equipmentCategories: {
      type: Array,
      required: true,
    },
    processes: {
      type: Array,
      required: true,
    },
    processesCategories: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();

    // Clean the source store so we can cache only
    // current wizard's fields for its lifetime
    store.dispatch("source/reset");

    const currentStep = ref(1);
    const nextStepRequest = ref(false);
    const incompleteStepAlert = ref(false);

    const source = computed(() => store.getters["source/source"]);
    const equipment = computed(() => store.getters["source/equipment"]);
    const processes = computed(() => store.getters["source/processes"]);
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
                if (dataType === "string") {
                  deepCopyOfSource.data[key] = "";
                } else {
                  deepCopyOfSource.data[key] = null;
                }
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
                    if (dataType === "text" || dataType === "string") {
                      deepCopyOfEquipment[index].data[key] = "";
                    } else {
                      deepCopyOfEquipment[index].data[key] = null;
                    }
                  }
                }
              }
            }
          }
          if (deepCopyOfEquipment.length)
            deepCopyOfFormData.equipment = deepCopyOfEquipment.map((e) => ({
              id: e.key,
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
                    if (dataType === "text" || dataType === "string") {
                      deepCopyOfProcesses[index].data[key] = "";
                    } else {
                      deepCopyOfProcesses[index].data[key] = null;
                    }
                  }
                }
              }
            }
          }
          if (deepCopyOfProcesses.length)
            deepCopyOfFormData.processes = deepCopyOfProcesses.map((p) => ({
              id: p.key,
              category_id: p.parent,
              data: p.data,
            }));

          //template
          if (Object.keys(template.value).length)
            deepCopyOfFormData.template_id = template.value.key;

          //location
          if (Object.keys(location.value).length) {
            deepCopyOfFormData.location = location.value.key;
            deepCopyOfFormData.location_id = location.value.id;
          }

          return deepCopyOfFormData;
        })
        .patch(route("objects.sources.update", props.instance.id), {
          onSuccess: () => {
            store.dispatch("map/refreshMap");
            store.commit("objects/setNotify", {
                title: 'Source',
                text: 'Source Updated Successfully',
                type: 'success'
            });
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

