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
          v-model="selected"
          :headers="headers"
          :items="items"
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
      const markers = ref([]);

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

      const selected = ref(
        // We can use .filter() here instead of .flatMap() and apply the .map() later (i.e. .filter().map())
        // it depends on the developer's writting style
        items.flatMap((item) => {
          if (item.location.name === 'Not assigned') return [];
          return item;
        })
      );

      watch(selected, (current, previous) => {
        console.log("PARENT CURRENT selcted", current);
        console.log("PARENT PREVIOUS selcted", previous);
      })

      const deletionModalIsVisible = ref(false);
      const entryToDelete = ref({});

      function populateMarkers(locations) {
        const uniqueLocations = useUniqueLocations(locations);
        markers.value = uniqueLocations.value.map((element) => element.data);
      }

      function removeMarkers() {
        markers.value = [];
      }

      function deleteEntry(entry) {
        entryToDelete.value = entry;
        deletionModalIsVisible.value = true;
      }

      async function onDelete() {
        await Inertia.delete(route("objects.sources.destroy", entryToDelete.value.id));

        // close the modal after the deletion
        deletionModalIsVisible.value = false;
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
        headers,
        items,
        deletionModalIsVisible,
        centerAtLocation,
        deleteEntry,
        onDelete
      };
    },
  };
</script>
