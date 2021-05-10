<template>
  <slide-over
    v-model="open"
    title="Sink Details"
    subtitle="Below, you can see the details that are associated to the currently selected Sink."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Sink ID -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    >
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          ID
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ instance.id }}
        </div>
      </div>
    </div>

    <!-- Sink Name -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    >
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          Name
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ instance.name }}
        </div>
      </div>
    </div>

    <!-- Sink Template -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    >
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          Template
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ instance.template.name }}
        </div>
      </div>
    </div>

    <!-- Sink Location Name -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-if="instance.location_id"
    >
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          Location's Name
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ instance.location.name }}
        </div>
      </div>
    </div>

    <!-- Sink Location Description -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-if="instance.location_id"
    >
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          Location's Description
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{
            instance.location.description
              ? instance.location.description
              : "Not available."
          }}
        </div>
      </div>
    </div>

    <template #actions>
      <secondary-outlined-button type="button" @click="open = false">
        Cancel
      </secondary-outlined-button>
      <primary-button
        @click="onRouteRequest('objects.sinks.edit', instance.id)"
      >
        Edit
      </primary-button>
    </template>
  </slide-over>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/NewLayout/SlideOver.vue";
import SelectMenu from "@/Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "@/Components/NewLayout/Forms/TextInput.vue";
import PrimaryButton from "@/Components/NewLayout/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/NewLayout/SecondaryOutlinedButton.vue";

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
    instance: {
      type: Object,
      required: true,
    },
  },

  setup(props, { emit }) {
    const store = useStore();

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const onRouteRequest = (route, props) => {
      store.dispatch("objects/goToSlideOver", { route, props });
    };

    return {
      open,
      onRouteRequest,
    };
  },
};
</script>

<style>
</style>
