<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="flex p-5">
      <div class="w-8/12">
        <leaflet-map></leaflet-map>
      </div>
      <div class="w-4/12 text-gray-700 p-5">
        <div class="h-56 mb-5 p-3 border border-gray-200 shadow">
          <h1 class="leading-5 font-bold mb-3">Latest news</h1>
          <span v-if="news.length == 0">No News</span>
          <div
            class="border-t border-gray-400 mt-2 p-2"
            v-for="ne of news"
            :key="ne.id"
          >
            <span class="leading-4 font-bold block">{{ ne.title }}</span>
            <span class="text-sm text-justify">{{ ne.content }}</span>
          </div>
        </div>
        <div class="h-56 my-5 p-3 border border-gray-200 shadow">
          <h1 class="leading-5 font-bold mb-3">Latest notifications</h1>
          <span v-if="notifications.length == 0">No New Notifications</span>
          <div
            class="border-t border-gray-400 mt-2 p-2"
            v-for="not of notifications"
            :key="not.id"
          >
            <span class="leading-4 font-bold block">{{ not.title }}</span>
            <span class="text-sm text-justify">{{ not.content }}</span>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetLabel from "@/Jetstream/Label";
import LeafletMap from "@/Components/LeafletMap";

export default {
  components: {
    AppLayout,
    JetLabel,
    LeafletMap,
  },
  props: {
    notifications: Array,
    news: Array,
  },
};
</script>

<style>
</style>
