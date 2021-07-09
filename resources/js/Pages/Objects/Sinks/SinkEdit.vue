<template>

  <SiteHead title="Edit a Sink" />

  <SlideOver
    v-model="open"
    title="Edit Sink"
    subtitle="Below, you can edit the details that are associated to the currently selected Sink."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Sink Template -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
          Templates
        </label>
      </div>
      <div class="sm:col-span-2">
        <SelectMenu
          v-model="selectedTemplate"
          :options="templates"
        />
      </div>
    </div>

    <!-- Sink Location -->
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
        <SelectMenu
          v-model="selectedLocation"
          :options="locations"
          :disabled="selectedTemplate ? false : true"
        />
      </div>
    </div>

    <!-- Sink Name -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label
          for="project_name"
          class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
        >
        </label>
      </div>
      <div class="sm:col-span-2">
        <TextInput
          v-model="form.sink.data.name"
          :label="'Name'"
        />
      </div>
    </div>

    <!-- Sink Properties -->
    <div
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
          <TextInput
            v-model="form.sink.data[prop.property.symbolic_name]"
            :label="prop.property.name"
            :unit="prop.unit.symbol"
            :placeholder="prop.property.name"
            :required="prop.required"
          />
        </div>
        <div v-else-if="prop.property.inputType === 'select'">
          <SelectMenu
            v-model="form.sink.data[prop.property.symbolic_name]"
            :options="prop.property.data.options"
            :disabled="selectedTemplate ? false : true"
            :required="prop.required"
            :label="prop.property.name"
          />
        </div>
      </div>
    </div>

    <template #actions>
      <SecondaryOutlinedButton
        type="button"
        :disabled="form.processing"
        @click="onClose"
      >
        Cancel
      </SecondaryOutlinedButton>
      <PrimaryButton
        @click="submit"
        :disabled="form.processing"
      >
        Save
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { ref, watch, computed, onBeforeUpdate } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
// import { Inertia } from '@inertiajs/inertia'

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import { useStore } from "vuex";

export default {
  components: {
    AppLayout,
    SiteHead,
    SlideOver,
    SelectMenu,
    TextInput,
    PrimaryButton,
    SecondaryOutlinedButton,
  },

  props: {
    modelValue: {
      type: Boolean,
      default: false,
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

    const form = useForm({
      sink: {
        data: {},
      },
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

        if (templateInfo.value?.template_properties) {
          for (const prop of templateInfo.value?.template_properties) {
            form.sink.data[prop.property.symbolic_name] = prop.default_value
              ? prop.default_value
              : "";
          }
        }
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
        onSuccess: () => {
          store.dispatch("map/refreshMap");
          store.dispatch("objects/showSlide", { route: "objects.list" });
        },
      });
    };

    const onClose = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    const onLocationSelect = () => {};

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
      onLocationSelect,
      onClose,
    };
  },
};
</script>

<style>
</style>
