<template>
  <div class="w-full h-full">
    <div id="map" class="h-full w-full z-0"></div>
  </div>
</template>

<script>
import L from "leaflet";
import mapUtils from "@/Utils/map.js";
import { onMounted, ref, toRefs, watch } from "vue";

export default {
  props: {
    markers: {
      type: Array,
      required: true,
    },
    marker: {
      type: Object,
      required: false,
    },
  },

  setup(props) {
    const map = ref();
    const mapObjects = ref({});

    onMounted(() => {
      map.value = mapUtils.init("map");
      mapUtils.loadMarkers(map.value, props.markers, mapObjects);
      window.map = map.value;
    });

    watch(
      () => props.markers,
      (value) => {
        console.log(props.markers);
        // console.log("Map received those new markers", current);
        // console.log("Map must remove old markers", previous);
        // mapUtils.removeMarkers(map.value, previous);
        // mapUtils.loadMarkers(map.value, current);
      }
    );

    function centerAtLocation(location) {
      mapUtils.centerAtLocation(this.map, location);
    }

    return {
      map,
      centerAtLocation,
      mapObjects,
    };
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
  color: green;
  text-shadow: 0 0 3px #000;
}

.sourceIcon b {
  color: black;
  position: relative;
  left: -50%;
}

#map {
  min-height: 70vh;
}
</style>
