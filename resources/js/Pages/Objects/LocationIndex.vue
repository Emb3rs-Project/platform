<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard teste 4
      </h2>
    </template>
    <div class="max-w-2xl w-full mx-auto min-h-map">
      <div id="map" style="height: 70vh"></div>
    </div>
  </app-layout>
</template>

<script>
import L from "leaflet";
import AppLayout from "@/Layouts/AppLayout";

export default {
  components: {
    AppLayout,
  },
  data() {
    return {};
  },
  mounted() {
    var map = L.map("map").setView([51.505, -0.09], 13);
    map.doubleClickZoom.disable();
    var popup = L.popup();

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
      var marker = L.marker(e.latlng, {
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
};
</script>


<style>
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
