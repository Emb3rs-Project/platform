<template>
  <AppLayout>
    <AmazingMap ref="map" />
    <div class="h-full fixed right-0 top-0 bot-0 z-10 shadow-md bg-gray-100">
      <div class="flex flex-1 flex-col pt-10 items-center w-16">
        <div class="my-4 text-center">
          <SinkIcon class="text-green-700 mb-2" />
          <span class="text-xs">Sinks</span>
        </div>
        <div class="my-4 text-center">
          <SourceIcon class="text-red-700 mb-2" />
          <span class="text-xs">Sources</span>
        </div>
        <div class="my-4 text-center">
          <LinkIcon class="text-blue-700 mb-2" />
          <span class="text-xs">Links</span>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { onMounted, ref, watch } from "vue";

import useUniqueLocations from "@/Composables/useUniqueLocations";

import AppLayout from "@/Layouts/AppLayout.vue";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SinkIcon from "@/Components/Icons/SinkIcon.vue";
import SourceIcon from "@/Components/Icons/SourceIcon.vue";
import LinkIcon from "@/Components/Icons/LinkIcon.vue";

export default {
  components: {
    AppLayout,
    AmazingMap,
    SlideOver,
    SinkIcon,
    SourceIcon,
    LinkIcon,
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

    const locations = [...props.sources, ...props.sinks];

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
