<template>
  <SiteHead title="Dashboard" />
  <AppLayout>
    <AmazingMap preview/>
    <div class="h-full fixed right-0 top-0 bot-0 z-10 shadow-md bg-gray-100">
      <div class="flex flex-col pt-10 w-16">
        <div class="my-4 text-center">
          <button class="group" @click="active('Sources')">
            <FireIcon class="h-11 w-11 rounded-full mx-auto" :class="{'text-red-600': elements['Sources'], 'text-red-400 group-hover:text-red-600': !elements['Sources']}" />
            <span class="text-xs font-semibold" :class="{'text-gray-400': !elements['Sources']}">Sources</span>
          </button>
        </div>
        <div class="my-4 text-center">
          <button class="group" @click="active('Sinks')">
            <LightningBoltIcon class="h-11 w-11 rounded-full mx-auto" :class="{'text-green-600': elements['Sinks'], 'text-green-400 group-hover:text-green-600': !elements['Sinks']}" />
            <span class="text-xs font-semibold" :class="{'text-gray-400': !elements['Sinks']}">Sinks</span>
          </button>
        </div>
        <div class="my-4 text-center">
          <button class="group" @click="active('Links')">
            <LinkIcon class="h-11 w-11 rounded-full mx-auto text-blue-600" :class="{'text-blue-600': elements['Links'], 'text-blue-400 group-hover:text-blue-600': !elements['Links']}" />
            <span class="text-xs font-semibold" :class="{'text-gray-400': !elements['Links']}">Links</span>
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { useStore } from "vuex";
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
  data() {
    return {
      elements: {Sinks: true, Links: true, Sources: true},
      store: useStore()
    }
  },
  methods: {
    active(element) {
      this.elements[element] = !this.elements[element];
      localStorage.setItem(`objects${element}`, this.elements[element]);
      this.store.dispatch("map/refreshMap");
    }
  },
  mounted() {
    this.elements = {
      Sinks: localStorage.getItem('objectsSinks') == 'false' ? false : true,
      Links: localStorage.getItem('objectsLinks') == 'false' ? false : true,
      Sources: localStorage.getItem('objectsSources') == 'false' ? false : true,
    }
    this.store.dispatch("map/refreshMap");
  }
};
</script>
