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
      <div v-show="open" class="w-screen max-w-2xl">
        <form
          class="h-full flex flex-col bg-white shadow-xl overflow-y-auto z-20"
        >
          <div class="flex-1">
            <!-- Header -->
            <div class="px-4 py-6 bg-indigo-700 sm:px-6">
              <div class="flex items-start justify-between space-x-3">
                <div class="space-y-1">
                  <slot name="header"></slot>
                </div>
                <div class="h-7 flex items-center">
                  <button
                    type="button"
                    class="bg-indigo-700 rounded-md text-indigo-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    @click="open = false"
                  >
                    <span class="sr-only">Close panel</span>
                    <!-- Heroicon name: outline/x -->
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
            </div>

            <slot></slot>
          </div>

          <!-- Action buttons -->
          <div class="flex-shrink-0 px-4 border-t border-gray-200 py-5 sm:px-6">
            <div class="space-x-3 flex justify-end">
              <button
                type="button"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                @click="open = false"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Create
              </button>
            </div>
          </div>
        </form>
      </div>
    </transition>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from "vue";
export default {
  setup() {
    let open = ref(true);

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
