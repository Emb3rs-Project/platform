<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sources
      </h2>
    </template>

    <div class="flex flex-col md:flex-row h-screen md:h-table-and-map p-5 gap-5">
      <div class="w-full h-96 md:h-full md:w-1/2">
        <index-table
          :headers="headers"
          :items="items"
          @itemAdded="onItemAdded"
          @itemRemoved="onItemRemoved"
          @allItemsAdded="onAllItemsAdded"
          @allItemsRemoved="onAllItemsRemoved"
          @itemDeleted="onItemDeleted"
          @centerAtLocation="onCenterAtLocation"
        >
          <template #header>
            Sources
          </template>
          <template #description>
            A list of all the Sources
          </template>
        </index-table>
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
    <deletion-modal
      v-show="deletionModalIsVisible"
      @deleteEntry="onDelete"
      @close="deletionModalIsVisible = !deletionModalIsVisible"
    >
      <template #header>
        Delete Source
      </template>

      <template #body>
        Are you sure you want to delete this Source? This action is ireversible.
      </template>
    </deletion-modal>
  </app-layout>
</template>

<script>
  import { watch, ref } from "vue";
  import { Inertia } from "@inertiajs/inertia";

  import useUniqueLocations from "@/Composables/useUniqueLocations";

  import AppLayout from "@/Layouts/AppLayout";
  import LeafletMap from "@/Components/LeafletMap";
  import PrimaryLinkButton from "@/Components/PrimaryLinkButton";
  import IndexTable from "@/Components/Tables/IndexTable";

  export default {
    components: {
      AppLayout,
      LeafletMap,
      PrimaryLinkButton,
      IndexTable
    },

    props: {
      sources: {
        type: Array,
        required: true,
      },
    },

    setup(props) {
      const map = ref(null);

      // Intialize the markers with unique locations
      const markers = ref(
        useUniqueLocations(props.sources).value.map((element) => element.data)
      );
      console.log(markers.value);

      const deletionModalIsVisible = ref(false);
      const entryToDelete = ref({});

      const headers = [
        {
          name: 'ID',
          isNumeric: true
        },
        {
          name: 'Name',
          isNumeric: false
        },
        {
          name: 'Template',
          isNumeric: false
        },
        {
          name: 'Category',
          isNumeric: false
        },
        {
          name: 'Location',
          isNumeric: false
        },
      ];
      const items = props.sources.map((item) => {
        return {
          id: {
            name: item.id,
            value: item.id,
            isNumeric: true,
            isLocation: false
          },
          name: {
            name: item.name ? item.name : 'Not assigned',
            value: item.name ? item.name : null,
            isNumeric: false,
            isLocation: false
          },
          template: {
            name: item.template ? item.template.name : 'Not assigned',
            value: item.template ? item.template : null,
            isNumeric: false,
            isLocation: false
          },
          category: {
            name: item.template.category ? item.template.category.name : 'Not assigned',
            value: item.template.category ? item.template.category : null,
            isNumeric: false,
            isLocation: false
          },
          location: {
            name: item.location ? item.location.name : 'Not assigned',
            value: item.location ? item.location : null,
            isNumeric: false,
            isLocation: true
          },
        }
      });

      function onItemRemoved(item) {
        console.log("onItemRemoved", item);
        const marker = markers.value.find((element) => element.id === item.location.value.geo_object.id);

        if (marker) {
          const markerIndex = markers.value.indexOf(marker);
          //   console.log("will delete entry", markerIndex, "from the markers", [...markers.value]);
          markers.value.splice(markerIndex, 1);
        }
        // Because there is a posibility that the removed marker (location)
        // was belonging to another entry that is still selected, we must repopulate
        // the markers(locations) array.

        // In the end, we will end up with the NEW unique locations for the
        // currently selected entries
        // populateMarkers(currentSelections);
      }


      function onItemAdded(item) {
        console.log('onItemAdded', item);
        // const marker = markers.value.find((element) => element.id === _currentSelection.data.id);

        // // This entry's location does not exist in the already displaying
        // // markers (locations). For that reason, we must include it in it
        // if (!marker) {
        //   markers.value.push(_currentSelection.data);
        // }
      }

      function onAllItemsAdded() {
        console.log("onAllItemsAdded");
        populateAllMarkers();
      }

      function onAllItemsRemoved() {
        console.log("allItemsRemoved");
        removeAllMarkers();
      }

      //   function populateMarkers(locations) {
      //     const uniqueLocations = useUniqueLocations(locations);
      //     markers.value = uniqueLocations.value.map((element) => element. );
      //   }

      function populateAllMarkers() {
        markers.value = useUniqueLocations(props.sources).value.map((element) => element.data)
      }

      function removeAllMarkers() {
        markers.value = [];
      }

      function onItemDeleted(entry) {
        entryToDelete.value = entry;
        deletionModalIsVisible.value = true;
      }

      async function onDelete() {
        await Inertia.delete(route("objects.sources.destroy", entryToDelete.value.id));

        // close the modal after the deletion
        deletionModalIsVisible.value = false;
      }

      function onCenterAtLocation(location) {
        if (!markers.value.length) return;

        const marker = markers.value.find((m) => m.id === location.geo_object.id);
        map.value.centerAtLocation(marker);
      }

      return {
        map,
        markers,
        headers,
        items,
        onItemAdded,
        onItemRemoved,
        onAllItemsAdded,
        onAllItemsRemoved,
        onItemDeleted,
        deletionModalIsVisible,
        onCenterAtLocation,
        onDelete
      };
    },
  };
</script>
