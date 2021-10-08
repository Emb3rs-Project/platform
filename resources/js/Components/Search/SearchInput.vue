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
        v-model="searchKeyword"
        type="text"
        name="search"
        class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-9 sm:text-sm border-gray-300 rounded-md"
        :placeholder="placeholder"
        :disabled="disabled"
        ref="inputRef"
      />
    </div>
  </div>
</template>

<script>
import { ref, computed } from "vue";

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
    inputRef: {},
    shortcutTrigger: {
      type: Boolean,
      default: false,
    },
  },

  emits: ["update:modelValue"],

  setup(props, ctx) {
    const search = ref(null);

    const searchKeyword = computed({
      get: () => props.modelValue,
      set: (value) => ctx.emit("update:modelValue", value),
    });

    return {
      search,
      searchKeyword,
    };
  },
};
</script>
