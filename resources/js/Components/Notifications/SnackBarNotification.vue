<template>
  <transition
    enter-active-class="transition duration-100 ease-out"
    enter-from-class="transform scale-95 opacity-0"
    enter-to-class="transform scale-100 opacity-100"
    leave-active-class="transition duration-75 ease-in"
    leave-from-class="transform scale-100 opacity-100"
    leave-to-class="transform scale-95 opacity-0"
  >
    <div
      class="fixed bottom-0 inset-x-0 pb-2 sm:pb-5"
      v-if="show"
    >
      <div
        class="mx-auto px-2 sm:px-6 lg:px-8"
        :class="sizeClasses"
      >
        <div
          class="p-2 rounded-lg shadow-lg sm:p-3"
          :class="styleClasses.background"
        >
          <div class="flex items-center justify-between flex-wrap">
            <div class="w-0 flex-1 flex items-center">
              <span
                class="flex p-2 rounded-lg"
                :class="styleClasses.icon"
              >
                <InformationCircleIcon
                  v-if="type === 'info'"
                  class="h-6 w-6 text-white"
                  aria-hidden="true"
                />
                <ExclamationCircleIcon
                  v-if="type === 'warning'"
                  class="h-6 w-6 text-white"
                  aria-hidden="true"
                />
                <XCircleIcon
                  v-if="type === 'danger'"
                  class="h-6 w-6 text-white"
                  aria-hidden="true"
                />
                <CheckCircleIcon
                  v-if="type === 'success'"
                  class="h-6 w-6 text-white"
                  aria-hidden="true"
                />
              </span>
              <p class="ml-3 font-medium text-white truncate">
                <span class="md:inline">
                  {{ message }}
                </span>
              </p>
            </div>
            <div class="order-2 flex-shrink-0 sm:order-3 sm:ml-2">
              <button
                type="button"
                class="-mr-1 flex p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-white"
                :class="styleClasses.dismissIcon"
                @click="show = false"
              >
                <span class="sr-only">Dismiss</span>
                <XIcon
                  class="h-6 w-6 text-white"
                  aria-hidden="true"
                />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import { computed, onUpdated } from "vue";
import { useStore } from "vuex";

import {
  InformationCircleIcon,
  ExclamationCircleIcon,
  XCircleIcon,
  CheckCircleIcon,
  XIcon,
} from "@heroicons/vue/outline";

export default {
  components: {
    InformationCircleIcon,
    ExclamationCircleIcon,
    XCircleIcon,
    CheckCircleIcon,
    XIcon,
  },

  setup() {
    const store = useStore();

    const show = computed({
      get: () => store.getters["snackbarNotifications/show"],
      set: () => store.commit("snackbarNotifications/hide"),
    });
    const type = computed(() => store.getters["snackbarNotifications/type"]);
    const size = computed(() => store.getters["snackbarNotifications/size"]);
    const message = computed(
      () => store.getters["snackbarNotifications/message"]
    );

    const styleClasses = computed(
      () =>
        ({
          info: {
            background: "bg-blue-500",
            icon: "bg-blue-700",
            dismissIcon: "hover:bg-blue-400",
          },
          warning: {
            background: "bg-yellow-500",
            icon: "bg-yellow-700",
            dismissIcon: "hover:bg-yellow-400",
          },
          danger: {
            background: "bg-red-500",
            icon: "bg-red-700",
            dismissIcon: "hover:bg-red-400",
          },
          success: {
            background: "bg-green-500",
            icon: "bg-green-700",
            dismissIcon: "hover:bg-green-400",
          },
        }[type.value])
    );

    const sizeClasses = computed(
      () =>
        ({
          small: "max-w-2xl",
          medium: "max-w-4xl",
          large: "max-w-6xl",
          xlarge: "max-w-7xl",
        }[size.value])
    );

    return {
      show,
      type,
      size,
      message,
      styleClasses,
      sizeClasses,
    };
  },
};
</script>
