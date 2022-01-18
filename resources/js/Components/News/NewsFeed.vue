<template>
  <div class="bg-gray-50 pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
    <div class="relative max-w-lg mx-auto divide-y-2 divide-gray-200 lg:max-w-7xl">
      <div>
        <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
          News
        </h2>
        <div class="mt-3 sm:mt-4 lg:grid lg:grid-cols-2 lg:gap-5 lg:items-center">
          <p class="text-xl text-gray-500">
            Here you may find all the lattest news about your Institution.
          </p>
        </div>
      </div>

      <div
        v-if="news.length"
        class="mt-6 pt-10 grid gap-16 lg:grid-cols-2 lg:gap-x-5 lg:gap-y-12"
      >
        <div
          v-for="article in news"
          :key="article.id"
        >
          <NewsFeedItem :article="article" />
        </div>
      </div>
      <div
        v-else
        class="flex justify-center pt-10"
      >
        <p class="block font-bold text-2xl text-gray-400 p-4">
          No news were found.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

import { getFriendlyLifetime } from "@/Helpers/helpers";
import NewsFeedItem from "@/Components/News/NewsFeedItem.vue";

export default {
  components: {
    Link,
    NewsFeedItem,
  },

  props: {
    news: {
      type: Array,
      required: true,
    },
  },

  setup() {
    return {
      getFriendlyLifetime,
    };
  },
};
</script>
