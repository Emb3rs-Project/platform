import L from 'leaflet';

export default {
  fontAwesomeIcon: L.divIcon({
    html: '<i class="fas fa-industry fa-2x"></i>',
    iconSize: [20, 20],
    className: "sourceIcon",
    iconAnchor: [10, 20],
  }),
  init(mapId, center, zoom, options = { drawControl: true }) {
    const map = L.map(mapId, options).setView(center, zoom);

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
  destroy(mapId) { },
  // TODO: This is redundant but keep if for now
  // loadMarkers(map, markers = [], mapObjects = {}) {
  //   for (const marker of markers) {
  //     const type = marker.type;
  //     const data = marker.data;
  //     switch (type) {
  //       case "circle":
  //         const circle = L.circle(data.center, {
  //           color: 'red',
  //           fillColor: 'red',
  //           fillOpacity: 0.2,
  //           radius: data.radius
  //         }).addTo(map)
  //         mapObjects[data.id] = circle
  //         break;
  //       case "polygon":
  //         const polygon = L.polygon(data.points, {
  //           color: 'blue',
  //           fillColor: 'blue',
  //           fillOpacity: 0.5,
  //         }).addTo(map)
  //         mapObjects[data.id] = polygon
  //         break;
  //       case "point":
  //         const point = L.marker(data.center, {
  //           icon: this.fontAwesomeIcon
  //         }).addTo(map)
  //         mapObjects[data.id] = point
  //         break;
  //     }
  //   }

  //   return mapObjects;
  // },
  // TODO: This is redundant but keep if for now
  // removeMarkers(map, markers) {
  //   for (const marker of markers) {
  //     const type = marker.type;
  //     const data = marker.data;
  //     switch (type) {
  //       case "circle":
  //         console.log('TODO: Remove Circle', data.center)
  //         break;
  //       case "polygon":
  //         console.log('TODO: Remove Polygon', data.points)
  //         break;
  //       case "point":
  //         console.log('TODO: Remove Point', data.center)
  //         break;
  //     }
  //   }
  // },
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
  addPoint(map, center, { icon = 'leaf', textClass = 'text-green-700', borderClass = 'border-green-700', draggable = false, type = 'sink' } = {}) {
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
      contextmenu: true,
      contextmenuWidth: 140,
      contextmenuItems: [],
      defaultTextClass: textClass,
      defaultBorderClass: borderClass,
      instanceType: type
    }).addTo(map);
  },
  addCircle(map, center) {
    return L.circle(center, {
      color: 'red',
      fillColor: 'red',
      fillOpacity: 0.2,
      radius: 5
    }).addTo(map)
  },
  addSegment(map, from, to, context) {
    const polyline = L.polyline([from, to], {
      color: 'green'
    }).addTo(map);

    polyline.bindContextMenu({
      contextmenu: true,
      contextmenuWidth: 140,
      contextmenuItems: context(polyline)
    })

    return polyline;
  },
  addInstances(map, instances = [], mapObjects = {
    sources: null,
    sinks: null,
    links: null,
    all: null,
    layerControl: null
  }, onMarkerClick = () => { }) {
    const sources = []
    const sinks = []
    const all = []

    console.log(instances.length);

    for (let instance of instances.filter((i) => i.location && i.selected)) {

      // Skipping Locations with areas
      if (instance.location.type !== 'point') continue;

      const type = instance.template.category.type
      const center = instance.location.data.center

      const iconOptions = {
        icon: '',
        textClass: '',
        borderClass: '',
        draggale: false
      }

      switch (type) {
        case 'source':
          // console.log('MAP::addInstances -> Added a source.', instance);
          iconOptions.icon = 'fire';
          iconOptions.textClass = 'text-red-700';
          iconOptions.borderClass = 'border-red-700';
          iconOptions.type = 'source';
          sources.push(this.addPoint(map, center, iconOptions).on("mousedown", () => onMarkerClick(instance)));
          break;
        case 'sink':
          // console.log('MAP::addInstances -> Added a sink.', instance);
          iconOptions.icon = 'leaf';
          iconOptions.textClass = 'text-green-700';
          iconOptions.borderClass = 'border-green-700';
          iconOptions.type = 'sink';
          sinks.push(this.addPoint(map, center, iconOptions).on("mousedown", () => onMarkerClick(instance)));
          break;
      }
    }

    mapObjects.sources = L.layerGroup(sources);
    mapObjects.sinks = L.layerGroup(sinks);
    mapObjects.all = L.layerGroup([...sinks, ...sources]);
  },
  createIconOptions(type, inFocus = false) {
    const iconOptions = {
      icon: '',
      textClass: '',
      borderClass: '',
      textColor: null,
      borderColor: null,
      draggale: false,
      iconShape: "marker",
    }

    switch (type) {
      case 'sink':
        iconOptions.icon = 'leaf'
        iconOptions.textClass = 'text-green-700'
        iconOptions.borderClass = 'border-green-700'
        iconOptions.type = 'sink'
        break;
      case 'source':
        iconOptions.icon = 'fire'
        iconOptions.textClass = 'text-red-700'
        iconOptions.borderClass = 'border-red-700'
        iconOptions.type = 'source'
        break;
    }

    if (inFocus) {
      iconOptions.textClass = 'text-yellow-500'
      iconOptions.borderClass = 'border-yellow-500'
    }

    iconOptions.customClasses = [iconOptions.textClass, iconOptions.borderClass].join(" ") + ""

    return iconOptions
  },
  removeAllInstances(map, mapObjects = {
    sources: null,
    sinks: null,
    links: null,
    all: null,
    layerControl: null
  }) {
    const sources = mapObjects.sources?.getLayers() ?? [];
    const sinks = mapObjects.sinks?.getLayers() ?? [];

    for (const _sourceLayer of sources) {
      map.removeLayer(_sourceLayer);
    }

    for (const _sinkLayer of sinks) {
      map.removeLayer(_sinkLayer);
    }

    mapObjects.layerControl?.removeFrom(map);

    mapObjects = {
      all: null,
      sources: null,
      sinks: null,
      links: null,
      layerControl: null
    }
  },
  focusMarker(map, marker, mapObjects) {
    const allMarkers = mapObjects.all?.getLayers();

    for (const _m of allMarkers) {
      const _opt = this.createIconOptions(_m.options.instanceType)
      _m.setIcon(L.BeautifyIcon.icon(_opt))
    }

    if (!!marker) {
      const iconOptions = this.createIconOptions(marker.options.instanceType, true)
      const _icon = L.BeautifyIcon.icon(iconOptions)

      marker.setIcon(_icon)
    }
  },
  getInstancesInView(map, instances) {
    const instancesInView = [];

    for (const _instance in instances) {
      const instanceLatLng = L.latLng(
        instances[_instance].location?.data?.center[0],
        instances[_instance].location?.data?.center[1]
      );

      if (map.getBounds().contains(instanceLatLng)) {
        instancesInView.push(instances[_instance]);
      }

    }

    return instancesInView;
  }
}
