<template>
  <div class="w-full h-full">
    <div id="map" class="h-full w-full"></div>
  </div>
</template>

<script>
import L from "leaflet";
import mapUtils from "@/Utils/map.js";
import { onMounted, ref, watch } from "vue";

export default {
  props: {
    marker: {
      type: Object,
      required: false,
    },
    markerType: {
      type: String,
      required: false,
    },
  },
  setup(props) {
    const map = ref();
    const mapObjects = ref();
    const data = ref({});

    onMounted(() => {
      map.value = mapUtils.init("map");
      window.map = map.value;
    });

    watch(
      () => props.markerType,
      (v) => (data.value = {})
    );

    return {
      map,
      mapObjects,
      data,
    };
  },
  methods: {
    centerAtLocation(location) {
      mapUtils.centerAtLocation(this.map, location);
    },
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
