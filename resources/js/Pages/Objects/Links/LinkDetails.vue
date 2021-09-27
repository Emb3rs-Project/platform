<template>
  <SiteHead title="Link Details" />

  <SlideOver
    title="Link Details"
    subtitle="Below, you can see the details that are associated to the currently selected Sink."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Link ID -->
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

    <!-- Link Name -->
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

    <!-- Link Description -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          Description
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ instance.description ?? "Not defined." }}
        </div>
      </div>
    </div>

    <!-- Link Segments -->
    <div
      v-if="Object.keys(instance.geo_segments).length"
      class="divide-y"
    >
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
        <div class="block text-base font-medium text-gray-900 sm:pt-1">
          <p>Segments</p>
        </div>
      </div>
      <div
        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6 sm:py-5"
        v-for="(segment, segmentIdx) in instance.geo_segments"
        :key="segmentIdx"
      >
        <div>
          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
            From
          </label>
          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
            To
          </label>
        </div>
        <div class="col-span-2">
          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
            {{ segment.data.from }}
          </div>
          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
            {{ segment.data.to }}
          </div>
        </div>
        <div class="flex place-content-center">
          <SecondaryButton
            variant="location"
            type="button"
            @click="onGoToLocation([segment.data.from, segment.data.to])"
          >
            View Location
          </SecondaryButton>
        </div>
      </div>
    </div>
    <div v-else>
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 place-content-center justify-items-center">
        <div class="col-span-3">
          <p class="block font-bold text-2xl text-gray-200 p-4">
            No assigned segments.
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
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

export default {
  components: {
    AppLayout,
    SiteHead,
    SlideOver,
    SelectMenu,
    TextInput,
    SecondaryButton,
    PrimaryButton,
    SecondaryOutlinedButton,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();

    const onGoToLocation = (loc) =>
      store.dispatch("map/centerAt", {
        marker: {
          type: "point",
          data: {
            center: loc,
          },
        },
      });

    const onRouteRequest = (route, properties) => {
      store.dispatch("objects/showSlide", { route, properties });
    };

    const onClose = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      onGoToLocation,
      onRouteRequest,
      onClose,
    };
  },
};
</script>
