<template>
  <!-- Source ID -->
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

  <!-- Source Name -->
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

  <!-- Source Template -->
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

  <!-- Source Location Name -->
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
    instance: {
      type: Object,
      required: true,
    },
  },

  setup() {
    return {};
  },
};
</script>

<style>
</style>
