<template>
  <div class="w-full h-full">
    <div id="map" class="h-full w-full"></div>
  </div>
</template>

<script>
import L from "leaflet";
import mapUtils from "@/Utils/map.js";
import { computed, onMounted, ref, watch } from "vue";

export default {
  props: {
    modelValue: {
      type: Object,
      required: true,
    },
  },
  setup(props, { emit }) {
    const map = ref();
    const mapObjects = ref([]);
    const data = computed({
      get() {
        return props.modelValue;
      },
      set(v) {
        emit("update:modelValue", v);
      },
    });

    data.value = {
      segments: [],
      segmentIndex: 0,
    };

    return {
      map,
      data,
      mapObjects,
    };
  },
  emits: ["update:modelValue"],
  methods: {
    onMapDoubleClick({ latlng }) {
      const { segments, segmentIndex } = this.data;

      if (segments[segmentIndex])
        segments[segmentIndex].to = [latlng.lat, latlng.lng];
      else {
        segments[segmentIndex] = {
          from: [latlng.lat, latlng.lng],
        };
      }

      if (segments[segmentIndex].to) {
        const line = L.polyline(
          [segments[segmentIndex].from, segments[segmentIndex].to],
          {
            color: "green",
          }
        ).addTo(this.map);

        this.mapObjects.push(line);
        this.data.segmentIndex++;
        // line.on('contextmenu', () => this.map.removeLayer(line))
      }
    },
  },
  mounted() {
    if (!this.map) {
      this.map = mapUtils.init("map");
      this.map.on("dblclick", (e) => this.onMapDoubleClick(e));
    }
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
