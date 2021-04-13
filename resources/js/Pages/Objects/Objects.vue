<template>
  <app-layout>
    <template #content>
      123
      <embers-map :markers="markers"></embers-map>
      <slide-over></slide-over>
    </template>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import EmbersMap from "@/Components/NewLayout/Map/EmbersMap.vue";
import SlideOver from "@/Components/NewLayout/SlideOver.vue";

export default {
  components: [AppLayout, EmbersMap, SlideOver],
  setup() {
    console.log("inited");

    return {};
  },
};
</script>

<style>
</style>
