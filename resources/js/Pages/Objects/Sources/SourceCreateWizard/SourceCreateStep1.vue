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
    v-for="property in properties"
    :key="property"
  >
    <div>
      <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
        {{ property.property.description }}
      </label>
    </div>
    <div class="sm:col-span-2">
      <div v-if="property.property.inputType === 'text'">
        <TextInput
          v-model="data[property.property.symbolic_name]"
          :unit="property.unit.symbol"
          :placeholder="property.property.name"
          :required="property.required"
          :label="property.property.name"
        >
        </TextInput>
      </div>
      <div v-else-if="property.property.inputType === 'select'">
        <SelectMenu
          v-model="data[property.property.symbolic_name]"
          :options="property.property.data.options"
          :required="property.required"
          :label="property.property.name"
        >
        </SelectMenu>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, onBeforeUnmount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";

import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";

export default {
  components: {
    SelectMenu,
    TextInput,
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
  },

  emits: {
    status: (payload) => {
      if (
        payload === "current" ||
        payload === "upcoming" ||
        payload === "complete"
      )
        return true;

      return false;
    },
  },

  setup(props, ctx) {
    onMounted(() => console.log("MOUNTED"));
    const store = useStore();

    const data = ref(store.getters["sources/source"]);

    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
        properties: t.template_properties,
      }))
    );

    const selectedTemplate = ref(
      store.getters["sources/template"] ?? templates.value[0] ?? {}
    );

    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
      }))
    );
    const selectedLocation = ref(store.getters["sources/location"] ?? {});

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
      selectedLocation,
      () =>
        store.commit("sources/setLocation", {
          location: selectedLocation.value,
        }),
      { immediate: true }
    );

    watch(
      data,
      (data) => {
        store.dispatch("sources/setSource", {
          source: JSON.parse(JSON.stringify(data)),
        });
      },
      { deep: true }
    );

    watch(
      selectedTemplate,
      (selectedTemplate, previous) => {
        if (!selectedTemplate.properties) return; // edge case

        if (!Object.keys(selectedTemplate.properties).length === 0) return; // edge case

        if (!previous) data.value = {};

        store.dispatch("sources/setTemplate", { template: selectedTemplate });

        for (const property of selectedTemplate.properties) {
          const prop = property.property ?? null;

          if (prop) {
            const placeholder = prop.inputType === "select" ? {} : "";

            data.value[property.symbolic_name] =
              property.default_value ?? placeholder;
          }
        }
      },
      { immediate: true }
    );

    const properties = computed(() => {
      const properties = [];

      Object.assign(properties, selectedTemplate.value.properties);

      properties.sort((a, b) =>
        a.order < b.order ? -1 : a.order > b.order ? 1 : 0
      );

      return properties;
    });

    onBeforeUnmount(() => console.log(store.state.sources));
    return {
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      data,
      properties,
    };
  },
};
</script>
