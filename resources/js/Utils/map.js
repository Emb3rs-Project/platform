import L from "leaflet";

export default {
    fontAwesomeIcon: L.divIcon({
        html: '<i class="fas fa-industry fa-2x"></i>',
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



        for (const marker of markers) {
            const type = marker.type;
            const data = marker.data;
            switch (type) {
                case "circle":
                    L.circle(data.center, {
                        color: 'red',
                        fillColor: 'red',
                        fillOpacity: 0.2,
                        radius: data.radius
                    }).addTo(map)
                    console.log('Added Circle', data.center)
                    break;
                case "polygon":
                    L.polygon(data.points, {
                        color: 'blue',
                        fillColor: 'blue',
                        fillOpacity: 0.5,
                    }).addTo(map)
                    console.log('Added Polygon', data.points)
                    break;
                case "point":
                    L.marker(data.center, {
                        icon: this.fontAwesomeIcon
                    }).addTo(map)
                    console.log('Added Point', data.center)
                    break;
            }
        }


    },
    loadLinks(map, markers = []) {
        for (const marker of markers) {
            if (marker.to)
                L.polyline([marker.from, marker.to], { color: 'green' }).addTo(map);
        }

    },
    centerAtLocation(map, { type, data }) {
        switch (type) {
            case "circle":
                map.flyTo(data.center);
                break;
            case "polygon":
                map.flyTo(data.points[0])
                break;
            case "point":
                map.flyTo(data.center);
                break;
        }
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
