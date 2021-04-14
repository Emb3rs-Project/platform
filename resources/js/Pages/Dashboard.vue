<template>
  <app-layout>
    <template #content>
      <amazing-map :centerValue="[38.7181959, -9.1975417]"></amazing-map>
      <div class="h-full fixed right-0 top-0 bot-0 z-10 shadow-md bg-gray-100">
        <div class="flex flex-1 flex-col pt-10 items-center w-16">
          <div class="my-4 text-center">
            <sink-icon class="text-green-700 mb-2"></sink-icon>
            <span class="text-xs">Sinks</span>
          </div>
          <div class="my-4 text-center">
            <source-icon class="text-red-700 mb-2"></source-icon>
            <span class="text-xs">Sources</span>
          </div>
          <div class="my-4 text-center">
            <link-icon class="text-blue-700 mb-2"></link-icon>
            <span class="text-xs">Links</span>
          </div>
        </div>
      </div>
    </template>
  </app-layout>
</template>

<script>
import { ref } from "vue";

import useUniqueLocations from "@/Composables/useUniqueLocations";

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/NewLayout/SlideOver.vue";
import FilterDropdown from "@/Components/NewLayout/FilterDropdown.vue";
import SinkIcon from "../Components/Icons/SinkIcon.vue";
import SourceIcon from "../Components/Icons/SourceIcon.vue";
import LinkIcon from "../Components/Icons/LinkIcon.vue";
import AmazingMap from "../Components/Map/AmazingMap.vue";

export default {
  components: {
    AppLayout,
    SlideOver,
    FilterDropdown,
    SinkIcon,
    SourceIcon,
    LinkIcon,
    AmazingMap,
  },
  props: {
    users: {
      type: Array,
      required: true,
    },
    sources: {
      type: Array,
      required: true,
    },
    sinks: {
      type: Array,
      required: true,
    },
  },

  setup(props, context) {
    const map = ref(null);
    const markers = ref([]);

    const locations = props.sources.concat(props.sinks);

    const uniqueLocations = useUniqueLocations(locations);

    for (const _location of uniqueLocations.value) {
      markers.value.push(_location.data);
    }

    return {
      map,
      markers,
    };
  },
};
</script>

<style scoped>
</style>
