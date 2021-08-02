<template>
  <SiteHead title="Objects" />

  <AppLayout>
    <!-- Menu Of Available Instances -->
    <button
      type="button"
      class="fixed right-8 top-24 sm:top-6 z-10 p-4 rounded-full bg-yellow-500"
      @click="toggleIndexComponent"
    >
      <TemplateIcon class="h-8 w-auto text-white hover:text-gray-100" />
    </button>

    <AmazingMap ref="map" />

    <component
      v-bind="slideProps"
      :is="slideComponent"
    />
  </AppLayout>
</template>

<script>
import {
  computed,
  defineAsyncComponent,
  onBeforeUnmount,
  ref,
  watch,
} from "vue";
import { useStore } from "vuex";

import { TemplateIcon } from "@heroicons/vue/outline";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import AmazingMap from "@/Components/Map/AmazingMap";
import SlideOver from "@/Components/SlideOver";
import LinkIcon from "@/Components/Icons/LinkIcon";

export default {
  components: {
    SiteHead,
    AppLayout,
    TemplateIcon,
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

    const slideComponent = computed(() => {
      return slideController.value
        ? defineAsyncComponent({
            loader: () => import(`@/Pages/${slideController.value}`),
            delay: 300,
          })
        : false;
    });

    const currentSlideOverPath = computed(
      () => store.getters["objects/currentRoute"]
    );

    const currentSlidePathCheckSum = computed(
      () => store.getters["objects/routeCheckSum"]
    );

    watch(currentSlidePathCheckSum, async () => {
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

    onBeforeUnmount(() => store.commit("objects/closeSlide"));

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


