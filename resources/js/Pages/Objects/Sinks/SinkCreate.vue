<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sink
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Sink</h1>
    </div>
    <div class="flex p-5">
      <div class="w-6/12">
        <input-row heading="Details | Sink" desc="Enter a name for your sink">
          Sink Name
        </input-row>

        <input-row desc="Choose how you want to define your Sink" class="mt-10">
          What type of Sink?
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
      <jet-link-button path="objects.sinks.create"> Create </jet-link-button>
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