<template>
  <app-layout>
    <template #content>
      <div class="flex flex-1 bg-green-500">
        test
      </div>
      <!-- <div class="z-1000">
        <leaflet-map
          :markers="markers"
          ref="map"
        />
      </div> -->

      <!-- <div>
        <slide-over></slide-over>
      </div> -->

    </template>
  </app-layout>
</template>

<script>
  import { ref } from "vue";
  import AppLayout from "@/Layouts/NewLayout/AppLayout.vue";
  import LeafletMap from "@/Components/LeafletMap.vue";
  import SlideOver from "@/Components/NewLayout/SlideOver.vue";

  export default {
    components: { AppLayout, SlideOver, LeafletMap },

    props: {
      users: {
        type: Array,
        required: true,
      },
      sources: {
        type: Array,
        required: true,
      },
      sinks: {
        type: Array,
        required: true,
      },
    },

    setup(props, context) {
      const map = ref(null)
      const markers = ref([]);

      return {
        map,
        markers
      }
    },
  };
</script>

<style scoped>
</style>
