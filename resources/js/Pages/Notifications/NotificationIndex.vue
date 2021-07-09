<template>
  <AppLayout>

    <SiteHead title="Notifications" />
    <NotificationFeed :notifications="notifications" />
  </AppLayout>
</template>

<script>
import { ref } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import NotificationFeed from "@/Components/NotificationFeed.vue";

export default {
  components: {
    AppLayout,
    SiteHead,
    NotificationFeed,
  },

  props: {
    notifications: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    return {};
  },
};
</script>

<style>
</style>
