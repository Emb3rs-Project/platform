<template>
  <div id="map" class="h-screen min-w-full z-0"></div>
</template>

<script>
import mapUtils from "@/Utils/map.js";
import { computed, onMounted, ref } from "@vue/runtime-core";
import L from "leaflet";
import "beautifymarker";

// CSS for Markers
import "beautifymarker/leaflet-beautify-marker-icon.css";

export default {
  props: {
    centerValue: {
      type: Array,
    },
  },
  emits: ["onMove"],
  setup(props, { emit }) {
    const map = ref(null);
    const mapObjects = ref({});

    const center = computed({
      get() {
        return props.centerValue;
      },
      set(value) {
        emit("onMove", value);
      },
    });

    onMounted(() => {
      map.value = mapUtils.init("map", center.value);
      window.map = map.value;

      map.value.on("moveend", ({ target }) => console.log(target.getCenter()));

      mapUtils.addPoint(map.value, center.value);

      mapUtils
        .addPoint(map.value, center.value, {
          icon: "leaf",
          textClass: "text-green-700",
          borderClass: "border-green-700",
          draggable: true,
        })
        .bindPopup(`${center.value}`);

      mapUtils.addPoint(map.value, center.value, {
        icon: "fire",
        textClass: "text-red-700",
        borderClass: "border-red-700",
        draggable: true,
      });
    });

    return {
      center,
    };
  },
};
</script>

<style>
</style>
