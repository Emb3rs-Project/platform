b<template>
  <slide-over
    v-model="open"
    title="New Sink"
    subtitle="Get started by filling in the information below to create your new Sink. This Sink will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Sink Template -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
          Templates
        </label>
      </div>
      <div class="sm:col-span-2">
        <select-menu
          v-model="selectedTemplate"
          :options="templates"
        ></select-menu>
      </div>
    </div>

    <!-- Sink Location -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label
          for="project_name"
          class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
        >
          Locations
        </label>
      </div>
      <div class="sm:col-span-2">
        <select-menu
          v-model="form.location_id"
          :options="locations"
          :disabled="selectedTemplate ? false : true"
        ></select-menu>
      </div>
    </div>

    <!-- Sink Properties -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-for="prop in properties"
      :key="prop.id"
    >
      <div>
        <label
          for="project_name"
          class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
        >
          {{ prop.property.description }}
        </label>
      </div>
      <div class="sm:col-span-2">
        <div v-if="prop.property.inputType === 'text'">
          <text-input
            v-model="form.sink.data[prop.property.symbolic_name]"
            :unit="prop.unit.symbol"
            :label="prop.property.name"
            :placeholder="prop.property.name"
            :required="prop.required"
          >
          </text-input>
        </div>
        <div v-else-if="prop.property.inputType === 'select'">
          <select-menu
            v-model="form.sink.data[prop.property.symbolic_name]"
            :options="prop.property.data.options"
            :disabled="selectedTemplate ? false : true"
            :required="prop.required"
            :label="prop.property.name"
          >
          </select-menu>
        </div>
        <jet-input-error
          :message="form.errors"
          class="mt-2"
        />
      </div>
    </div>

    <template #actions>
      <secondary-outlined-button
        type="button"
        :disabled="form.processing"
        @click="onCancel"
      >
        Cancel
      </secondary-outlined-button>
      <primary-button
        @click="submit"
        :disabled="form.processing"
      >
        Save
      </primary-button>
    </template>
  </slide-over>
</template>

<script>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import { useStore } from "vuex";
import { keyParToSelect } from "../../../Utils/array";

export default {
  components: {
    AppLayout,
    SlideOver,
    SelectMenu,
    TextInput,
    JetInputError,
    PrimaryButton,
    SecondaryOutlinedButton,
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    templates: {
      type: Array,
      default: [],
    },
    locations: {
      type: Array,
      default: [],
    },
  },

  setup(props, { emit }) {
    const store = useStore();
    const templateInfo = ref(null);

    const templates = keyParToSelect(props.templates);
    const selectedTemplate = ref(templates.length ? templates[0] : null);

    const locations = keyParToSelect(props.locations);

    const form = useForm({
      sink: {
        data: {},
      },
      template_id: null,
      location_id: null,
    });

    watch(
      () => store.getters["map/selectedMarker"],
      (val) => {
        console.log("marker", val);
        if (!!val) {
          const oldLocation = locations.find(
            (l) => l.value === "Selected Marker"
          );
          if (oldLocation) {
            oldLocation.key = val;
          } else {
            locations.unshift({
              key: val,
              value: "Selected Marker",
            });
          }
        } else {
          const oldLocationIndex = locations.findIndex(
            (l) => l.value === "Selected Marker"
          );
          if (oldLocationIndex !== -1) {
            locations.splice(oldLocationIndex, 1);
          }
        }
        form.location_id = locations[0];
      },
      { immediate: true }
    );

    watch(
      selectedTemplate,
      (template) => {
        templateInfo.value = props.templates.find((t) => t.id === template.key);
        form.template_id = template.key;

        if (templateInfo.value?.template_properties) {
          for (const prop of templateInfo.value?.template_properties) {
            form.sink.data[prop.property.symbolic_name] = prop.default_value
              ? prop.default_value
              : "";
          }
        }
      },
      { immediate: true }
    );

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const properties = computed(() =>
      Object.assign([], templateInfo.value?.template_properties)
    );

    const submit = () => {
      form.location_id = form.location_id?.key;
      form.post(route("objects.sinks.store"), {
        onSuccess: () => {
          store.dispatch("map/refreshMap");
          store.dispatch("objects/showSlide", { route: "objects.list" });
        },
      });
    };

    const onCancel = () => {
      store.dispatch("objects/showSlide", { route: "objects.list" });
    };

    return {
      templateInfo,
      templates,
      selectedTemplate,
      locations,
      form,
      open,
      properties,
      submit,
      onCancel,
    };
  },
};
</script>

<style>
</style>
