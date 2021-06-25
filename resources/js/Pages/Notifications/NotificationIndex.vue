<template>
  <app-layout>
    <notification-feed :notifications="notifications"></notification-feed>
  </app-layout>
</template>

<script>
import { ref } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import NotificationFeed from "@/Components/NotificationFeed.vue";

export default {
  components: {
    AppLayout,
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
