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
      />
    </div>
  </div>

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
        v-model="selectedLocation"
        :options="locations"
        :disabled="selectedTemplate ? false : true"
      ></select-menu>
    </div>
  </div>

  <div v-if="selectedTemplate">
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-for="property in selectedTemplate.props"
      :key="property"
    >
      <div>
        <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
          {{ property.property.name }}
        </label>
      </div>
      <div class="sm:col-span-2">
        <div v-if="property.property.inputType === 'text'">
          <text-input
            v-model="data[property.property.symbolic_name]"
            :unit="property.unit.symbol"
            :placeholder="property.property.name"
            :required="property.required"
          >
          </text-input>
        </div>
        <div v-else-if="property.property.inputType === 'select'">
          <select-menu
            v-model="data[property.property.symbolic_name]"
            :options="property.property.data.options"
            :required="property.required"
          >
          </select-menu>
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

export default {
  components: {
    SelectMenu,
    TextInput,
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
  },

  setup(props) {
    const store = useStore();

    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
        props: t.template_properties,
      }))
    );
    const sessionTemplate = computed(() => store.getters["sources/template"]);
    const selectedTemplate = ref(
      sessionTemplate.value ??
        templates.value.find((t) => t.key === props.instance.template_id)
    );

    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
      }))
    );
    const sessionLocation = computed(() => store.getters["sources/location"]);
    const selectedLocation = ref(
      sessionLocation.value ??
        locations.value.find((l) => l.key === props.instance.location_id)
    );

    const sessionData = computed(() => store.getters["sources/source"]);
    const data = ref(JSON.parse(JSON.stringify(sessionData.value)) ?? {});

    watch(
      selectedTemplate,
      (selectedTemplate) => {
        store.dispatch("sources/setTemplate", { template: selectedTemplate });

        if (!Object.keys(selectedTemplate.props).length === 0) return;

        for (const property of selectedTemplate.props) {
          console.log(property);
          data.value[property.property.symbolic_name] = property.default_value;
        }
      },
      { immediate: true, deep: true }
    );

    watch(
      selectedLocation,
      () =>
        store.commit("sources/setLocation", {
          location: selectedLocation.value,
        }),
      { immediate: true, deep: true }
    );

    watch(
      data,
      (data) => {
        store.dispatch("sources/setSource", {
          source: JSON.parse(JSON.stringify(data)),
        });
      },
      { immediate: true, deep: true }
    );

    return {
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      data,
    };
  },
};
</script>
