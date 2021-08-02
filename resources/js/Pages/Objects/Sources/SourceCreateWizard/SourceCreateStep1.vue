<template>
  <pre>{{ nextStepRequest }}</pre>
  <!-- Source Template -->
  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
    <div>
      <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
        Select a Template
      </label>
    </div>
    <div class="sm:col-span-2">
      <SelectMenu
        v-model="selectedTemplate"
        :options="templates"
        label="Template"
      />
    </div>
  </div>

  <!-- Source Location -->
  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
    <div>
      <label
        for="project_name"
        class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
      >
        Select a Location
      </label>
    </div>
    <div class="sm:col-span-2">
      <SelectMenu
        v-model="selectedLocation"
        :options="locations"
        :disabled="!selectedTemplate"
        label="Location"
      />
    </div>
  </div>

  <!-- Source Properties -->
  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    v-for="property in properties"
    :key="property"
  >
    <div class="col-span-3">
      <div v-if="property.property.inputType === 'text' || property.property.inputType === 'String'">
        <TextInput
          v-model="source.data[property.property.symbolic_name]"
          :unit="property.unit.symbol"
          :placeholder="property.property.name"
          :description="property.property.description"
          :required="property.required"
          :label="property.property.name"
        />
      </div>
      <div v-else-if="property.property.inputType === 'select'">
        <SelectMenu
          v-model="source.data[property.property.symbolic_name]"
          :options="property.property.data.options"
          :description="property.property.description"
          :required="property.required"
          :label="property.property.name"
        />
      </div>
      <div
        v-for="(error, key) in errors"
        :key="key"
      >
        <JetInputError
          v-show="key.includes(property.property.symbolic_name)"
          :message="error"
          class="mt-2"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";
import * as yup from "yup";

import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";

import JetInputError from "@/Jetstream/InputError.vue";

export default {
  components: {
    SelectMenu,
    TextInput,
    JetInputError,
  },

  props: {
    templates: {
      type: Array,
      required: true,
    },
    locations: {
      type: Array,
      required: true,
    },
    nextStepRequest: {
      type: Boolean,
      required: true,
    },
  },

  emits: ["completed"],

  setup(props, ctx) {
    const store = useStore();

    const validationSchema = {};

    // We deep copy the store data, so we manipulate it freely and commit our changes back, when we are ready
    const source = ref(JSON.parse(JSON.stringify(store.state.source.source)));

    const errors = ref({});

    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
        properties: t.template_properties,
      }))
    );
    const selectedTemplate = ref(
      store.state.source.template ?? templates.value[0] ?? {}
    );

    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
      }))
    );
    const selectedLocation = ref(store.state.source.location ?? {});
    const selectedMarker = computed(() => store.getters["map/selectedMarker"]);
    if (selectedMarker.value) {
      locations.value.unshift({
        key: selectedMarker.value,
        value: "Selected Marker",
      });

      if (!Object.keys(selectedLocation.value).length)
        selectedLocation.value = locations.value[0];
    }

    watch(
      selectedTemplate,
      () =>
        store.commit("source/setTemplate", {
          template: selectedTemplate.value,
        }),
      { immediate: true }
    );
    watch(
      selectedLocation,
      () =>
        store.commit("source/setLocation", {
          location: selectedLocation.value,
        }),
      { immediate: true }
    );

    watch(
      selectedTemplate,
      (selectedTemplate) => {
        if (!selectedTemplate.properties) return; // edge case
        if (!Object.keys(selectedTemplate.properties).length) return; // edge case

        if (!Object.keys(source.value.data).length) {
          console.log("STORE IS EMPTY SO WE FILL THE PROPS WITH PLACEHOLDERS");

          const properties = selectedTemplate.properties;

          let validationRules = "yup.";

          for (const property of properties) {
            const inputType = property.property.inputType;
            const dataType = property.property.dataType;

            if (property.property) {
              const placeholder = inputType === "select" ? {} : "";

              source.value.data[property.property.symbolic_name] =
                property.property.default_value ?? placeholder;

              if (property.required) validationRules.concat("required().");

              switch (dataType.toLowerCase()) {
                case "text":
                  validationRules.concat("string()");
                  break;
                case "string":
                  validationRules.concat("string()");
                  break;
                case "number":
                  validationRules.concat("number()");
                  break;
                case "float":
                  validationRules.concat("number()");
                  break;
                case "datetime":
                  validationRules.concat("date()");
                  break;
                default:
                  break;
              }
              validationSchema[property.property.symbolic_name] =
                validationRules;
            }

            console.log(validationSchema);
          }

          return;
        }

        console.log("STORE HAS PROPS WITH VALUES");
        console.log(source.value.data);
      },
      { immediate: true }
    );

    const commitSource = window._.debounce(
      () =>
        store.commit("source/setSourceData", {
          data: JSON.parse(JSON.stringify(source.value.data)),
        }),
      500
    );

    watch(source, (source) => commitSource(), {
      deep: true,
      immediate: true,
    });

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        const test = yup.string().required();

        const properties = selectedTemplate.value.properties;

        for (const property of properties) {
          console.log(property);
          const inputType = property.property.inputType;
          const dataType = property.property.dataType;

          const field = `source.data.${property.property.symbolic_name}`;

          if (inputType === "select") {
            if (
              property.required &&
              !Object.keys(source.data[property.property.symbolic_name]).length
            )
              errors.value[
                field
              ] = `The ${property.property.name.toLowerCase()} field is required.`;

            switch (dataType.toLowerCase()) {
              case "text":
                break;
              case "string":
                break;
              case "number":
                break;
              case "float":
                break;
              case "datetime":
                break;

              default:
                break;
            }
          } else {
          }
        }

        console.log("errors", errors.value);

        if (!Object.keys(errors.value).length) ctx.emit("completed");
      }
    );

    watch(errors, (errors) => console.log("errors", errors), { deep: true });

    const properties = computed(() => {
      const properties = [];

      Object.assign(properties, selectedTemplate.value.properties);

      properties.sort((a, b) =>
        a.order < b.order ? -1 : a.order > b.order ? 1 : 0
      );

      return properties;
    });

    return {
      source,
      errors,
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      properties,
    };
  },
};
</script>
