<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sources
      </h2>
    </template>

    <div class="flex flex-col md:flex-row h-screen md:h-table-and-map p-5 gap-5">
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
                    <th
                      scope="col"
                      class="relative px-6 py-3"
                    >
                      <div class="flex justify-center items-center h-5">
                        <input
                          type="checkbox"
                          class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                          v-model="massSelection"
                          :indeterminate="massSelectionIndeterminated"
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
                    v-for="(source, index) in sources"
                    :key="index"
                    :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                  >
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex justify-center items-center h-5">
                        <input
                          :name="source.name"
                          :value="source"
                          v-model="selected"
                          type="checkbox"
                          class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded disabled:opacity-50"
                          :disabled="source.location ? false : true"
                        />
                        <!-- This example requires Tailwind CSS v2.0+ -->
                        <!-- Enabled: "bg-indigo-600", Not Enabled: "bg-gray-200" -->
                        <!-- <tris-checkbox
                          :state="source.enabled"
                          @state="onElementStateChanged"
                        ></tris-checkbox> -->
                      </div>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ source.id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ source.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ source.template.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ source.template.category.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                      <inertia-link :href="route('objects.sources.show', source.id)">
                        <detail-icon class="text-gray-500 font-medium text-sm w-5"></detail-icon>
                      </inertia-link>
                      <inertia-link :href="route('objects.sources.edit', source.id)">
                        <edit-icon class="text-gray-500 font-medium text-sm w-5"></edit-icon>
                      </inertia-link>
                      <button
                        class="focus:outline-none"
                        @click="onDelete(source.location)"
                      >
                        <trash-icon class="text-red-500 font-medium text-sm w-5"></trash-icon>
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
        <pre>{{ selected }}</pre>
      </div>

      <div class="w-full h-full md:w-1/2">
        <leaflet-map
          :markers="markers"
          ref="map"
        ></leaflet-map>
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
  import TrisCheckbox from "@/Components/Forms/TrisCheckbox";
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
      TrisCheckbox
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

      const uniqueSourceLocations = useUniqueLocations(props.sources);

      // Populate the map with unique locations only.
      for (const _source of uniqueSourceLocations.value) {
        markers.value.push(_source.data);
      }

      const massSelection = ref(true);
      const massSelectionIndeterminated = ref(false);
      const selected = ref([]);

      let allSelectedLength;

      onMounted(() => {
        selectAll();
      });

      function selectAll() {
        // Select (checked) all the applicable sources
        for (const _source of props.sources) {
          if (!_source.location) continue;
          selected.value.push(_source);
        }
        allSelectedLength = selected.value.length;
      }

      function deSelectAll() {
        selected.value = [];
      }

      watch(selected, (current) => {
        if (current.length === allSelectedLength) {
          massSelection.value = true;
          massSelectionIndeterminated.value = false;
          return;
        }

        if (!current.length) {
          massSelectionIndeterminated.value = false;
          return;
        }

        massSelection.value = false;
        massSelectionIndeterminated.value = true;
      });

      watch(massSelection, (current, previous) => {
        if (previous && selected.value.length === allSelectedLength) {
          deSelectAll();
          return;
        }

        if (!previous && !selected.value.length) {
          selectAll();
          return;
        }

        if (current && selected.value.length && selected.value.length !== allSelectedLength) {
          selectAll();
          massSelectionIndeterminated.value = false;
          return;
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

        massSelectionIndeterminated,
        massSelection,
        selected,

        map,
        markers,

        onDelete,
        centerAtLocation,
      };
    },
  };
</script>
