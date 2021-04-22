<template>
  <div id="map" class="h-screen min-w-full z-0"></div>
</template>

<script>
import mapUtils from "@/Utils/map.js";
import { computed, onMounted, ref } from "@vue/runtime-core";
import L from "leaflet";
import "beautifymarker";
import "leaflet-contextmenu";

// CSS for Markers
import "beautifymarker/leaflet-beautify-marker-icon.css";
import "leaflet-contextmenu/dist/leaflet.contextmenu.min.css";

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
  emits: ["onMove", "onCreateRequest"],
  setup(props, { emit }) {
    const map = ref(null);
    const mapObjects = ref({
      sources: null,
      sinks: null,
      links: null,
    });

    const center = computed({
      get() {
        return props.centerValue;
      },
      set(value) {
        emit("onMove", value);
      },
    });

    const onCreateSink = (val) =>
      emit("onCreateRequest", {
        type: "sink",
        center: [val.latlng.lat, val.latlng.lng],
      });

    const onCreateSource = (val) =>
      emit("onCreateRequest", {
        type: "source",
        center: [val.latlng.lat, val.latlng.lng],
      });

    const onCreateLink = (vale) => {
      mapUtils.removeAllInstances(map.value, mapObjects.value);
    };

    onMounted(() => {
      map.value = mapUtils.init("map", center.value, {
        drawControl: true,
        contextmenu: true,
        contextmenuWidth: 140,
        contextmenuItems: [
          {
            text: "Create Sink Here",
            callback: onCreateSink,
          },
          {
            text: "Create Source Here",
            callback: onCreateSource,
          },
          "-",
          {
            text: "Start Link Creation",
            callback: onCreateLink,
          },
          {
            text: "Zoom in",
            callback: (o) => map.value.zoomIn(),
          },
          {
            text: "Zoom out",
            callback: (o) => map.value.zoomOut(),
          },
        ],
      });
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
