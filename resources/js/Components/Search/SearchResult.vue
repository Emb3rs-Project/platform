<template>
  <div v-if="loading">
    <TextSkeleton
      :columns="1"
      :count="2"
    />
  </div>
  <div v-else>
    <div v-if="results.length">
      <ul
        role="list"
        class="mt-4 grid grid-cols-1 gap-4 divide-y divide-gray-200 p-1"
      >
        <li
          v-for="(entity, entityIdx) in results"
          :key="entityIdx"
        >
          <SearchItem :entity="entity" />
        </li>
      </ul>
    </div>
    <div
      v-else
      class="flex items-center justify-center h-60"
    >
      <p class="text-2xl font-bold text-gray-400">
        It looks like there are no results for this search.
      </p>
    </div>
  </div>
</template>

<script>
import TextSkeleton from "@/Components/Skeletons/TextSkeleton.vue";
import SearchItem from "@/Components/Search/SearchItem.vue";

export default {
  components: {
    TextSkeleton,
    SearchItem,
  },

  props: {
    loading: {
      type: Boolean,
      required: true,
    },
    results: {
      type: Array,
      required: true,
    },
  },
};
</script>
