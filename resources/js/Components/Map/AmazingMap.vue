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
    instances: {
      type: Array,
      default: [],
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

      map.value.on(
        "moveend",
        ({ target }) => (center.value = target.getCenter())
      );

      if (props.instances?.length > 0) {
        mapUtils.addInstances(map.value, props.instances, mapObjects.value);
      }
    });

    return {
      center,
    };
  },
};
</script>

<style>
</style>
