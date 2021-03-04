<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Locations
      </h2>
    </template>

    <div class="w-full my-5 px-10 flex justify-end">
      <input
        class="border rounded-full bg-gray-200 border-gray-200 outline-none pl-5 leading-3"
        placeholder="search..."
      />
    </div>

    <leaflet-map></leaflet-map>

    <div class="w-full my-5 px-10 flex justify-end">
      <jet-link-button path="objects.locations.create">
        Create New Location
      </jet-link-button>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetInput from "@/Jetstream/Input";
import JetLinkButton from "@/Jetstream/LinkButton";
import JetLabel from "@/Jetstream/Label";
import LeafletMap from "@/Components/LeafletMap";

export default {
  components: {
    AppLayout,
    JetInput,
    JetLabel,
    JetLinkButton,
    LeafletMap,
  },
  data() {
    return {
      search: "",
    };
  },

  methods: {},
};
</script>

<style scoped>
</style>
