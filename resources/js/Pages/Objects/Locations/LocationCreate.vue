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
      <div class="w-6/12">
        <input-row
          heading="Details | Location"
          desc="Enter a name for your location"
        >
          Location Name
        </input-row>

        <input-row desc="Choose how you want to define your Location">
          What type of location?
        </input-row>
      </div>
      <div class="w-6/12">
        <leaflet-map></leaflet-map>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";

export default {
  components: {
    AppLayout,
    LeafletMap,
    InputRow,
  },
};
</script>

<style>
</style>
