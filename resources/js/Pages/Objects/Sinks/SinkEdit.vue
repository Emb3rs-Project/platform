<template>
  <slide-over
    v-model="open"
    title="Edit Sink"
    subtitle="Below, you can edit the details that are associated to the currently selected Sink."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Sink Template -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    >
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
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    >
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

    <!-- Sink Name -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    >
      <div>
        <label
          for="project_name"
          class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
        >
        </label>
      </div>
      <div class="sm:col-span-2">
        <text-input v-model="form.sink.data.name" :label="'Name'"> </text-input>
      </div>
    </div>

    <!-- Sink Properties -->
    <!-- <div
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
            :label="prop.property.name"
            :unit="prop.unit.symbol"
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
      </div>
      <pre>{{ prop }}</pre>
    </div> -->

    <template #actions>
      <secondary-outlined-button
        type="button"
        :disabled="form.processing"
        @click="onClose"
      >
        Cancel
      </secondary-outlined-button>
      <primary-button @click="submit()" :disabled="form.processing">
        Save
      </primary-button>
    </template>
  </slide-over>
</template>

<script>
import { ref, watch, computed, onBeforeUpdate } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
// import { Inertia } from '@inertiajs/inertia'

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/NewLayout/SlideOver.vue";
import SelectMenu from "@/Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "@/Components/NewLayout/Forms/TextInput.vue";
import PrimaryButton from "@/Components/NewLayout/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/NewLayout/SecondaryOutlinedButton.vue";
import { useStore } from "vuex";

export default {
  components: {
    AppLayout,
    SlideOver,
    SelectMenu,
    TextInput,
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
    equipments: {
      type: Array,
      default: [],
    },
    locations: {
      type: Array,
      default: [],
    },
    instance: {
      type: Object,
      required: true,
    },
  },

  setup(props, { emit }) {
    const store = useStore();
    onBeforeUpdate(() => {
      console.log("onBeforeUpdate");
    });

    const form = useForm({
      sink: {
        data: {},
      },
      //   equipments: [],
      template_id: null,
      location_id: null,
    });

    const templateInfo = ref(null);

    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
      }))
    );
    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
      }))
    );

    const selectedTemplate = ref(
      templates.value.find((t) => t.key === props.instance.template.id)
    );

    const selectedLocation = ref(
      locations.value.find((l) => l.key === props.instance.location.id)
    );

    watch(
      selectedTemplate,
      (selectedTemplate) => {
        templateInfo.value = props.templates.find(
          (t) => t.id === selectedTemplate.key
        );
        form.template_id = selectedTemplate.key;
        form.sink.data.name = props.instance.name;
        console.log(props.instance.name);

        // if (templateInfo.value?.template_properties) {
        //   for (const prop of templateInfo.value?.template_properties) {
        //     form.sink.data[prop.property.symbolic_name] = prop.default_value
        //       ? prop.default_value
        //       : "";
        //   }
        // }
      },
      { immediate: true, deep: true }
    );

    watch(
      selectedLocation,
      (selectedLocation) => {
        form.location_id = selectedLocation.key;
      },
      { immediate: true, deep: true }
    );

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const properties = computed(() =>
      Object.assign([], templateInfo.value?.template_properties)
    );

    const submit = () => {
      form.patch(route("objects.sinks.update", props.instance.id), {
        onError: (e) => console.log(e),
      });
    };

    return {
      templateInfo,
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      form,
      open,
      properties,
      submit,
      onClose: () =>
        store.dispatch("objects/showSlide", { route: "objects.list" }),
    };
  },

  methods: {
    onLocationSelect(locId) {},
  },
};
</script>

<style>
</style>
