<template>
  <div>
    <ul
      role="list"
      :class="['mt-4 grid gap-4', columns]"
    >
      <li
        v-for="index in Array(count)"
        :key="index"
      >
        <div class="border border-gray-300 shadow rounded-md p-4 w-full">
          <div class="animate-pulse flex space-x-4">
            <div class="flex-1 space-y-4 py-1">
              <div class="h-4 bg-gray-400 rounded w-3/4"></div>
              <div class="space-y-2">
                <div class="h-4 bg-gray-400 rounded"></div>
                <div class="h-4 bg-gray-400 rounded w-5/6"></div>
              </div>
            </div>
          </div>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import { computed } from "vue";

export default {
  props: {
    columns: {
      type: Number,
      default: 1,
    },
    count: {
      type: Number,
      default: 2,
    },
  },

  setup(props) {
    const columns = computed(() => `grid-cols-${props.columns}`);

    return {
      columns,
    };
  },
};
</script>
