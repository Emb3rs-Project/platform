<template>
  <AppLayout>
    <SiteHead title="News" />
    <NewsFeed :news="news" />
  </AppLayout>

</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import NewsFeed from "@/Components/News/NewsFeed.vue";

export default {
  components: {
    AppLayout,
    SiteHead,
    NewsFeed,
  },

  props: {
    news: {
      type: Array,
      required: true,
    },
  },
};
</script>
