<template>
    <div class="w-full h-full">
        <div id="map" class="h-full w-full z-0"></div>
    </div>
</template>

<script>
import L from "leaflet";
import "beautifymarker";
import "leaflet-contextmenu";
// CSS for Markers
import "beautifymarker/leaflet-beautify-marker-icon.css";
import "leaflet-contextmenu/dist/leaflet.contextmenu.min.css";

import mapUtils from "@/Utils/map.js";
import { onMounted, ref, toRefs, watch } from "vue";

export default {
    props: {
        instances: {
            type: Array,
            required: true,
        },
        links: {
            type: Array,
            required: true,
        },
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
        const instances = ref(
            props.instances.map((i) => ({
                ...i,
                selected: true,
            }))
        );
        const links = ref(
            props.links.map((i) => ({
                ...i,
                selected: true,
            }))
        );

        onMounted(() => {
            map.value = mapUtils.init("map", [38.7181959, -9.1975417], 13, {
                scrollWheelZoom: false,
                dragging: false,
                zoomControl: false
            });
            mapUtils.addInstances(map.value, instances.value, mapObjects);
            mapUtils.addLinks(map.value, links.value, mapObjects);
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
</style>

<style scoped>
#map {
    min-height: 70vh;
}
</style>
