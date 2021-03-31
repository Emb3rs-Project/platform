<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sources
      </h2>
    </template>

    <div
      class="flex flex-col md:flex-row h-screen md:h-table-and-map p-5 gap-5"
    >
      <div class="w-full h-96 md:h-full md:w-1/2">
        <div class="flex flex-col max-h-full">
          <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
            <h3 class="text-lg leading-6 font-bold text-gray-900">Sources</h3>
            <p class="text-sm text-gray-500">A list of all the Sources</p>
          </div>
          <div class="overflow-y-auto overflow-x-auto shadow sm:rounded-b-md">
            <div v-if="sources.length">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="relative px-6 py-3">
                      <div class="flex justify-center items-center h-5">
                        <input
                          name="comments"
                          type="checkbox"
                          class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                          v-model="massAction"
                        />
                      </div>
                    </th>
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
                    <th scope="col" class="relative px-6 py-3">
                      <span class="sr-only">Edit</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(source, index) in sources"
                    :key="index"
                    :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                  >
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex justify-center items-center h-5">
                        <!-- <input
                          :name="source.name"
                          :value="source"
                          v-model="selected"
                          type="checkbox"
                          class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded disabled:opacity-50"
                          :disabled="source.location ? false : true"
                        /> -->
                        <!-- This example requires Tailwind CSS v2.0+ -->
                        <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                        <button
                          type="button"
                          class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                          aria-pressed="false"
                          @click="source.selected = !source.selected"
                        >
                          <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
                          <span
                            v-bind:class="{
                              'translate-x-5': source.selected,
                              'translate-x-0': source.selected == false,
                              'translate-x-2': source.selected == null,
                            }"
                            class="pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
                          >
                            <!-- Enabled: "opacity-0 ease-out duration-100", Not Enabled: "opacity-100 ease-in duration-200" -->
                            <!-- CROSS -->
                            <span
                              v-bind:class="{
                                'opacity-0 ease-out duration-100':
                                  source.selected == true ||
                                  source.selected == null,
                                'opacity-100 ease-in duration-200':
                                  source.selected == false,
                              }"
                              class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
                              aria-hidden="true"
                            >
                              <svg
                                class="bg-white h-3 w-3 text-gray-400"
                                fill="none"
                                viewBox="0 0 12 12"
                              >
                                <path
                                  d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                                  stroke="currentColor"
                                  stroke-width="2"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                />
                              </svg>
                            </span>
                            <!-- MINUS -->
                            <span
                              v-bind:class="{
                                'opacity-100 ease-in duration-200':
                                  source.selected == null,
                                'opacity-0 ease-out duration-100':
                                  source.selected == false || source.selected,
                              }"
                              class="opacity-0 ease-out duration-100 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
                              aria-hidden="true"
                            >
                              <svg
                                className="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  strokeLinecap="round"
                                  strokeLinejoin="round"
                                  strokeWidth="{2}"
                                  d="M20 12H4"
                                />
                              </svg>
                            </span>
                            <!-- CHECK -->
                            <span
                              v-bind:class="{
                                'opacity-100 ease-in duration-200':
                                  source.selected,
                                'opacity-0 ease-out duration-100': !source.selected,
                              }"
                              class="opacity-0 ease-out duration-100 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
                              aria-hidden="true"
                            >
                              <svg
                                class="bg-white h-3 w-3 text-indigo-600"
                                fill="currentColor"
                                viewBox="0 0 12 12"
                              >
                                <path
                                  d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                                />
                              </svg>
                            </span>
                          </span>
                        </button>
                      </div>
                    </td>
                    <td
                      class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium text-gray-900"
                    >
                      {{ source.id }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                    >
                      {{ source.name }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                    >
                      {{ source.template.name }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                    >
                      {{ source.template.category.name }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                    >
                      <a
                        :class="{
                          'font-bold text-green-700 cursor-pointer hover:text-green-500':
                            source.location,
                        }"
                        @click="centerAtLocation(source.location)"
                      >
                        {{
                          source.location
                            ? source.location.name
                            : "Not Assigned"
                        }}
                      </a>
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2"
                    >
                      <inertia-link
                        :href="route('objects.sources.show', source.id)"
                      >
                        <detail-icon
                          class="text-gray-500 font-medium text-sm w-5"
                        ></detail-icon>
                      </inertia-link>
                      <inertia-link
                        :href="route('objects.sources.edit', source.id)"
                      >
                        <edit-icon
                          class="text-gray-500 font-medium text-sm w-5"
                        ></edit-icon>
                      </inertia-link>
                      <button
                        class="focus:outline-none"
                        @click="onDelete(source.location)"
                      >
                        <trash-icon
                          class="text-red-500 font-medium text-sm w-5"
                        ></trash-icon>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div
              v-else
              class="flex items-center place-content-center bg-gray-50 h-20 md:h-64"
            >
              <h1 class="text-2xl font-extrabold text-gray-300 uppercase">
                No Sources Found
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
      <primary-link-button :path="'objects.sources.create'">
        Create New Source
      </primary-link-button>
    </div>
  </app-layout>
</template>

<script>
import { watch, ref, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";

import useUniqueLocations from "@/Composables/useUniqueLocations";

import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton";
import TrashIcon from "@/Icons/TrashIcon.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import DetailIcon from "@/Icons/DetailIcon.vue";

export default {
  components: {
    AppLayout,
    LeafletMap,
    PrimaryLinkButton,
    TrashIcon,
    EditIcon,
    DetailIcon,
  },

  props: {
    sources: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const map = ref(null);
    const markers = ref([]);
    const selected = ref([]);
    const massAction = ref(true);

    const uniqueSourceLocations = useUniqueLocations(props.sources);

    // Populate the map with unique locations only.
    for (const _source of uniqueSourceLocations.value) {
      markers.value.push(_source.data);
    }

    onMounted(() => {
      selectAll();
    });

    function selectAll() {
      // Select (checked) all the applicable sources
      for (const _source of props.sources) {
        if (!_source.location) continue;
        selected.value.push(_source);
      }
    }

    function deSelectAll() {
      selected.value = [];
    }

    watch(massAction, (selected) => {
      if (selected) {
        selectAll();
        return;
      }
      deSelectAll();
    });

    watch(selected, (selected, prevSelected) => {
      // massAction.value = false;
      if (!selected.length) {
        markers.value = [];
        return;
      }

      for (const _source of selected) {
        const marker = uniqueSourceLocations.value.find(
          (location) => location.id === _source.id
        );
        if (marker) continue;
        markers.value.push(_source.data);
      }
    });

    function onDelete(source) {
      // show modal here
      Inertia.delete(route("objects.sources.destroy", source.id));
    }

    function centerAtLocation(location) {
      if (!markers.value.length) return;

      const marker = markers.value.find((m) => m.id === location.geo_object.id);
      map.value.centerAtLocation(marker);
    }

    return {
      map,
      markers,
      selected,
      massAction,
      onDelete,
      centerAtLocation,
    };
  },
};
</script>
