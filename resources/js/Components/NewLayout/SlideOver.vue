<template>
  <div class="absolute inset-y-0 right-0 max-w-full flex overflow-hidden">
    <transition
      enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div
        v-if="open"
        class="flex flex-col justify-between z-20 w-screen max-w-2xl bg-gray-50 divide-y divide-gray-200"
      >
        <!-- Header -->
        <div class="py-6 px-4 sm:px-6" :class="headerBackground">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-white">{{ title }}</h2>
            <div class="ml-3 h-7 flex items-center">
              <button
                type="button"
                class="rounded-md hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                :class="dismissButtonTextColor"
                @click="open = false"
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
            <p class="text-sm" :class="subtitleTextColor">
              {{ subtitle }}
            </p>
          </div>
        </div>
        <div
          class="overflow-y-auto h-full py-6 space-y-6 sm:py-0 sm:space-y-0 sm:divide-y sm:divide-gray-200"
        >
          <slot></slot>
        </div>
        <!-- <form
          class="h-full divide-y divide-gray-200 flex flex-col bg-red-500 shadow-xl z-20"
        >
          <div class="flex-1 h-0 overflow-y-scroll">
            <slot></slot>
          </div>
          <div class="flex-shrink-0 px-4 py-4 flex justify-end">
          <slot name="actions"></slot>
        </div>
        </form>-->
        <div class="flex-shrink-0 px-4 py-4 flex justify-end gap-5">
          <slot name="actions"></slot>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { onMounted, onUnmounted, computed } from "vue";
export default {
  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    title: {
      type: String,
      default: "Not Defined",
    },
    subtitle: {
      type: String,
      default: "",
    },
    headerBackground: {
      type: String,
      default: "bg-green-700",
    },
    dismissButtonTextColor: {
      type: String,
      default: "text-green-200",
    },
    subtitleTextColor: {
      type: String,
      default: "text-green-300",
    },
  },

  emits: ["update:modelValue"],

  setup(props, { emit }) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const closeOnEscape = (e) => {
      if (open.value && e.keyCode === 27) {
        open.value = false;
      }
    };

    onMounted(() => document.addEventListener("keydown", closeOnEscape));
    onUnmounted(() => document.removeEventListener("keydown", closeOnEscape));

    return {
      open,
    };
  },
};
</script>
