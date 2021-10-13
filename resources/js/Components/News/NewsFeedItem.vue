<template>
  <div class="bg-red-300">
    <p class="text-sm text-gray-500">
      <time :datetime="article.updated_at">
        {{ getFriendlyLifetime(article.created_at) }}
      </time>
    </p>
    <Link
      :href="route('news.show', article.id)"
      class="mt-2 block"
    >
    <p class="text-xl font-semibold text-gray-900">
      {{ article.title }}
    </p>
    <p
      class="mt-3 text-base text-gray-500"
      v-html="article.content"
    >
    </p>
    </Link>
    <div class="mt-3">
      <Link
        :href="route('news.show', article.id)"
        class="text-base font-semibold text-indigo-600 hover:text-indigo-500"
      >
      Read full article
      </Link>
    </div>
  </div>

</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

import { getFriendlyLifetime } from "@/Helpers/helpers";

export default {
  components: {
    Link,
  },

  props: {
    article: {
      type: Object,
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
