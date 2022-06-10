<template>
  <div class="absolute inset-y-0 right-0 max-w-full flex overflow-hidden">
    <transition
      enter-active-class="transform transition ease-in-out duration-300 sm:duration-300"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transform transition ease-in-out duration-300 sm:duration-300"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div
        v-if="open"
        class="flex flex-col justify-between z-20 w-screen max-w-2xl bg-gray-50 divide-y divide-gray-200 opacity-80 hover:opacity-100"
      >
        <!-- Header -->
        <div :class="[styleClasses.background, 'py-6 px-4 sm:px-6']">
          <div class="flex items-center justify-between">
            <div class="flex items-start justify-center gap-2">
              <h2 :class="[styleClasses.title.text, 'text-lg font-medium']">
                {{ title }}
              </h2>
            </div>

            <div class="ml-3 h-7 flex items-center">
              <button
                v-if="!alwaysOpen"
                type="button"
                :class="[styleClasses.dismissIcon.text, styleClasses.dismissIcon.ring,'rounded-md focus:outline-none focus:ring-2']"
                @click="onClose"
              >
                <span class="sr-only">Close panel</span>
                <svg
                  class="h-6 w-6"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                  aria-hidden="true"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
          <div class="mt-1">
            <p :class="[styleClasses.subtitle.text, 'text-sm']">
              {{ subtitle }}
            </p>
          </div>
        </div>

        <div>
          <slot name="stickyTop"></slot>
        </div>

        <div class="overflow-y-auto h-full py-6 space-y-6 sm:py-0 sm:space-y-0 sm:divide-y sm:divide-gray-200">
          <slot></slot>
        </div>

        <div>
          <slot name="stickyBottom"></slot>
        </div>

        <div class="shrink-0 px-4 py-4 flex justify-end gap-5">
          <slot name="actions"></slot>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { onMounted, onUnmounted, computed } from "vue";
import { useStore } from "vuex";

export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    subtitle: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      required: true,
    },
    autoOpen: {
      type: Boolean,
      default: true,
    },
    alwaysOpen: {
      type: Boolean,
      default: false,
    },
  },

  setup(props) {
    const store = useStore();

    const open = computed({
      get: () => store.getters["objects/slideOpen"],
      set: () => store.commit("objects/closeSlide"),
    });

    const styleClasses = computed(
      () =>
        ({
          objects: {
            background: "bg-yellow-700",
            title: {
              text: "text-white",
            },
            subtitle: {
              text: "text-gray-100",
            },
            dismissIcon: {
              text: "text-gray-100 hover:text-white",
              ring: "focus:ring-white",
            },
          },
          sink: {
            background: "bg-green-700",
            title: {
              text: "text-white",
            },
            subtitle: {
              text: "text-gray-200",
            },
            dismissIcon: {
              text: "text-gray-100 hover:text-white",
              ring: "focus:ring-white",
            },
          },
          source: {
            background: "bg-red-700",
            title: {
              text: "text-white",
            },
            subtitle: {
              text: "text-gray-200",
            },
            dismissIcon: {
              text: "text-gray-100 hover:text-white",
              ring: "focus:ring-white",
            },
          },
          link: {
            background: "bg-blue-700",
            title: {
              text: "text-white",
            },
            subtitle: {
              text: "text-gray-200",
            },
            dismissIcon: {
              text: "text-gray-100 hover:text-white",
              ring: "focus:ring-white",
            },
          },
        }[props.type.toLowerCase()])
    );

    const currentRoute = computed(() => store.getters["objects/currentRoute"]);

    const onClose = () => {
      if (currentRoute.value !== "objects.list" && currentRoute.value !== "objects.sinks.create") {
        store.dispatch("map/unfocusMarker");

        return store.dispatch("objects/showSlide", { route: "objects.list" });
      }

      return (open.value = false);
    };

    const closeOnEscape = (e) => {
      if (open.value && e.keyCode === 27) {
        open.value = false;
      }
    };

    onMounted(() => {
      props.autoOpen ? store.commit("objects/openSlide") : null;

      if (!props.alwaysOpen)
        document.addEventListener("keydown", closeOnEscape);
    });

    onUnmounted(() => {
      if (!props.alwaysOpen)
        document.removeEventListener("keydown", closeOnEscape);
    });

    return {
      open,
      styleClasses,
      onClose,
    };
  },
};
</script>
