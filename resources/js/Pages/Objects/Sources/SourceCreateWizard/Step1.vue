<template>
  <!-- Source Template -->
  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
  >
    <div>
      <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
        Select a Template
      </label>
    </div>
    <div class="sm:col-span-2">
      <select-menu
        v-model="selectedTemplate"
        :options="templates"
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
          <pre>{{ data[property.property.symbolic_name] }}</pre>
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

import SelectMenu from "../../../../Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "../../../../Components/NewLayout/Forms/TextInput.vue";

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
  },

  setup(props) {
    const store = useStore();
    const selectedTemplate = ref(store.getters["sources/template"] ?? null);
    const data = ref(store.getters["sources/source"]);

    watch(
      data,
      (data) => {
        store.dispatch("sources/addSource", {
          source: JSON.parse(JSON.stringify(data)),
        });
      },
      { deep: true }
    );

    watch(selectedTemplate, (selectedTemplate) => {
      data.value = {};

      store.dispatch("sources/addTemplate", { template: selectedTemplate });

      if (!Object.keys(selectedTemplate.props).length === 0) return;

      for (const property of selectedTemplate.props) {
        data.value[property.property.symbolic_name] = property.default_value;
      }
    });

    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
        props: t.template_properties,
      }))
    );

    return {
      templates,
      selectedTemplate,
      data,
    };
  },
};
</script>
