<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sources
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Source</h1>
    </div>
    <div class="flex p-5">
      <div class="w-6/12">
        <input-row
          heading="Details | Source"
          desc="Enter a name for your source"
        >
          Source Name
        </input-row>

        <input-row
          desc="Choose how you want to define your Source"
          class="mt-10"
        >
          What type of Source?
        </input-row>

        <input-row desc="Description" class="mt-10"> Label </input-row>
        <input-row desc="Description" class="mt-10"> Label </input-row>
        <input-row desc="Description" class="mt-10"> Label </input-row>
        <input-row desc="Description" class="mt-10"> Label </input-row>
        <input-row desc="Description" class="mt-10"> Label </input-row>
      </div>

      <div class="w-6/12">
        <leaflet-map></leaflet-map>
      </div>
    </div>
    <div class="w-full my-5 px-16 flex justify-end">
      <jet-link-button path="objects.sources.create"> Create </jet-link-button>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";
import JetLinkButton from "@/Jetstream/LinkButton";

export default {
  components: {
    AppLayout,
    LeafletMap,
    InputRow,
    JetLinkButton,
  },
};
</script>

<style>
</style>
