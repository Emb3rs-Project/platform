<template>
  <app-layout>
    <!-- Menu Of Available Instances -->
    <div
      class="fixed right-8 top-6 z-10 p-4 rounded-full cursor-pointer bg-yellow-500 hover:animate-none"
      :class="{ 'animate-pulse': !indexSlide }"
      @click="indexSlide = !indexSlide"
    >
      <svg
        class="w-8 h-8 text-white hover:text-gray-100"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
        ></path>
      </svg>
    </div>

    <amazing-map
      :centerValue="[38.7181959, -9.1975417]"
      :instances="instances"
      @onCreateRequest="onCreateRequest"
    ></amazing-map>

    <Component
      class="z-20"
      v-bind="$props"
      :is="modalComponent"
      v-if="modalComponent"
      v-model="slideOver"
    />

    <objects-index
      :instances="instances"
      class="z-30"
      v-model="indexSlide"
    ></objects-index>
  </app-layout>
</template>

<script>
import { ref } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import AmazingMap from "../../Components/Map/AmazingMap";
import SlideOver from "../../Components/NewLayout/SlideOver";
import SinkCreate from "../../Pages/Objects/Sinks/SinkCreate";
import SourceCreate from "../../Pages/Objects/Sources/SourceCreate";
import ObjectsIndex from "./ObjectsIndex.vue";

export default {
  components: {
    AppLayout,
    AmazingMap,
    SlideOver,
    ObjectsIndex,
  },

  props: {
    instances: {
      type: Array,
      default: [],
    },
  },

  setup(props) {
    console.log(props);
    const slideOver = ref(true);
    const modalComponent = ref();

    const indexSlide = ref(false);

    // const onLoadComponent = () => (modalComponent.value = SinkCreate);

    const onCreateRequest = (req) => {
      if (modalComponent.value) slideOver.value = false; // reset the current slide over
      if (req.type === "sink") modalComponent.value = SinkCreate;
      if (req.type === "source") modalComponent.value = SourceCreate;
    };

    return {
      //   openMenu,
      slideOver,
      //   onLoadComponent,
      modalComponent,
      onCreateRequest,
      indexSlide,
    };
  },
};
</script>

<style>
</style>


