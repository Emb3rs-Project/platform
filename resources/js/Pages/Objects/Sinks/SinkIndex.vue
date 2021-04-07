<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sinks
      </h2>
    </template>

    <div
      class="flex flex-col md:flex-row h-screen md:h-table-and-map p-5 gap-5"
    >
      <div class="w-full h-96 md:h-full md:w-1/2">
        <div class="flex flex-col max-h-full">
          <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
            <h3 class="text-lg leading-6 font-bold text-gray-900">Sinks</h3>
            <p class="text-sm text-gray-500">A list of all the Sinks</p>
          </div>
          <div class="overflow-y-auto overflow-x-auto shadow sm:rounded-b-md">
            <div v-if="sinks.length">
              <!-- <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th
                      scope="col"
                      class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      ID
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Name
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Template
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Category
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Location
                    </th>
                    <th
                      scope="col"
                      class="relative px-6 py-3"
                    >
                      <span class="sr-only">Edit</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(sink, index) in sinks"
                    :key="index"
                    :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                  >
                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ sink.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ sink.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ sink.template ? sink.template.name : "Not Assigned" }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ sink.template ? sink.template.category_id : "Not Assigned" }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      <a
                        :class="{'font-bold text-green-600 cursor-pointer hover:text-green-700': sink.location }"
                        @click="centerAtLocation(sink.location)"
                      >
                        {{ sink.location ? sink.location.name : "Not Assigned" }}
                      </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                      <inertia-link :href="route('objects.sinks.show', sink.id)">
                        <detail-icon class="text-gray-500 font-medium text-sm w-5"></detail-icon>
                      </inertia-link>
                      <inertia-link :href="route('objects.sinks.edit', sink.id)">
                        <edit-icon class="text-gray-500 font-medium text-sm w-5"></edit-icon>
                      </inertia-link>
                      <button
                        class="focus:outline-none"
                        @click="onDelete(sink)"
                      >
                        <trash-icon class="text-red-500 font-medium text-sm w-5"></trash-icon>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table> -->
              <amazing-index-table
                v-model="sinks"
                :columns="tableColumns"
                @onUpdateSelection="onUpdateMarkers"
              >
                <!-- ID -->
                <template #header-id> ID </template>
                <template #body-id="{ item }">
                  <td class="text-left pl-4">
                    {{ item.id }}
                  </td>
                </template>

                <!-- Name -->
                <template #header-name> Name </template>
                <template #body-name="{ item }">
                  <td class="text-left pl-4">
                    {{ item.name }}
                  </td>
                </template>

                <!-- Template -->
                <template #header-template> Template </template>
                <template #body-template="{ item }">
                  <td class="text-left pl-4">
                    {{ item.template.name }}
                  </td>
                </template>

                <!-- Location -->
                <template #header-location> Location </template>
                <template #body-location="{ item }">
                  <td class="text-left pl-4">
                    <a
                      :class="{
                        'font-bold text-green-600 cursor-pointer hover:text-green-700':
                          item.location,
                      }"
                      @click="centerAtLocation(item.location)"
                    >
                      {{ item.location ? item.location.name : "Not Assigned" }}
                    </a>
                  </td>
                </template>

                <!-- Actions -->
                <template #header-actions> </template>
                <template #body-actions="{ item }">
                  <td
                    class="pr-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end"
                  >
                    <inertia-link :href="route('objects.sinks.show', item.id)">
                      <detail-icon
                        class="text-gray-500 font-medium text-sm w-5"
                      ></detail-icon>
                    </inertia-link>
                    <inertia-link :href="route('objects.sinks.edit', item.id)">
                      <edit-icon
                        class="text-gray-500 font-medium text-sm w-5"
                      ></edit-icon>
                    </inertia-link>
                    <button class="focus:outline-none" @click="onDelete(item)">
                      <trash-icon
                        class="text-red-500 font-medium text-sm w-5"
                      ></trash-icon>
                    </button>
                  </td>
                </template>
              </amazing-index-table>
            </div>
            <div
              v-else
              class="flex items-center place-content-center bg-gray-50 h-20 md:h-64"
            >
              <h1 class="text-2xl font-extrabold text-gray-300 uppercase">
                No Sinks Found
              </h1>
            </div>
          </div>
        </div>
      </div>

      <div class="w-full h-full md:w-1/2">
        <leaflet-map :markers="markers" ref="map"></leaflet-map>
      </div>
    </div>

    <div class="w-full px-16 flex justify-end">
      <button class="px-6 py-3 bg-pink-500" @click="hideActions()">
        hahahaha
      </button>
      <primary-link-button :path="'objects.sinks.create'">
        Create New Sink
      </primary-link-button>
    </div>
  </app-layout>
</template>

<script>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

import useUniqueLocations from "@/Composables/useUniqueLocations";

import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton";
import TrashIcon from "@/Icons/TrashIcon.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import DetailIcon from "@/Icons/DetailIcon.vue";
import AmazingIndexTable from "@/Components/Tables/AmazingIndexTable.vue";

export default {
  components: {
    AppLayout,
    LeafletMap,
    PrimaryLinkButton,
    TrashIcon,
    EditIcon,
    DetailIcon,
    AmazingIndexTable,
  },

  props: {
    sinks: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const map = ref(null);
    const markers = ref([]);

    const uniqueSinks = useUniqueLocations(props.sinks);

    for (const sink of uniqueSinks.value) {
      markers.value.push(sink.data);
    }

    const tableColumns = ref(["id", "name", "template", "location", "actions"]);

    return {
      map,
      markers,
      tableColumns,
      uniqueSinks,
    };
  },
  methods: {
    hideActions() {
      this.tableColumns = ["id", "name", "template"];
    },
    centerAtLocation(location) {
      if (!location) return;

      const marker = markers.value.find((m) => m.id === location.geo_object.id);
      map.value.centerAtLocation(marker);
    },
    onDelete(sink) {
      Inertia.delete(route("objects.sinks.destroy", sink.id));
    },
    onUpdateMarkers() {
      const selected = this.uniqueSinks.filter((s) => s.selected);
      this.markers = selected.map((S) => S.data);
    },
  },
};
</script>
