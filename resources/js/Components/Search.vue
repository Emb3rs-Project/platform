<template>
  <div class="px-3 mt-5 z-0">
    <label for="search" class="sr-only"> Search </label>
    <div class="mt-1 relative rounded-md shadow-sm">
      <div
        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
        aria-hidden="true"
      >
        <!-- Heroicon name: solid/search -->
        <svg
          class="mr-3 h-4 w-4 text-gray-400"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
          fill="currentColor"
          aria-hidden="true"
        >
          <path
            fill-rule="evenodd"
            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
            clip-rule="evenodd"
          />
        </svg>
      </div>
      <input
        type="text"
        name="search"
        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-9 sm:text-sm border-gray-300 rounded-md"
        :placeholder="placeholder"
        v-model="search"
        :disabled="disabled"
        ref="searchRef"
      />
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onUnmounted } from "@vue/runtime-core";
export default {
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
  },

  emits: ["update:modelValue"],

  setup(props, { emit }) {
    const searchRef = ref(null);

    const search = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
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
      document.addEventListener("keydown", focusOnSlash);
      //   document.addEventListener("keydown", closeOnEscape);
    });
    onUnmounted(() => {
      document.removeEventListener("keydown", focusOnSlash);
      //   document.removeEventListener("keydown", closeOnEscape);
    });

    return {
      search,
      searchRef,
    };
  },
};
</script>
