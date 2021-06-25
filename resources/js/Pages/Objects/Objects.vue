<template>
  <app-layout>
    <!-- Menu Of Available Instances -->
    <div
      class="fixed right-8 top-6 z-10 p-4 rounded-full cursor-pointer bg-yellow-500 hover:animate-none"
      :class="{ 'animate-pulse': !slideOpen }"
      @click="toggleIndexComponent"
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
      ref="map"
    ></amazing-map>

    <component
      class="z-30"
      v-bind="slideProps"
      :is="slideComponent"
      v-show="slideComponent"
    ></component>
  </app-layout>
</template>

<script>
import { computed, defineAsyncComponent, ref, watch } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout.vue";
import AmazingMap from "@/Components/Map/AmazingMap";
import SlideOver from "@/Components/SlideOver";
import LinkIcon from "@/Components/Icons/LinkIcon";
import { Inertia } from "@inertiajs/inertia";

export default {
  components: {
    AppLayout,
    AmazingMap,
    SlideOver,
    LinkIcon,
  },

  props: {
    instances: {
      type: Array,
      default: [],
    },
  },

  setup(props) {
    const map = ref(null);
    const store = useStore();
    const slideProps = ref(null);
    const slideController = ref();
    const slideOpen = computed(() => store.getters["objects/slideOpen"]);

    const slideComponent = computed(() =>
      slideController.value
        ? defineAsyncComponent({
            loader: () => import(`@/Pages/${slideController.value}`),
            delay: 300,
          })
        : false
    );

    const currentSlideOverPath = computed(
      () => store.getters["objects/currentRoute"]
    );

    const currentSlidePathCheckSum = computed(
      () => store.getters["objects/routeCheckSum"]
    );

    watch(currentSlidePathCheckSum, async (_) => {
      const newPath = currentSlideOverPath.value;
      if (!newPath) return;
      let _route = "";

      if (newPath.includes("show") || newPath.includes("edit")) {
        let props = store.getters["objects/currentRouteProps"];
        _route = route(newPath, props);
      } else {
        _route = route(newPath);
      }

      const response = await fetch(_route).then((res) => {
        if (!res.ok) {
          const error = new Error(res.statusText);
          error.json = res.json();
          throw error;
        }

        return res.json();
      });

      slideProps.value = response.props;

      if (response.slideOver === slideController.value) {
        store.commit("objects/openSlide");
      } else {
        slideController.value = response.slideOver;
      }
    });

    const toggleIndexComponent = () =>
      store.dispatch("objects/showSlide", {
        route: "objects.list",
        props: null,
      });

    return {
      map,
      slideComponent,
      slideProps,
      toggleIndexComponent,
      slideOpen,
    };
  },
};
</script>

<style>
</style>


