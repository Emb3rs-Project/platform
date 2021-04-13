<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Links
      </h2>
    </template>

    <div class="w-full my-5 px-10 flex justify-end">
      <input
        type="text"
        class="border rounded-full bg-gray-200 border-gray-200 outline-none pl-5 leading-3"
        placeholder="search..."
      />
    </div>

    <div class="flex p-5 h-screen md:h-content">
      <div class="w-full">
        <leaflet-map-link-viewer :markers="markers"></leaflet-map-link-viewer>
      </div>
    </div>
    <div class="w-full my-5 px-10 flex justify-end">
      <jet-link-button
        :disabled="true"
        path="objects.locations.create"
        class="mx-2"
      >
        Configure Links
      </jet-link-button>
      <jet-link-button
        path="objects.links.create"
        class="mx-2"
      >
        Create New Link
      </jet-link-button>
    </div>
  </app-layout>
</template>

<script>
  import AppLayout from "@/Layouts/AppLayout";
  import JetInput from "@/Jetstream/Input";
  import JetLinkButton from "@/Jetstream/LinkButton";
  import JetLabel from "@/Jetstream/Label";
  import LeafletMapLinkViewer from "@/Components/LeafletMapLinkViewer";
  import { ref } from "vue";

  export default {
    components: {
      AppLayout,
      JetInput,
      JetLabel,
      JetLinkButton,
      LeafletMapLinkViewer,
    },
    props: {
      links: {
        type: Array,
        required: true,
      },
    },
    setup(props) {
      const markers = ref([]);

      for (const link of props.links) {
        markers.value.push(...link.geo_segments.map((s) => s.data));
      }

      return { markers };
    },
    methods: {},
  };
</script>

<style scoped>
</style>
