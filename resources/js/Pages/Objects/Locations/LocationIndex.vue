<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Locations
      </h2>
    </template>

    <div class="w-full my-5 px-10 flex justify-end">
      <input
        class="border rounded-full bg-gray-200 border-gray-200 outline-none pl-5 leading-3"
        placeholder="search..."
      />
    </div>

    <div class="w-full px-10 min-h-map">
      <div id="map" style="height: 70vh"></div>
    </div>
    <div class="w-full my-5 px-10 flex justify-end">
      <inertia-link
        as="button"
        :href="route('register')"
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
      >
        Create New Location
      </inertia-link>
    </div>
  </app-layout>
</template>

<script>
import L from "leaflet";
import AppLayout from "@/Layouts/AppLayout";
import JetInput from "@/Jetstream/Input";
import JetLabel from "@/Jetstream/Label";
import JetResponsiveNavLink from "@/Jetstream/ResponsiveNavLink";

export default {
  components: {
    AppLayout,
    JetResponsiveNavLink,
    JetInput,
    JetLabel,
  },
  data() {
    return {
      search: "",
    };
  },
  mounted() {
    const map = L.map("map").setView([51.505, -0.09], 13);
    map.doubleClickZoom.disable();

    const popup = L.popup();

    const fontAwesomeIcon = L.divIcon({
      html: '<i class="fas fa-circle fa-2x"><b>S</b></i>',
      iconSize: [20, 20],
      className: "sourceIcon",
      iconAnchor: [10, 20],
    });

    L.tileLayer(
      "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}",
      {
        attribution:
          'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: "mapbox/streets-v11",
        tileSize: 512,
        zoomOffset: -1,
        accessToken:
          "pk.eyJ1IjoiZGF2aWRzZiIsImEiOiJja2xzbHdtb2cwNDh5MnFuOHU1aGZ4b2E5In0.gbrzRdkK45QXjt5HGp5BZg",
      }
    ).addTo(map);

    const markers = [];

    map.on("dblclick", (e) => {
      const marker = L.marker(e.latlng, {
        draggable: true,
        icon: fontAwesomeIcon,
      }).addTo(map);

      marker.on("move", (e) => {
        popup
          .setLatLng(e.latlng)
          .setContent("Moving to " + e.latlng.toString())
          .openOn(map);
      });

      markers.push(marker);
    });
  },
  methods: {
    create() {
      this.$inertia.get(route("objects.locations.create"));
    },
  },
};
</script>

<style scoped>
.leaflet-popup-close-button {
  display: none;
}

.sourceIcon {
  text-align: center;
  line-height: 20px;
}

.sourceIcon i {
  color: white;
  text-shadow: 0 0 3px #000;
}

.sourceIcon b {
  color: black;
  position: relative;
  left: -50%;
}
</style>
