<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Locations
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Location</h1>
    </div>
    <div class="flex p-5">
      <div class="flex w-6/12"></div>
      <div class="flex w-6/12">
        <leaflet-map></leaflet-map>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";

export default {
  components: {
    AppLayout,
    LeafletMap,
  },
};
</script>

<style>
</style>
