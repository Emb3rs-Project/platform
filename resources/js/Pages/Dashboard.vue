<template>
  <app-layout>
    <template #content>

      <!-- <div class="border-b border-gray-200 px-4 py-4 sm:flex sm:items-center sm:justify-between sm:px-6 lg:px-8">
        <div class="flex-1 min-w-0">
          <h1 class="text-lg font-medium leading-6 text-gray-900 sm:truncate">
            Home
          </h1>
        </div>
        <div class="mt-4 flex sm:mt-0 sm:ml-4">
          <button
            type="button"
            class="order-1 ml-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:order-0 sm:ml-0"
          >
            Share
          </button>
          <button
            type="button"
            class="order-0 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:order-1 sm:ml-3"
          >
            Create
          </button>
        </div>
      </div> -->
      <embers-map :markers="markers"></embers-map>
      <slide-over></slide-over>
      <filter-dropdown></filter-dropdown>
    </template>
  </app-layout>
</template>

<script>
  import { ref } from "vue";

  import useUniqueLocations from "@/Composables/useUniqueLocations";

  import AppLayout from "@/Layouts/AppLayout.vue";
  import EmbersMap from "@/Components/NewLayout/Map/EmbersMap.vue";
  import SlideOver from "@/Components/NewLayout/SlideOver.vue";
  import FilterDropdown from "@/Components/NewLayout/FilterDropdown.vue";


  export default {
    components: { AppLayout, SlideOver, EmbersMap, FilterDropdown },

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
      const map = ref(null);
      const markers = ref([]);

      const locations = props.sources.concat(props.sinks);

      const uniqueLocations = useUniqueLocations(locations);

      for (const _location of uniqueLocations.value) {
        markers.value.push(_location.data)
      }

      return {
        map,
        markers,
      }
    },
  };
</script>

<style scoped>
</style>
