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
                          :value="source"
                          v-model="selected"
                          type="checkbox"
                          class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded disabled:opacity-50"
                          :disabled="source.location ? false : true"
                        />
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
        <pre>{{markers}}</pre>
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
  import { watch, ref, onBeforeMount, onMounted, onRenderTriggered, onRenderTracked } from "vue";
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
      DetailIcon
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
      const massSelection = ref(true);
      const massSelectionIndeterminated = ref(false);
      const selected = ref([]);
      let allSelectedLength;

      selectAll();

      // Select (checked) all the applicable sources
      function selectAll() {
        const temp = [];
        for (const _source of props.sources) {
          if (!_source.location) continue;
          temp.push(_source);
        }

        selected.value = temp;
        populateMarkers(selected.value);

        allSelectedLength = selected.value.length;
      }

      function deSelectAll() {
        selected.value = [];
        removeMarkers();
      }

      watch(selected, (current, previous) => {
        // An iteam was removed from the selected entries
        if (current.length < previous.length) {
          // Since we "touched" the selected items, on the next mass selection
          // user action, we must select them all
          massSelection.value = false;

          // If the removed item was the last selected item, update mass action
          // to be empty.
          // Else, update mass action to the indeterminated state.
          if (!current.length) {
            massSelectionIndeterminated.value = false;
          } else {
            massSelectionIndeterminated.value = true;
          }

          // Remove the entry from the selected items
          removeEntry(current, previous);
          return;
        }

        // An item was added to the selected entries
        if (current.length > previous.length) {
          // If the added item was the last selected item for array fulliness,
          // update mass action to be selected.
          // Else, update mass action to the indeterminated state.
          if (current.length === allSelectedLength) {
            massSelection.value = true;
            massSelectionIndeterminated.value = false;
          } else {
            massSelection.value = false;
            massSelectionIndeterminated.value = true;
          }

          // Add the entry to the selected items
          addEntry(current, previous);
          return;
        }
      });

      watch(massSelection, (current, previous) => {
        // All the entries are selected, so, if pressesed, deselect them all.
        if (previous && selected.value.length === allSelectedLength) {
          deSelectAll();
          return;
        }

        if (!previous && !selected.value.length) {
          selectAll();
          return;
        }

        if (current && selected.value.length && selected.value.length !== allSelectedLength) {
          massSelectionIndeterminated.value = false;
          selectAll();
          return;
        }
      });

      function populateMarkers(locations) {
        const uniqueLocations = useUniqueLocations(locations);
        markers.value = uniqueLocations.value.map((element) => element.data);
      }

      function removeMarkers() {
        markers.value = [];
      }

      function removeEntry(currentSelections, previousSelections) {
        // we must find the removed entry and check if its location already exists in the markers array
        // if it does, remove it
        // if it does not, do nothing
        for (const _previousSelection of previousSelections) {
          const entry = currentSelections.find((element) => element.id === _previousSelection.id);

          // Current (_previousSelection) entry does not exist in the currentSelections, which means, that
          // this entry was removed from the array of selected entries.
          // For that reason, we must remove its marker (location) from the map.
          if (!entry) {
            const marker = markers.value.find((element) => element.id === _previousSelection.data.id);

            if (marker) {
              const markerIndex = markers.value.indexOf(marker);
              //   console.log("will delete entry", markerIndex, "from the markers", [...markers.value]);
              markers.value.splice(markerIndex, 1);
            }
            // we found the element that was removed, so stop the itteration
            break;
          }
        }
        // Because there is a posibility that the removed marker (location)
        // was belonging to another entry that is still selected, we must repopulate
        // the markers(locations) array.
        //
        // In the end, we will end up with the NEW unique locations for the
        // currently selected entries
        populateMarkers(currentSelections);
      }

      function addEntry(currentSelections, previousSelections) {
        // We must find the new entry and check if its location already exists in the markers array
        // if it does, do nothing
        // if it does not, add it
        for (const _currentSelection of currentSelections) {
          const entry = previousSelections.find((element) => element.id === _currentSelection.id);

          // Current (_currentSelection) entry does not exist in the previousSelections, which means, that
          // this entry was added to the array of selected entries.
          // For that reason, we must add its marker (location) to the map.
          if (!entry) {
            const marker = markers.value.find((element) => element.id === _currentSelection.data.id);

            // This entry's location does not exist in the already displaying
            // markers (locations). For that reason, we must include it in it
            if (!marker) {
              markers.value.push(_currentSelection.data);
            }

            // we found the element that was added, so stop the itteration
            break;
          }
        }
      }

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
        massSelection,
        massSelectionIndeterminated,
        selected,
        onDelete,
        centerAtLocation,
      };
    },
  };
</script>
