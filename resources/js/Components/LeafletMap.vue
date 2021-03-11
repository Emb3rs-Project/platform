<template>
  <div class="w-full h-full">
    <div id="map" class="h-full w-full"></div>
  </div>
</template>

<script>
import L from "leaflet";
import mapUtils from "@/Utils/map.js";
import { onMounted, ref } from "@vue/runtime-core";

export default {
  props: {
    markers: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const map = ref();
    const mapObjects = ref();

    onMounted(() => {
      map.value = mapUtils.init("map");
      mapObjects.value = mapUtils.loadMarkers(map.value, props.markers);
    });

    return {
      map,
      mapObjects,
    };
  },
  mounted() {},
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
