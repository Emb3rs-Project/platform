<template>
  <div>
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
        name="search"
        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 sm:text-sm border-gray-300 rounded-md"
        :placeholder="placeholder"
        v-model="search"
        :disabled="disabled"
        ref="searchRef"
      />
      <div
        v-if="shortcutTrigger"
        class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5"
      >
        <kbd class="inline-flex items-center border border-gray-200 rounded px-2 text-sm font-sans font-medium text-gray-400">
          ⌘K
        </kbd>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from "vue";

import { SearchIcon } from "@heroicons/vue/solid";

export default {
  components: {
    SearchIcon,
  },

  props: {
    modelValue: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      default: "Press / to search",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    shortcutTrigger: {
      type: Boolean,
      default: false,
    },
  },

  emits: ["update:modelValue"],

  setup(props, ctx) {
    const searchRef = ref(null);

    const search = computed({
      get: () => props.modelValue,
      set: (value) => ctx.emit("update:modelValue", value),
    });

    function focusOnSlash(e) {
      if (e.keyCode === 191) {
        searchRef.value.focus();
      }
    }

    // function closeOnEscape(e) {
    //   if (searchRef.value && e.keyCode === 27) {
    //     searchRef.value = "";
    //     searchRef.value.blur();
    //   }
    // }

    onMounted(() => {
      document.addEventListener("keypress", focusOnSlash);
      //   document.addEventListener("keydown", closeOnEscape);
    });
    onUnmounted(() => {
      document.removeEventListener("keypress", focusOnSlash);
      //   document.removeEventListener("keydown", closeOnEscape);
    });

    return {
      search,
      searchRef,
    };
  },
};
</script>
