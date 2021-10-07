<template>
  <!-- <div class="px-3 mt-5">
    <label
      for="search"
      class="sr-only"
    >
      Search
    </label>
    <div class="mt-1 relative rounded-md shadow-sm">
      <div
        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
        aria-hidden="true"
      >
        <SearchIcon class="mr-3 h-4 w-auto text-gray-400" />
      </div>
      <input
        type="text"
        name="searchPlaceholder"
        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 sm:text-sm border-gray-300 rounded-md"
        placeholder="Quick search"
        @focus="onFocus"
        ref="search"
      />
      <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
        <kbd class="inline-flex items-center border border-gray-200 rounded-md px-2 text-sm font-sans font-medium text-gray-400">
          ↵ Enter
        </kbd>
      </div>
    </div>
  </div> -->

  <div class="px-3 mt-5">
    <div class="mt-1 relative rounded-md shadow-sm">
      <div
        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
        aria-hidden="true"
      >
        <SearchIcon class="mr-3 h-4 w-auto text-gray-400" />
      </div>
      <button
        type="button"
        class="inline-flex items-center bg-white border border-gray-300 rounded-md pl-9 px-2 text-sm font-sans font-medium text-gray-500 w-full h-10"
        @click="onClick"
      >
        Quick search
      </button>
      <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
        <kbd class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400">
          ↵ Enter
        </kbd>
      </div>
    </div>
  </div>

  <QuickSearchModal v-model="showQuickSearchModal" />

</template>

<script>
import { ref, onMounted, onUnmounted } from "vue";

import QuickSearchModal from "@/Components/Modals/QuickSearchModal.vue";

import { SearchIcon } from "@heroicons/vue/solid";

export default {
  components: {
    SearchIcon,
    QuickSearchModal,
  },

  props: {},

  setup(props, ctx) {
    const showQuickSearchModal = ref(true);

    function focusOnEnter(e) {
      if (e.keyCode === 13) {
        onClick();
      }
    }

    const onClick = () => {
      showQuickSearchModal.value = true;
      console.log("input");
    };

    onMounted(() => document.addEventListener("keypress", focusOnEnter));
    onUnmounted(() => document.removeEventListener("keypress", focusOnEnter));

    return {
      showQuickSearchModal,
      onClick,
    };
  },
};
</script>
