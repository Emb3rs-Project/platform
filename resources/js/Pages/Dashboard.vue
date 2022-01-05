<template>
  <SiteHead title="Dashboard" />
  <AppLayout>
    <AmazingMap preview />
    <div class="h-full fixed right-0 top-0 bot-0 z-10 shadow-md bg-gray-100">
      <div class="flex flex-col pt-10 w-16">
        <div class="my-4 text-center">
          <FireIcon class="h-11 w-11 rounded-full mx-auto text-red-600" />
          <span class="text-xs font-semibold">Sources</span>
        </div>
        <div class="my-4 text-center">
          <LightningBoltIcon class="h-11 w-11 rounded-full mx-auto text-green-600" />
          <span class="text-xs font-semibold">Sinks</span>
        </div>
        <div class="my-4 text-center">
          <LinkIcon class="h-11 w-11 rounded-full mx-auto text-blue-600" />
          <span class="text-xs font-semibold">Links</span>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SiteHead from "@/Components/SiteHead.vue";

import { FireIcon, LightningBoltIcon, LinkIcon } from "@heroicons/vue/solid";

export default {
  components: {
    AppLayout,
    SiteHead,
    AmazingMap,
    SlideOver,
    FireIcon,
    LightningBoltIcon,
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
