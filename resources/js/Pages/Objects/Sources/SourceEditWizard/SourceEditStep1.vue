<template>
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
    v-for="property in selectedTemplate.properties"
    :key="property"
  >
    <div class="col-span-3">
      <div v-if="property.property.inputType === 'text' || property.property.inputType === 'String'">
        <TextInput
          v-model="source.data[property.property.symbolic_name]"
          :unit="property.unit.symbol"
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
        <div
          v-for="subError in error"
          :key="subError"
        >
          <JetInputError
            v-if="key.includes(property.property.symbolic_name)"
            :message="subError"
            class="mt-2"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";

import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "@/Jetstream/InputError.vue";

import { validateProperies } from "../helpers/validate-properties";
import { sortProperties } from "../helpers/sort-properties";

export default {
  components: {
    SelectMenu,
    TextInput,
    JetInputError,
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
    nextStepRequest: {
      type: Boolean,
      required: true,
    },
  },

  emits: ["completed", "incompleted"],

  setup(props, ctx) {
    const store = useStore();

    const storeSource = computed(() => store.getters["source/source"]);

    // We deep copy the store data, so we manipulate it freely and commit our changes back, when we are ready
    const source = ref(window._.cloneDeep(storeSource.value));

    const errors = ref({});

    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
        properties: sortProperties(t.template_properties),
      }))
    );
    const selectedTemplate = ref(
      templates.value.find(
        (template) => template.key === props.instance.template_id
      )
    );

    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
      }))
    );
    const selectedLocation = ref(
      locations.value.find(
        (location) => location.key === props.instance.location_id
      )
    );

    watch(
      selectedTemplate,
      () =>
        store.commit("source/setTemplate", {
          template: selectedTemplate.value,
        }),
      { immediate: true, deep: true }
    );
    watch(
      selectedLocation,
      () =>
        store.commit("source/setLocation", {
          location: selectedLocation.value,
        }),
      { immediate: true, deep: true }
    );

    watch(
      selectedTemplate,
      (selectedTemplate) => {
        if (Object.keys(source.value.data).length) return;

        if (!selectedTemplate.properties.length) source.value.data = {};

        for (const property of selectedTemplate.properties) {
          const prop = property.property;

          const value = props.instance.values.properties[prop.symbolic_name];

          if (value) {
            if (prop.inputType === "select") {
              source.value.data[prop.symbolic_name] = prop.data.options.find(
                (o) => o.key === value
              );
            } else {
              source.value.data[prop.symbolic_name] = value;
            }
          } else {
            const placeholder = prop.inputType === "select" ? {} : "";

            source.value.data[prop.symbolic_name] = placeholder;
          }
        }
      },
      { immediate: true, deep: true }
    );

    const commitSource = window._.debounce(
      () =>
        store.commit("source/setSourceData", {
          data: window._.cloneDeep(source.value.data),
        }),
      500
    );

    watch(source, () => commitSource(), {
      deep: true,
      immediate: true,
    });

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        errors.value = {};

        const properties = selectedTemplate.value.properties;

        validateProperies(source.value, properties, errors.value);

        if (!Object.keys(errors.value).length) ctx.emit("completed");
        else ctx.emit("incompleted");
      }
    );

    return {
      source,
      errors,
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
    };
  },
};
</script>
