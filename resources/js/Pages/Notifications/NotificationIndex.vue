<template>
  <AppLayout>
    <SiteHead title="Notifications" />
    <NotificationFeed
      :notifications="notifications"
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
    notifications: {
      type: Array,
      required: true,
    },
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
