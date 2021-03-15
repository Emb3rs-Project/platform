<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Locations
      </h2>
    </template>

    <div class="flex p-5 h-screen md:h-content gap-2">
      <div class="w-6/12 md:overflow-y-auto">
        <div class="overflow-x bg-red-500">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Name
                </th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(i, index) in locations"
                :key="index"
                :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                class="hover:bg-gray-300 hover:text-white pt-3 pb-3 rounded"
              >
                <!-- bg-white is on odd rows -->
                <!-- bg-gray is on even rows -->

                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                  @click="centerAtLocation(i)"
                >
                  {{ i.name }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2"
                >
                  <a href="#">
                    <edit-icon
                      class="text-gray-600 font-medium text-sm w-5"
                    ></edit-icon>
                  </a>
                  <a href="#" @click="onDelete(i)">
                    <trash-icon
                      class="text-gray-600 font-medium text-sm w-5"
                    ></trash-icon>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- </div> -->
          <!-- </div> -->
        </div>
        <!-- </div> -->
      </div>
      <div class="w-6/12">
        <leaflet-map :markers="markers" ref="map"></leaflet-map>
      </div>
    </div>

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
import TrashIcon from "@/Icons/TrashIcon.vue";
import EditIcon from "@/Icons/EditIcon.vue";
import LeafletMap from "@/Components/LeafletMap";
import { ref } from "@vue/reactivity";

export default {
  components: {
    AppLayout,
    JetInput,
    JetLabel,
    JetLinkButton,
    LeafletMap,
    TrashIcon,
    EditIcon,
  },
  props: {
    locations: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const markers = ref([]);

    for (const loc of props.locations) {
      markers.value.push(loc.geo_object);
    }

    return { markers };
  },

  methods: {
    onDelete(sink) {
      this.$inertia.delete(route("objects.locations.destroy", sink.id));
    },
    centerAtLocation(location) {
      const marker = this.markers.find((m) => m.id === location.geo_object.id);
      this.$refs.map.centerAtLocation(marker);
    },
  },
};
</script>

<style scoped>
</style>
