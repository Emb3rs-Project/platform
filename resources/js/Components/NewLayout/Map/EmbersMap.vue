<template>
  <div
    id="map"
    class="h-screen min-w-full z-0"
  ></div>
</template>

<script>
  import mapUtils from "@/Utils/map.js";
  import { onMounted, ref, watch } from "vue";

  export default {
    props: {
      markers: {
        type: Array,
        required: true,
      },
    },

    setup(props) {
      const map = ref(null);
      const mapObjects = ref({});

      onMounted(() => {
        map.value = mapUtils.init("map");
        mapObjects.value = mapUtils.loadMarkers(map.value, props.markers, mapObjects.value);
        window.map = map.value;
        console.log(mapObjects.value);
      });

      watch(
        () => props.markers,
        (value) => {
          //   console.log(props.markers);

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

<style >
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
</style>
