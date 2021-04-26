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

    <component
      class="z-30"
      v-bind="slideOverProps"
      :is="slideOverComponent"
      v-show="slideOverComponent"
      v-model="slideOver"
      @onRouteRequest="onRouteRequest"
    ></component>

    <objects-index
      :instances="instances"
      v-model="indexSlide"
      @onActionRequest="onActionRequest"
    ></objects-index>
  </app-layout>
</template>

<script>
import { computed, defineAsyncComponent, ref } from "vue";
import pluralize from "pluralize";

import AppLayout from "@/Layouts/AppLayout.vue";
import AmazingMap from "../../Components/Map/AmazingMap";
import SlideOver from "../../Components/NewLayout/SlideOver";
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
    const slideOverProps = ref(null);
    const slideOver = ref(true);
    const modalComponent = ref(null);
    const indexSlide = ref(false);
    const currentSlideOver = ref(null);

    const slideOverComponent = computed(() =>
      currentSlideOver.value
        ? defineAsyncComponent(() =>
            import(`@/Pages/${currentSlideOver.value}`)
          )
        : false
    );

    const onCreateRequest = async (req) => {
      if (slideOver.value) slideOver.value = false; // reset the current slideover
      if (indexSlide.value) indexSlide.value = false; // reset the current index slideover

      const res = await fetch(
        route(`objects.${pluralize.plural(req.type)}.create`)
      ).then((res) => {
        if (!res.ok) {
          const error = new Error(res.statusText);
          error.json = res.json();
          throw error;
        }

        return res.json();
      });
      console.log(res);

      slideOverProps.value = res.props;
      currentSlideOver.value = res.slideOver;

      slideOver.value = true;
    };

    const onActionRequest = (res) => {
      console.log("parent", res);
      if (slideOver.value) slideOver.value = false; // reset the current slideover
      if (indexSlide.value) indexSlide.value = false; // reset the current index slideover

      slideOverProps.value = res.props;
      console.log("GEOCFU", res.props);
      currentSlideOver.value = res.slideOver;

      slideOver.value = true;
    };

    const onRouteRequest = (e) => {
      console.log("OBJECTS onRouteRequest", e);
    };

    return {
      slideOver,
      modalComponent,
      onCreateRequest,
      onActionRequest,
      indexSlide,
      slideOverComponent,
      slideOverProps,
      onRouteRequest,
    };
  },
};
</script>

<style>
</style>


