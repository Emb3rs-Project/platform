<template>
  <SiteHead title="Sink Details" />

  <SlideOver
    title="Sink Details"
    subtitle="Below, you can see the details that are associated to the currently selected Sink."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Sink ID -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
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
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
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
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
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
          {{ instance.location.description ?? "Not available."}}
        </div>
      </div>
    </div>

    <!-- Sink Properties -->
    <div
      v-if="Object.keys(instance.values).length"
      class="divide-y"
    >
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
        <div class="block text-base font-medium text-gray-900 sm:pt-1">
          <p>Properties</p>
        </div>
      </div>
      <div
        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
        v-for="(property, propertyIdx) in properties"
        :key="propertyIdx"
      >
        <div>
          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
            {{property.property.name}}
          </label>
        </div>
        <div class="sm:col-span-2">
          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
            {{ instance.values[property.property.symbolic_name] ?? 'Not defined.' }}
          </div>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
        <div class="col-span-3 text-center">
          <p class="block font-bold text-2xl text-gray-200 mt-12">
            No assigned properties.
          </p>
        </div>
      </div>
    </div>

    <template #actions>
      <SecondaryOutlinedButton
        type="button"
        @click="onClose()"
      >
        Cancel
      </SecondaryOutlinedButton>
      <PrimaryButton @click="onRouteRequest('objects.sinks.edit', instance.id)">
        Edit
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

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
    instance: {
      type: Object,
      required: true,
    },
    templateProperties: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();

    const properties = computed(() => {
      const properties = [];

      Object.assign(properties, props.templateProperties);

      properties.sort((a, b) =>
        a.order < b.order ? -1 : a.order > b.order ? 1 : 0
      );

      return properties;
    });

    const onRouteRequest = (route, props) => {
      store.dispatch("objects/showSlide", { route, props });
    };

    const onClose = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      properties,
      onRouteRequest,
      onClose,
    };
  },
};
</script>
