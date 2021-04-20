import L from 'leaflet';

export default {
    fontAwesomeIcon: L.divIcon({
        html: '<i class="fas fa-industry fa-2x"></i>',
        iconSize: [20, 20],
        className: "sourceIcon",
        iconAnchor: [10, 20],
    }),
    init(mapId, center = [38.7181959, -9.1975417], options = { drawControl: true }) {
        const map = L.map(mapId, options).setView(center, 13);
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
    destroy(mapId) {
        // maybe we need to distroy the map
    },
    loadMarkers(map, markers = [], mapObjects = {}) {
        for (const marker of markers) {
            const type = marker.type;
            const data = marker.data;
            switch (type) {
                case "circle":
                    const circle = L.circle(data.center, {
                        color: 'red',
                        fillColor: 'red',
                        fillOpacity: 0.2,
                        radius: data.radius
                    }).addTo(map)
                    mapObjects[data.id] = circle
                    break;
                case "polygon":
                    const polygon = L.polygon(data.points, {
                        color: 'blue',
                        fillColor: 'blue',
                        fillOpacity: 0.5,
                    }).addTo(map)
                    mapObjects[data.id] = polygon
                    break;
                case "point":
                    const point = L.marker(data.center, {
                        icon: this.fontAwesomeIcon
                    }).addTo(map)
                    mapObjects[data.id] = point
                    break;
            }
        }

        return mapObjects;
    },
    removeMarkers(map, markers) {
        for (const marker of markers) {
            const type = marker.type;
            const data = marker.data;
            switch (type) {
                case "circle":
                    console.log('TODO: Remove Circle', data.center)
                    break;
                case "polygon":
                    console.log('TODO: Remove Polygon', data.points)
                    break;
                case "point":
                    console.log('TODO: Remove Point', data.center)
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
    },
    addPoint(map, center, { icon = 'leaf', textClass = 'text-green-700', borderClass = 'border-green-700', draggable = false } = {}) {
        const iconOptions = {
            icon,
            textColor: null,
            borderColor: null,
            iconShape: "marker",
            customClasses: [textClass, borderClass].join(" ") + ""
        };


        return L.marker(L.latLng(center), {
            icon: L.BeautifyIcon.icon(iconOptions),
            draggable,
        })
            .addTo(map);
    },
    addInstances(map, instances = [], mapObjects = {}) {
        for (let instance of instances.filter((i) => i.location)) {

            // Skipping Locations with areas
            if (instance.location.geo_object.type !== 'point') continue;

            const type = instance.template.category.type
            const center = instance.location.geo_object.data.center

            const iconOptions = {
                icon: '',
                textClass: '',
                borderClass: '',
                draggale: false
            }



            switch (type) {
                case 'sink':
                    iconOptions.icon = 'leaf'
                    iconOptions.textClass = 'text-green-700'
                    iconOptions.borderClass = 'border-green-700'
                    break;
                case 'source':
                    iconOptions.icon = 'fire'
                    iconOptions.textClass = 'text-red-700'
                    iconOptions.borderClass = 'border-red-700'
                    break;
            }

            mapObjects[instance.id] = this.addPoint(map, center, iconOptions)
        }
    }
}

// addPoint(map, center , options)
//


// addPoint(map, center, {icon, textClass, borderClass, draggable} = {icon = 'leaf', textClass = 'text-green-700', borderClass = 'border-green-700', draggable = false })
// appPoint(map, [])
// options = null
// icon = 'leaf', textClass = 'text-green-700', borderClass = 'border-green-700', draggable = false

// addPoint(map, [], { icon : 'fire' })
// options = {icon : 'fire'}
// icon = 'fire', textClass = null, borderClass = null, draggable = null


// addPoint(map, center, _options) {
//     const options = Object.assign( {icon : 'leaf', textClass : 'text-green-700', borderClass : 'border-green-700', draggable : false }, _options)
//     const iconOptions = {
//         icon : options.icon,
//         textColor: null,
//         borderColor: null,
//         iconShape: "marker",
//         customClasses: [options.textClass, options.borderClass].join(" ")
//     };


//     return L.marker(L.latLng(center), {
//         icon: L.BeautifyIcon.icon(iconOptions),
//         draggable : options.draggable,
//     }).addTo(map);
// }
