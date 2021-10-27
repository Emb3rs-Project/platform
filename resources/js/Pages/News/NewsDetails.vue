<template>
  <AppLayout>
    <SiteHead title="News" />

    <div class="bg-gray-50 pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
      <div class="relative max-w-lg mx-auto divide-y-2 divide-gray-200 lg:max-w-7xl">
        <div>
          <Link
            :href="route('news.index')"
            class="flex justify-start items-center gap-4"
            preserve-scroll
          >
          <ArrowLeftIcon class="h-8 w-8" />
          <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">
            News
          </h2>
          </Link>

          <div class="mt-3 sm:mt-4 lg:grid lg:grid-cols-1 lg:gap-5 lg:items-center">
            <p class="text-xl text-gray-500">
              Here you may find all the lattest news about your Institution.
            </p>
          </div>
        </div>
        <div class="mt-6 pt-10 grid gap-1 lg:grid-cols-1 lg:gap-x-5 ">
          <p class="text-sm text-gray-500">
            <time :datetime="news.updated_at">
              {{ getFriendlyLifetime(news.created_at) }}
            </time>
          </p>

          <p class="text-xl font-semibold text-gray-900">
            {{ news.title }}
          </p>
          <p
            class="mt-3 text-base text-gray-500"
            v-html="news.content"
          >
          </p>

        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { computed, onBeforeUnmount, ref } from "vue";
import { Link, usePage } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import { ArrowLeftIcon } from "@heroicons/vue/solid";

import { getFriendlyLifetime } from "@/Helpers/helpers";

export default {
  components: {
    AppLayout,
    SiteHead,
    Link,
    ArrowLeftIcon,
  },

  props: {
    news: {
      type: Object,
      required: true,
    },
  },

  setup(props) {
    const previousUrl = usePage().props.value.previousUrl;

    const onGoBack = () => {
      if (previousUrl !== "empty") return previousUrl;
    };
    // const onGoBack = computed(() => {});

    return {
      getFriendlyLifetime,
      onGoBack,
    };
  },
};
</script>
