<!DOCTYPE html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

    <script>
        L_NO_TOUCH = false;
        L_DISABLE_3D = false;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.6.0/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.6.0/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css"/>
    <style>html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }</style>
    <style>#map {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }</style>

    <meta name="viewport" content="width=device-width,
                initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <style>
        #map {
            position: relative;
            width: 100.0%;
            height: 100.0%;
            left: 0.0%;
            top: 0.0%;
        }
    </style>


    <style>
        .foliumpopup {
            margin: auto;
        }

        .foliumpopup table {
            margin: auto;
        }

        .foliumpopup tr {
            text-align: left;
        }

        .foliumpopup th {
            padding: 2px;
            padding-right: 8px;
        }
    </style>

</head>
<body>

<div class="folium-map" id="map"></div>

</body>
<script>

    let edges = JSON.parse(@json($edges));
    let nodes = JSON.parse(@json($nodes));
    let sourcesToMap = JSON.parse(@json($sourcesToMap));
    let sinksToMap = JSON.parse(@json($sinksToMap));

    function getLatLongFrom(osmid) {

        let n = nodes.find((node) => node.osmid === osmid)
        return [n.lat, n.lon]
    }

    edges.map( function (edge) {
        edge.fromCoord = getLatLongFrom(edge.from)
        edge.toCoord = getLatLongFrom(edge.to)
        return edge
    })

    const style = {
        "fillColor": "#00FF00",
        "color": "#00FF00",
    }


    const map = L.map(document.getElementById('map'),
        {
            center:sinksToMap[0].coords,
            zoom: 13,
            zoomControl: true,
            preferCanvas: false,
        })
    L.control.scale().addTo(map);

    L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
            "attribution": "Data by \u0026copy; \u003ca href=\"http://openstreetmap.org\"\u003eOpenStreetMap\u003c/a\u003e, under \u003ca href=\"http://www.openstreetmap.org/copyright\"\u003eODbL\u003c/a\u003e.",
            "detectRetina": false,
            "maxNativeZoom": 18,
            "maxZoom": 18,
            "minZoom": 0,
            "noWrap": false,
            "opacity": 1,
            "subdomains": "abc",
            "tms": false
        }
    ).addTo(map);





    function styler (feature) {
        return {
            stroke: true,
            color: "green",
            weight: 5
        }
    }

    function onEachFeature (feature, layer) {
        layer.on({
            click: function (e) {
                map.fitBounds(e.target.getBounds());
            }
        });
    };



    let polylinePoints = edges.forEach( (edge) => {
        new L.polyline([edge.toCoord, edge.fromCoord], {
            color: 'red',
            weight: 3,

            smoothFactor: 1
        }).addTo(map)

    })

    let sourceIcon = L.AwesomeMarkers.icon(
        {
            "extraClasses": "fa-rotate-0",
            "icon": "tint",
            "iconColor": "white",
            "markerColor": "red",
            "prefix": "glyphicon"
        }
    );

    let sources = []
    sourcesToMap.forEach((source) => {
        let sourceMark = L.marker(source.coords,  {title: source.name})
            .bindPopup(source.name)
            .addTo(map)
            .setIcon(sourceIcon);

        sources.push(sourceMark)
    })


    let sinkIcon = L.AwesomeMarkers.icon(
        {
            "tilte":"teste",
            "extraClasses": "fa-rotate-0",
            "icon": "leaf",
            "iconColor": "white",
            "markerColor": "green",
            "prefix": "glyphicon"
        }
    );

    let sinks = []
    sinksToMap.forEach((sink) => {

        let sinkMarker = L.marker(sink.coords, {title: sink.name})
            .bindPopup(sink.name)
            .addTo(map)
            .setIcon(sinkIcon);

        sinks.push(sinkMarker)
    })


    var overlays = {
        "Sources": L.layerGroup(sources),
        "Sinks": L.layerGroup(sinks),
    };

    L.control.layers({}, overlays).addTo(map);

</script>
