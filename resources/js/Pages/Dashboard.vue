<template>
  <SiteHead title="Dashboard" />
  <AppLayout>
    <AmazingMap preview />
    <div class="h-full fixed right-0 top-0 bot-0 z-10 shadow-md bg-gray-100">
      <div class="flex flex-1 flex-col pt-10 items-center w-16">
        <div class="my-4 text-center">
          <SourceIcon class="text-red-700 mb-2" />
          <span class="text-xs">Sources</span>
        </div>
        <div class="my-4 text-center">
          <SinkIcon class="text-green-700 mb-2" />
          <span class="text-xs">Sinks</span>
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
import AppLayout from "@/Layouts/AppLayout.vue";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SinkIcon from "@/Components/Icons/SinkIcon.vue";
import SourceIcon from "@/Components/Icons/SourceIcon.vue";
import LinkIcon from "@/Components/Icons/LinkIcon.vue";
import SiteHead from "@/Components/SiteHead.vue";

export default {
  components: {
    AppLayout,
    SiteHead,
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
};
</script>
