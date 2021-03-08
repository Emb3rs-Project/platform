<template>
    <div class="w-full h-full">
        <div
            id="map"
            class="h-full w-full"
        ></div>
    </div>
</template>

<script>
import L from "leaflet";

export default {
    mounted() {
        const map = L.map("map").setView([51.505, -0.09], 13);
        map.doubleClickZoom.disable();

        const popup = L.popup();

        const fontAwesomeIcon = L.divIcon({
            html: '<i class="fas fa-circle fa-2x"><b>S</b></i>',
            iconSize: [20, 20],
            className: "sourceIcon",
            iconAnchor: [10, 20],
        });

        L.tileLayer(
            "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}",
            {
                attribution:
                    'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: "mapbox/streets-v11",
                tileSize: 512,
                zoomOffset: -1,
                accessToken:
                    "pk.eyJ1IjoiZGF2aWRzZiIsImEiOiJja2xzbHdtb2cwNDh5MnFuOHU1aGZ4b2E5In0.gbrzRdkK45QXjt5HGp5BZg",
            }
        ).addTo(map);

        const markers = [];

        map.on("dblclick", (e) => {
            const marker = L.marker(e.latlng, {
                draggable: true,
                icon: fontAwesomeIcon,
            }).addTo(map);

            marker.on("move", (e) => {
                popup
                    .setLatLng(e.latlng)
                    .setContent("Moving to " + e.latlng.toString())
                    .openOn(map);
            });

            markers.push(marker);
        });
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
    color: white;
    text-shadow: 0 0 3px #000;
}

.sourceIcon b {
    color: black;
    position: relative;
    left: -50%;
}
</style>
