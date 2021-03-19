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
    marker: {
      type: Object,
      required: false,
    },
    markerType: {
      type: String,
      required: false,
    },
    modelValue: {
      type: Object,
      required: true,
    },
    radius: {
      type: Number,
      required: true,
    },
  },
  setup(props, { emit }) {
    const map = ref();
    const mapObjects = ref();
    const data = computed({
      get() {
        return props.modelValue;
      },
      set(v) {
        emit("update:modelValue", v);
      },
    });
    const currentObject = ref({});

    watch(
      () => props.markerType,
      (v) => {
        data.value = {};
      }
    );

    watch(
      () => props.radius,
      (v) => {
        if (currentObject.value.obj) {
          currentObject.value.obj.setRadius(v);
        }
      }
    );

    const centerAtLocation = (location) =>
      mapUtils.centerAtLocation(map.value, location);

    return {
      map,
      mapObjects,
      data,
      centerAtLocation,
      currentObject,
    };
  },
  emits: ["update:modelValue"],
  methods: {
    onMapDoubleClick({ latlng }) {
      if (this.markerType !== this.currentObject.type) {
        this.currentObject.type = this.markerType;
        if (this.currentObject.obj) {
          this.map.removeLayer(this.currentObject.obj);
          this.currentObject.obj = null;
        }
      }

      const { obj } = this.currentObject;
      let created = !obj;

      switch (this.markerType) {
        case "circle":
          this.data.center = [latlng.lat, latlng.lng];
          if (obj) obj.setLatLng(latlng);
          else
            this.currentObject.obj = L.circle(latlng, {
              color: "red",
              fillColor: "red",
              fillOpacity: 0.2,
              radius: this.radius,
            }).addTo(this.map);

          break;
        case "point":
          this.data.center = [latlng.lat, latlng.lng];
          if (obj) obj.setLatLng(latlng);
          else this.currentObject.obj = L.marker(latlng).addTo(this.map);
          break;
        case "polygon":
          if (this.data.points) this.data.points.push([latlng.lat, latlng.lng]);
          else this.data.points = [[latlng.lat, latlng.lng]];

          if (this.data.points.length > 1) {
            if (obj) obj.addLatLng(latlng);
            else
              this.currentObject.obj = L.polygon(this.data.points, {
                color: "blue",
                fillColor: "blue",
                fillOpacity: 0.5,
              }).addTo(this.map);
            break;
          }
      }

      if (created)
        this.currentObject.obj.on("contextmenu", () => {
          this.map.removeLayer(this.currentObject.obj);
          this.currentObject.obj = null;
          this.data = {};
        });
    },
  },
  mounted() {
    this.map = mapUtils.init("map");
    this.map.on("dblclick", (e) => this.onMapDoubleClick(e));
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
