<template>
  <AppLayout>

    <SiteHead title="Notifications" />

    <NotificationFeed
      :unreadNotifications="unreadNotifications"
      :readNotifications="readNotifications"
    />
  </AppLayout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import NotificationFeed from "@/Components/Notifications/NotificationFeed.vue";

export default {
  components: {
    AppLayout,
    SiteHead,
    NotificationFeed,
  },

  props: {
    unreadNotifications: {
      type: Array,
      required: true,
    },
    readNotifications: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style>
</style>
