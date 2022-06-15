<template>
  <SiteHead title="Objects" />
  <AppLayout>
    <button
      type="button"
      class="fixed right-8 top-20 lg:top-4 z-10 inline-flex items-center p-2 border-2 border-gray-400 rounded-full shadow-sm bg-gray-50 hover:bg-gray-100"
      @click="toggleIndexComponent"
    >
      <TemplateIcon
        class="h-8 w-auto text-yellow-500"
        aria-hidden="true"
      />
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

import { TemplateIcon } from "@heroicons/vue/solid";

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

  setup() {
    const store = useStore();

    const map = ref(null);

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
          store.commit("objects/setNotify", {
              title: 'Error',
              text: res.statusText,
              type: 'danger'
          });
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

    onBeforeUnmount(() => store.dispatch("objects/resetSlide"));

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
