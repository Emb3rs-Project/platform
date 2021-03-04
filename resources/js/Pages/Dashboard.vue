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
      <div class="w-4/12 text-gray-700">
        <div class="h-56 mb-5 p-3 border border-gray-200 shadow">
          <h1 class="leading-5 font-bold mb-3">Latest news</h1>
          <span
            >Lorem ipsum dolor sit, amet consectetur adipisicing elit.
            Temporibus fugit non repellat iure ex alias delectus similique.
            Similique ab obcaecati, ut accusamus quam, cupiditate placeat minima
            neque eum delectus itaque.</span
          >
        </div>
        <div class="h-56 my-5 p-3 border border-gray-200 shadow">
          <h1 class="leading-5 font-bold mb-3">Latest notifications</h1>
          <span
            >Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus
            inventore incidunt harum autem consectetur commodi, doloremque error
            corrupti, doloribus cum quasi. Culpa provident quis facilis est.
            Aspernatur explicabo sapiente velit?</span
          >
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
};
</script>

<style>
</style>
