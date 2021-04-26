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
      <!-- <select-menu
        v-model="selectedTemplate"
        :options="templates"
      ></select-menu> -->
    </div>
  </div>
</template>

<script>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

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

  emits: ["completed"],

  setup(props) {
    const form = useForm({
      source: {
        data: {},
      },
    });

    watch(
      form,
      (form) => {
        console.log(form);
        // the component can return true for completed or false otherwise
      },
      { deep: true }
    );

    const templates = props.templates.map((t) => ({
      key: t.id,
      value: t.name,
      props: t.template_properties,
    }));
    const selectedTemplate = ref(templates.length ? templates[0] : null);

    return {
      steps,
      form,
      templates,
      selectedTemplate,
    };
  },
};
</script>

<style>
</style>


