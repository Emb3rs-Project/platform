import L from "leaflet";

export default {
    fontAwesomeIcon: L.divIcon({
        html: '<i class="fas fa-circle fa-2x"><b>S</b></i>',
        iconSize: [20, 20],
        className: "sourceIcon",
        iconAnchor: [10, 20],
    }),
    init(mapId, center = [38.7181959, -9.1975417]) {
        const map = L.map(mapId).setView(center, 13);
        map.doubleClickZoom.disable();

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


        return map;
    },
    loadMarkers(map, markers = []) {

        const objects = []


        for (const { type, data } of markers) {
            switch (type) {
                case "circle":
                    objects.push(L.circle(data.center, {
                        color: 'red',
                        fillColor: 'red',
                        fillOpacity: 0.2,
                        radius: data.radius
                    }).addTo(map))
                    console.log('Added Circle', data.center)
                    break;
                case "polygon":
                    objects.push(L.polygon(data.points, {
                        color: 'blue',
                        fillColor: 'blue',
                        fillOpacity: 0.5,
                    }).addTo(map))
                    console.log('Added Polygon', data.points)
                    break;
                case "point":
                    objects.push(L.marker(data.center, {
                        icon: this.fontAwesomeIcon
                    }).addTo(map))
                    console.log('Added Point', data.center)
                    break;
            }
        }

        return objects;

    }
}



// const _markers = [];

// map.on("dblclick", (e) => {
//   const marker = L.marker(e.latlng, {
//     draggable: true,
//     icon: fontAwesomeIcon,
//   }).addTo(map);

//   marker.on("move", (e) => {
//     popup
//       .setLatLng(e.latlng)
//       .setContent("Moving to " + e.latlng.toString())
//       .openOn(map);
//   });

//   _markers.push(marker);
// });

// // for (var mark of this.markers) {
// //   const point = mark.data.points[0];

// //   const marker = L.marker(point, {
// //     draggable: true,
// //     icon: fontAwesomeIcon,
// //   }).addTo(map);
// // }
