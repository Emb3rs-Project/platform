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
    </div>
  </div>
</template>

<script>
import {
  computed,
  onBeforeUnmount,
  onMounted,
  ref,
  watch,
  watchEffect,
  reactive,
} from "vue";
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
    nextStepRequest: {
      type: Boolean,
      required: true,
    },
  },

  emits: ["completed"],

  setup(props, ctx) {
    const store = useStore();

    const errors = ref({});

    // We deep copy the store data, so we manipulate it freely and commit our changes when we are ready
    const source = ref(JSON.parse(JSON.stringify(store.state.source.source)));
    console.log("initial source", source);

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

          for (const property of properties) {
            const prop = property.property ?? null;

            if (prop) {
              const placeholder = prop.inputType === "select" ? {} : "";

              source.value.data[prop.symbolic_name] =
                prop.default_value ?? placeholder;
              console.log(prop.symbolic_name);
            }
          }

          if (Object.keys(source.value.data).length) {
            store.commit("source/setSourceData", {
              data: JSON.parse(JSON.stringify(source.value.data)),
            });
          }

          return;
        }

        console.log("STORE HAS PROPS WITH PLACEHOLDERS");
      },
      { immediate: true }
    );

    // const stopWatcher = store.watch(
    //   (state) => state.source,
    //   (data) => {
    //     console.log(data);
    //   },
    //   { immediate: true, deep: true }
    // );

    watch(
      source,
      (source) => {
        store.commit("source/setSourceData", {
          data: JSON.parse(JSON.stringify(source.data)),
        });

        const template = templates.value.find(
          (t) => t.key === selectedTemplate.value.key
        );

        for (const property of template.properties) {
          console.log(property);
          const type = property.property.inputType;

          if (type === "select") {
            if (
              property.required &&
              !Object.keys(source.data[property.property.symbolic_name]).length
            ) {
              // TODO: assign the errors
            }
          } else {
          }
        }

        // TODO: validate the values before allowing to proceed to the next step
        // ctx.emit("completed");
      },
      { deep: true }
    );

    const properties = computed(() => {
      const properties = [];

      Object.assign(properties, selectedTemplate.value.properties);

      properties.sort((a, b) =>
        a.order < b.order ? -1 : a.order > b.order ? 1 : 0
      );

      return properties;
    });

    // onBeforeUnmount(() => stopWatcher());

    return {
      source,
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      properties,
    };
  },
};
</script>
