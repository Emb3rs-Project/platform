import L from 'leaflet';

export default {
  init(id = 'map', center = [38.7181959, -9.1975417], zoom = 13, options = { drawControl: true }) {
    const map = L.map(id, options).setView(center, zoom);

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
  addPoint(map, center, {
    icon = 'leaf',
    textClass = 'text-green-700',
    borderClass = 'border-green-700',
    draggable = false,
    type = 'sink'
  } = {}) {
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
      objectType: type
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
    console.log('MapUtils::addSegment', from, to, context);
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
  addInstances(map, instances = [], mapObjects = { sources: null, sinks: null, links: null }, onClick = () => { }) {
    const sources = [];
    const sinks = [];

    for (const _instance of instances.filter((i) => i.location && i.selected)) {
      // Skipping Locations with areas
      if (_instance.location.type !== 'point') continue;

      const type = _instance.template.category.type
      const center = _instance.location.data.center

      switch (type.toLowerCase()) {
        case 'source':
          const source = this.addPoint(map, center, this.createIconOptions('source')).on("mousedown", () => onClick(_instance));
          sources.push(source);
          break;
        case 'sink':
          const sink = this.addPoint(map, center, this.createIconOptions('sink')).on("mousedown", () => onClick(_instance));
          sinks.push(sink);
          break;
      }
    }

    mapObjects.sources = L.layerGroup(sources);
    mapObjects.sinks = L.layerGroup(sinks);
  },
  addLinks(map, links = [], mapObjects = { sources: null, sinks: null, links: null }, onClick = () => { }) {
    const linksLayer = [];

    for (const _link of links.filter((l) => l.selected)) {
      const latLngs = [_link.geo_segments.map((gs) => ([
        gs.data.from, gs.data.to
      ]))];

      // #3B82F6 is bg-blue-500
      const link = L.polyline(latLngs, { color: '#3B82F6' }).addTo(map);

      linksLayer.push(link);
    }

    mapObjects.links = L.layerGroup(linksLayer);
  },
  createIconOptions(type, inFocus = false) {
    const iconOptions = {
      type: '',
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
  removeAllInstances(map, mapObjects = { sources: null, sinks: null, links: null }) {
    const sources = mapObjects.sources?.getLayers() ?? [];
    const sinks = mapObjects.sinks?.getLayers() ?? [];

    for (const _sourceLayer of sources) {
      map.removeLayer(_sourceLayer);
    }
    mapObjects.sources = null;

    for (const _sinkLayer of sinks) {
      map.removeLayer(_sinkLayer);
    }
    mapObjects.sinks = null;
  },
  removeAllLinks(map, mapObjects = { sources: null, sinks: null, links: null }) {
    const links = mapObjects.links?.getLayers() ?? [];

    for (const _linkLayer of links) {
      map.removeLayer(_linkLayer);
    }
    mapObjects.links = null;
  },
  focusMarker(map, marker, mapObjects = { sources: null, sinks: null, links: null }) {
    const sources = mapObjects.sources?.getLayers();
    const sinks = mapObjects.sinks?.getLayers();

    const markers = [...sources, ...sinks];

    for (const _marker of markers) {
      const options = this.createIconOptions(_marker.options.objectType);
      _marker.setIcon(L.BeautifyIcon.icon(options))
    }

    if (!!marker) {
      const options = this.createIconOptions(marker.options.objectType, true)
      const icon = L.BeautifyIcon.icon(options)
      marker.setIcon(icon)
    }
  },
  getInstancesInView(map, instances = []) {
    const instancesInView = [];

    for (const _instance of instances) {
      const instanceLatLng = L.latLng(
        _instance.location?.data?.center[0],
        _instance.location?.data?.center[1]
      );

      if (map.getBounds().contains(instanceLatLng))
        instancesInView.push(_instance)
    }

    return instancesInView;
  },
  getLinksInView(map, links = []) {
    const linksInView = [];

    for (const _link of links) {
      for (const _geoSegment of _link.geo_segments) {
        const geoSegmentLatLngEdgeA = L.latLng(_geoSegment.data.from, _geoSegment.data.to);
        const geoSegmentLatLngEdgeB = L.latLng(_geoSegment.data.to, _geoSegment.data.from);

        if (map.getBounds().contains(geoSegmentLatLngEdgeA) || map.getBounds().contains(geoSegmentLatLngEdgeB)) {
          linksInView.push(_link);

          break;
        }
      }
    }

    return linksInView;
  },
  setBoundaryView(map, boundingBox) {
      
  }
}
