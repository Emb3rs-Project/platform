<template>
  <!-- Source Information -->
  <div
    class="
      space-y-1
      px-4
      sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
    "
  >
    <PropertyDisclosure defaultOpen title="Information">
      <div class="my-4">
        <SelectMenu
          v-model="selectedTemplate"
          :options="templates"
          label="Template"
        />
      </div>
      <div class="my-4">
        <SelectMenu
          v-model="selectedLocation"
          :options="locations"
          label="Location"
          :disabled="selectedTemplate ? false : true"
        />
      </div>
    </PropertyDisclosure>
  </div>

  <!-- Source Properties-->
  <div
    v-if="properties.length"
    class="
      space-y-1
      px-4
      sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
    "
  >
    <PropertyDisclosure defaultOpen title="Properties">
      <div
        class="my-6"
        v-for="(property, propertyIdx) in properties"
        :key="propertyIdx"
      >
        <div v-if="property.property.inputType === 'text'">
          <TextInput
            v-model="source.data[property.property.symbolic_name]"
            :unit="property.unit.symbol"
            :description="property.property.description"
            :label="property.property.name"
            :placeholder="property.property.name"
            :required="property.required"
          />
        </div>
        <div v-else-if="property.property.inputType === 'select'">
          <SelectMenu
            v-model="source.data[property.property.symbolic_name]"
            :options="property.property.data.options"
            :description="property.property.description"
            :disabled="selectedTemplate ? false : true"
            :required="property.required"
            :label="property.property.name"
          />
        </div>
        <div v-for="(error, key) in errors" :key="key">
          <div v-if="property.property.symbolic_name === key">
            <div v-for="(e, eIdx) in error" :key="eIdx">
              <JetInputError :message="e" class="mt-2" />
            </div>
          </div>
        </div>
      </div>
    </PropertyDisclosure>
  </div>
  <div v-else>
    <div
      class="
        space-y-1
        px-4
        sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
      "
    >
      <div class="col-span-3 text-center">
        <p class="block font-bold text-2xl text-gray-200 p-4">
          No assigned properties.
        </p>
      </div>
    </div>
  </div>

  <!-- Source Advanced Properties -->
  <div
    v-if="advancedProperties.length"
    class="
      space-y-1
      px-4
      sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
    "
  >
    <PropertyDisclosure title="Advanced Properties">
      <div>
        <fieldset class="space-y-5">
          <legend class="sr-only">Advanced Properties Enable</legend>
          <div class="relative flex items-start">
            <div class="flex items-center h-5">
              <input
                id="advancedProperties"
                aria-describedby="advancedProperties-description"
                name="advancedProperties"
                type="checkbox"
                class="
                  focus:ring-indigo-500
                  h-4
                  w-4
                  text-blue-600
                  border-gray-300
                  rounded
                "
                v-model="withAdvancedProperties"
              />
            </div>
            <div class="ml-3 text-sm">
              <label for="advancedProperties" class="font-medium text-gray-700">
                Enable advanced properties
              </label>
            </div>
          </div>
        </fieldset>
      </div>
      <div
        class="my-6"
        v-for="(advancedProperty, advancedPropertyIdx) in advancedProperties"
        :key="advancedPropertyIdx"
      >
        <div v-if="advancedProperty.property.inputType === 'text'">
          <TextInput
            v-model="source.data[advancedProperty.property.symbolic_name]"
            :unit="advancedProperty.unit.symbol"
            :description="advancedProperty.property.description"
            :label="advancedProperty.property.name"
            :placeholder="advancedProperty.property.name"
            :required="advancedProperty.required"
            :disabled="!withAdvancedProperties"
          />
        </div>
        <div v-else-if="advancedProperty.property.inputType === 'select'">
          <SelectMenu
            v-model="source.data[advancedProperty.property.symbolic_name]"
            :options="advancedProperty.property.data.options"
            :description="advancedProperty.property.description"
            :required="advancedProperty.required"
            :label="advancedProperty.property.name"
            :disabled="!withAdvancedProperties"
          />
        </div>
        <div v-for="(error, key) in errors" :key="key">
          <div v-if="advancedProperty.property.symbolic_name === key">
            <div v-for="(e, eIdx) in error" :key="eIdx">
              <JetInputError :message="e" class="mt-2" />
            </div>
          </div>
        </div>
      </div>
    </PropertyDisclosure>
  </div>
</template>

<script>
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";

import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "@/Jetstream/InputError.vue";

import { sortProperties, validateProperies } from "@/Utils/helpers";

export default {
  components: {
    PropertyDisclosure,
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
    const storeTemplate = computed(() => store.getters["source/template"]);

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
      storeTemplate.value ?? templates.value.find((t) => t.key === props.instance.template_id)
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

    const properties = computed(() => {
      if (!selectedTemplate.value.properties.length) return [];

      return sortProperties(
        window._.cloneDeep(
          selectedTemplate.value.properties.filter((p) => !p.advanced)
        )
      );
    });

    const withAdvancedProperties = computed({
      get: () => store.getters["source/withAdvancedProperties"],
      set: (value) =>
        store.commit("source/setAdvancedPropertiesOption", {
          withAdvancedProperties: value,
        }),
    });

    const advancedProperties = computed(() => {
      if (!selectedTemplate.value.properties.length) return [];

      return sortProperties(
        window._.cloneDeep(
          selectedTemplate.value.properties.filter((p) => p.advanced)
        )
      );
    });

    const allProperties = computed(() => [
      ...properties.value,
      ...advancedProperties.value,
    ]);

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
        if (selectedTemplate != storeTemplate.value) {
          store.commit("source/setTemplate", {
            template: selectedTemplate,
          });

          if (!selectedTemplate.properties.length) source.value.data = {};

          for (const property of allProperties.value) {
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
        }
      },
      { immediate: true, deep: true }
    );

    const userSelectedProperties = computed(() =>
      selectedTemplate.value.properties.filter((p) => {
        if (p.advanced && !withAdvancedProperties.value) return false;

        return true;
      })
    );

    // watch(
    //   withAdvancedProperties,
    //   (enabled) => {
    //     if (!enabled) {
    //       for (const property in source.value.data) {
    //         const found = advancedProperties.value.find(
    //           (p) => p.property.symbolic_name === property
    //         );

    //         if (found) delete source.value.data[property];
    //       }
    //     } else {
    //       for (const property of advancedProperties.value) {
    //         const inputType = property.property.inputType;

    //         if (property.property) {
    //           const placeholder = inputType === "select" ? {} : "";

    //           const key = property.property.symbolic_name;

    //           source.value.data[key] =
    //             property.property.default_value ?? placeholder;
    //         }
    //       }
    //     }
    //   },
    //   { deep: true }
    // );

    const commitSource = window._.debounce(
      (source) =>
        store.commit("source/setSourceData", {
          data: window._.cloneDeep(source.data),
        }),
      500
    );

    watch(source, (source) => commitSource(source), {
      deep: true,
      immediate: true,
    });

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        errors.value = validateProperies(
          source.value,
          userSelectedProperties.value,
          selectedTemplate.value
        );

        for (const property in source.value.data) {
          const found = userSelectedProperties.value.find(
            (p) => p.property.symbolic_name === property
          );

          if (found) continue;

          delete source.value.data[property];
        }

        if (!Object.keys(errors.value).length) ctx.emit("completed");
        else ctx.emit("incompleted");
      }
    );

    return {
      source,
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      withAdvancedProperties,
      userSelectedProperties,
      properties,
      advancedProperties,
      errors,
    };
  },
};
</script>
