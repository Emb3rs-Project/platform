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
          v-model="form.source.data[property.property.symbolic_name]"
          :unit="property.unit.symbol"
          :placeholder="property.property.name"
          :required="property.required"
        >
        </text-input>
      </div>
      <div v-else-if="property.property.inputType === 'select'">
        <select-menu
          v-model="form.source.data[property.property.symbolic_name]"
          :options="property.property.data.options"
          :disabled="selectedTemplate ? false : true"
          :required="property.required"
        >
        </select-menu>
      </div>
    </div>
  </div>
</template>

<script>
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import SelectMenu from "../../../../Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "../../../../Components/NewLayout/Forms/TextInput.vue";

export default {
  components: {
    SelectMenu,
    TextInput,
  },

  props: {
    // modelValue: {
    //   type: Object,
    //   required: true,
    // },
    objects: {
      type: Array,
      required: true,
    },
  },

  emits: ["completed"],

  setup(props, { emit }) {
    const form = useForm({
      source: {
        data: {},
      },
    });

    watch(
      form,
      (form) => {
        console.log(form);
        emit("completed", true);
        // the component can return true for completed or false otherwise
      },
      { deep: true }
    );

    const templates = props.objects.map((t) => ({
      key: t.id,
      value: t.name,
      props: t.template_properties,
    }));
    const selectedTemplate = ref(templates.length ? templates[0] : null);

    return {
      form,
      templates,
      selectedTemplate,
    };
  },
};
</script>
