<template>
    <div id="map" class="min-h-screen min-w-full z-0"></div>

    <button
        type="button"
        class="fixed left-16 lg:left-80 top-20 lg:top-4 z-10 inline-flex items-center p-2 border-2 border-gray-400 rounded-full shadow-sm text-gray-200 bg-gray-50 hover:bg-gray-100"
        @click="onDefaultLocation"
    >
        <BookmarkIcon class="h-8 w-auto text-blue-500" aria-hidden="true" />
    </button>

    <button
        type="button"
        class="fixed left-16 lg:left-96 top-20 lg:top-4 z-10 inline-flex items-center p-2 border-2 border-gray-400 rounded-full shadow-sm text-gray-200 bg-gray-50 hover:bg-gray-100"
        @click="showTestNotification"
    >
        <BellIcon class="h-8 w-auto text-blue-500" aria-hidden="true" />
    </button>
</template>

<script>
import { computed, onBeforeUnmount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import L from "leaflet";
import { usePage } from "@inertiajs/inertia-vue3";
import route from "ziggy";

import mapUtils from "@/Utils/map.js";

import "beautifymarker";
import "leaflet-contextmenu";
// CSS for Markers
import "beautifymarker/leaflet-beautify-marker-icon.css";
import "leaflet-contextmenu/dist/leaflet.contextmenu.min.css";

import { BookmarkIcon, BellIcon } from "@heroicons/vue/solid";

import { notify } from "@kyvg/vue3-notification";

export default {
    components: {
        BookmarkIcon,
        BellIcon,
    },

    props: {
        center: {
            type: Array,
            default: [],
        },
        zoom: {
            type: Number,
            default: -1,
        },
        preview: {
            type: Boolean,
            default: false,
        },
        simulation: {
            type: Boolean,
            default: false,
        },
    },

    setup(props) {
        const store = useStore();

        const map = ref(null);

        const mapObjects = ref({
            sources: null,
            sinks: null,
            links: null,
            circleLinks: null,
        });

        const instances = ref([]);
        const links = ref([]);

        const storeInstances = computed(
            () => store.getters["objects/instances"]
        );
        const storeLinks = computed(() => store.getters["objects/links"]);

        watch(instances, () => loadMarkers(), {
            deep: true,
        });
        watch(links, () => loadLinks(), {
            deep: true,
        });

        watch(storeInstances, (values) => (instances.value = values), {
            deep: true,
        });
        watch(storeLinks, (values) => (links.value = values), {
            deep: true,
        });

        const currentLinkSegments = [];
        const currentCircleSegments = [];

        const lineLink = [];

        const currentSegment = {
            from: null,
            start: null,
        };

        const selectedLink = computed(
            () => store.getters["map/currentLinks"]
        );

        const user = computed(() => usePage().props.value.user);

        const center = computed({
            get: () => {
                const storeCenter = store.getters["map/center"];

                if (storeCenter.length) return storeCenter;

                if (props.center.length) return props.center;

                if (user.value?.data?.map?.hasOwnProperty("center")) {
                    const center = user.value.data.map.center;

                    store.dispatch("map/setCenter", {
                        center: center,
                    });

                    return center;
                }
            },
            set: (value) => store.dispatch("map/setCenter", { center: value }),
        });

        const zoom = computed({
            get: () => {
                const storeZoom = store.getters["map/zoom"];

                if (storeZoom) return storeZoom;

                if (props.zoom !== -1) return props.zoom;

                if (user.value?.data?.map?.hasOwnProperty("zoom")) {
                    const zoom = user.value.data.map.zoom;

                    store.dispatch("map/setZoom", {
                        zoom: zoom,
                    });

                    return zoom;
                }
            },
            set: (value) => store.dispatch("map/setZoom", { zoom: value }),
        });

        const defaultLocation = computed({
            get: () => {
                const storeDefaultLocation =
                    store.getters["map/defaultLocation"];

                if (storeDefaultLocation.length) return storeDefaultLocation;

                if (user.value?.data?.map?.hasOwnProperty("defaultLocation")) {
                    store.dispatch("map/setDefaultLocation", {
                        defaultLocation: user.value.data.map.defaultLocation,
                    });

                    return user.value.data.map.defaultLocation;
                }
            },
            set: (value) =>
                store.dispatch("map/setDefaultLocation", {
                    defaultLocation: value.latlng,
                }),
        });

        const onCreateSink = (marker) => {
            store.dispatch("map/selectMarker", {
                marker: marker.latlng,
                type: 'Sinks',
                color: "green-700",
            });
            store.dispatch("objects/showSlide", {
                route: "objects.sinks.create",
                props: {},
            });

            map.value.contextmenu.removeAllItems();

            for (const _contextItem of sinkCreationMapContext) {
                map.value.contextmenu.insertItem(_contextItem);
            }
        };

        const onCreateSource = (marker) => {
            store.dispatch("source/reset");
            store.dispatch("map/selectMarker", {
                marker: marker.latlng,
                type: 'Sources',
                color: "red-700",
            });
            store.dispatch("objects/showSlide", {
                route: "objects.sources.create",
                props: {},
            });

            map.value.contextmenu.removeAllItems();

            for (const _contextItem of sourceCreationMapContext) {
                map.value.contextmenu.insertItem(_contextItem);
            }
        };

        const selectedMarkerLatlng = computed(
            () => store.getters["map/selectedMarker"]
        );
        const selectedMarker = ref();

        watch(selectedMarkerLatlng, (newValue) => {
            if (!props.simulation) {
                if (selectedMarker.value)
                    map.value.removeLayer(selectedMarker.value);

                if (newValue) {
                    const draggable = true;
                    selectedMarker.value = mapUtils.addPoint(map.value, newValue, draggable, 'instance', {
                        icon: "plus",
                        textClass: "text-" + store.getters["map/selectedMarkerColor"],
                        borderClass:
                            "border-" + store.getters["map/selectedMarkerColor"],
                    });
                    
                    selectedMarker.value.on('dragend', (event) => {
                        store.dispatch("map/setSelectedMarkerPosition", { position: event.target.getLatLng() })
                    });
                }
            }
        });

        watch(
            () => store.getters["map/startMarker"],
            (marker) => {
                const centerValue = store.getters["map/center"];
                const value = {
                    latlng: {
                        lat: centerValue[0],
                        lng: centerValue[1]
                    }
                };
                
                if (marker === 'sinks') {
                   onCreateSink(value);
                } else if (marker === 'sources') {
                    onCreateSource(value);
                } else if (marker === 'links') {
                    store.commit("objects/closeSlide");
                    onCreateLink(value);
                }
                store.dispatch("map/startMarker", null);
            },
            { immediate: true }
        );

        watch(
            () => store.getters["map/saveLink"],
            (e) => {
                if (e) {
                    map.value.contextmenu.removeAllItems();
                    defautMapContext.map(_contextItem => map.value.contextmenu.insertItem(_contextItem));
                    store.commit("map/startLinks");
                    loadCircles();
                    currentSegment.from = null;
                    store.dispatch("map/saveLink", false);
                }
            },
            { immediate: true }
        );

        watch(
            () => store.getters["map/removeAllSegment"],
            (e) => {
                if (e) {
                    currentCircleSegments.map((circle, i) => {
                        map.value.removeLayer(circle);
                        currentCircleSegments.slice(i, 1);
                    });
                    currentLinkSegments.map((segment, i) => {
                        map.value.removeLayer(segment);
                        currentLinkSegments.slice(i, 1);
                    });
                    store.dispatch("map/saveLink", true);
                    store.dispatch("map/removeAllSegment", false);
                }
            },
            { immediate: true }
        );

        watch(
            () => store.getters["map/removeMarker"],
            (e) => {
                if (e) {
                    removeMarker();
                    store.dispatch("map/removeMarker", false);
                }
            },
            { immediate: true }
        );

        watch(
            () => store.getters["objects/showObject"],
            (show) => {
                if(show.route != '') {
                    setTimeout(() => store.dispatch("objects/showSlide", { route: show.route, props: show.param }), 500);
                    store.commit("objects/setShowObject", { route: '', param: '' });
                } 
            },
            { immediate: true }
        );

        const removeMarker = () => {
            if (selectedMarker.value) {
                map.value.removeLayer(selectedMarker.value);

                map.value.contextmenu.removeAllItems();
                defautMapContext.map(_contextItem => map.value.contextmenu.insertItem(_contextItem));

                store.dispatch("map/selectMarker", {
                    marker: null,
                    type: null,
                    color: "green",
                });
                store.dispatch("source/reset");
                store.dispatch("objects/showSlide", { route: "objects.list" });
            }     
        };

        const onCreateLink = (value, newSegment = false) => {
            if (!currentSegment.from && mapObjects.value.links) {
                const circleLinks = [];
                mapObjects.value.links.getLayers().forEach(points => {
                    points._latlngs[0].map((element) => {
                        circleLinks.push(mapUtils.addCircle(map.value, element[0]).bringToFront());
                        circleLinks.push(mapUtils.addCircle(map.value, element[1]).bringToFront());
                    });
                });
                mapObjects.value.circleLinks = L.layerGroup(circleLinks);
            }

            currentSegment.from = value.latlng ?? value.getLatLng();            
    
            if (!newSegment) {
                currentLinkSegments.map((element) => map.value.removeLayer(element));
                store.commit("map/startLinks");
            }
                
            map.value.contextmenu.removeAllItems();

            for (const _contextItem of linkCreationMapContext) {
                map.value.contextmenu.insertItem(_contextItem);
            }

            if (mapObjects.value.sinks)
                for (const _sinkLayer of mapObjects.value.sinks.getLayers()) {
                    _sinkLayer.options.contextmenuItems = [];
                    for (const _contextItem of linkCreationMarkerContext) {
                        _sinkLayer.options.contextmenuItems.push(
                            _contextItem(_sinkLayer)
                        );
                    }
                }

            if (mapObjects.value.sources)
                for (const _sourceLayer of mapObjects.value.sources.getLayers()) {
                    _sourceLayer.options.contextmenuItems = [];
                    for (const _contextItem of linkCreationMarkerContext) {
                        _sourceLayer.options.contextmenuItems.push(
                            _contextItem(_sourceLayer)
                        );
                    }
                }

            if (mapObjects.value.circleLinks)
                for (const _circleLinkLayer of mapObjects.value.circleLinks.getLayers()) {
                    _circleLinkLayer.options.contextmenuItems = [];
                    for (const _contextItem of linkCreationMarkerContext) {
                        _circleLinkLayer.options.contextmenuItems.push(
                            _contextItem(_circleLinkLayer)
                        );
                    }
                }

            //map.value.on("dblclick", (e) => onNextPoint(e));
            map.value.on("keydown", (e) => e.originalEvent.keyCode === 27 ? onStopLink():'');
            map.value.on("mousemove", (e) => {
                document.getElementById("map").focus();
                if (lineLink.length) {
                    map.value.removeLayer(lineLink[0]);
                    lineLink.splice(0, 1);
                }

                if (store.getters["map/saveLink"]) return onStopLink();

                const segment = L.polyline([currentSegment.from, e.latlng], {
                    color: 'green'
                }).addTo(map.value).on("dblclick", () => onNextPoint(e)).bringToBack();

                lineLink.push(segment);                
            });
        };

        const onStopLink = () => {
            //store.dispatch("objects/showSlide", { route: "objects.list" });

            map.value.contextmenu.removeAllItems();
            map.value.off("mousemove");
            //map.value.off("dblclick");

            if (lineLink.length) {
                map.value.removeLayer(lineLink[0]);
                lineLink.splice(0, 1);
            }

            for (const _contextItem of linkStopMapContext) {
                map.value.contextmenu.insertItem(_contextItem);
            }

            if (mapObjects.value.sinks)
                for (const _sinkLayer of mapObjects.value.sinks.getLayers()) {
                    _sinkLayer.options.contextmenuItems = [];
                    for (const _contextItem of linkStopMarkerContext) {
                        _sinkLayer.options.contextmenuItems.push(
                            _contextItem(_sinkLayer)
                        );
                    }
                }

            if (mapObjects.value.sources)
                for (const _sourceLayer of mapObjects.value.sources.getLayers()) {
                    _sourceLayer.options.contextmenuItems = [];
                    for (const _contextItem of linkStopMarkerContext) {
                        _sourceLayer.options.contextmenuItems.push(
                            _contextItem(_sourceLayer)
                        );
                    }
                }
        };

        const onStartMarker = (value) => {
            //const start = mapUtils.addCircle(map.value, value.getLatLng());

            currentSegment.from = value.getLatLng();
            currentSegment.start = value.getLatLng();
        };

        const onFinishLink = () => {
            map.value.off("mousemove");
            store.dispatch("objects/showSlide", {
                route: "objects.links.create",
                props: {},
            });            
        };

        const onFinishMarker = (value) => {
            const coord = value.getLatLng();
            const segment = mapUtils.addSegment(
                map.value,
                currentSegment.from,
                coord,
                linkCreationSegmentContext
            );

            const id = `${currentSegment.from}${coord}`;
            store.dispatch("map/setLink", {
                id,
                link: {
                    from: currentSegment.from,
                    to: coord,
                    distance: L.latLng(coord).distanceTo(currentSegment.from),
                    data: {},
                },
            });

            currentLinkSegments.push(segment);
            // mapObjects.value.links.push(segment);
            currentSegment.from = coord;

            onStopLink();

            store.dispatch("objects/showSlide", {
                route: "objects.links.create",
                props: {},
            });
        };

        const onRemoveSegment = (value) => {
            const points = value.getLatLngs();
            // const segmentIndex = mapObjects.value.links.findIndex(
            //   (s) =>
            //     s.getLatLngs().includes(points[0]) &&
            //     s.getLatLngs().includes(points[1])
            // );
            let circleIndex = currentCircleSegments.findIndex(e => e._latlng == points[0]);
            map.value.removeLayer(currentCircleSegments[circleIndex]);
            currentCircleSegments.splice(circleIndex, 1);
            circleIndex = currentCircleSegments.findIndex(e => e._latlng == points[1]);
	        map.value.removeLayer(currentCircleSegments[circleIndex]);
            currentCircleSegments.splice(circleIndex, 1);

            const segmentIndex = currentLinkSegments.findIndex(
                (s) =>
                    s.getLatLngs().includes(points[0]) &&
                    s.getLatLngs().includes(points[1])
            );

            const id = `${points[0]}${points[1]}`;
            store.dispatch("map/unsetLink", id);

            map.value.removeLayer(value);
            // mapObjects.value.links.splice(segmentIndex, 1);
            currentLinkSegments.splice(segmentIndex, 1);

            // if (mapObjects.value.links.length > 0) {
            //   currentSegment.from =
            //     mapObjects.value.links[segmentIndex - 1].getLatLngs()[1];
            // } else {
            //   currentSegment.from = currentSegment.start;
            // }

            if (currentLinkSegments.length > 0) {
                currentSegment.from =
                    currentLinkSegments[segmentIndex - 1].getLatLngs()[1];
            } else {
                currentSegment.from = currentSegment.start;
            }
        };

        const onSegmentProperties = (value) => {
            value.setStyle({
                color: "#F9A602",
            });
            store.dispatch("objects/showSlide", {
                route: "objects.links.create",
                props: {},
            });
        };

        const onNextPoint = (value) => {
            const coord = value.latlng ?? value.getLatLng();

            const segment = mapUtils.addSegment(
                map.value,
                currentSegment.from,
                coord,
                linkCreationSegmentContext,
            ).bringToBack();

            let circle = '';
            
            circle = mapUtils.addCircle(map.value, currentSegment.from);
            circle.bindContextMenu({
                contextmenu: true,
                contextmenuWidth: 140,
                contextmenuItems: circleCreationSegmentContext(circle)
            });
            currentCircleSegments.push(circle);

            circle = mapUtils.addCircle(map.value, coord);
            circle.bindContextMenu({
                contextmenu: true,
                contextmenuWidth: 140,
                contextmenuItems: circleCreationSegmentContext(circle)
            });
            currentCircleSegments.push(circle);

            const id = `${currentSegment.from}${coord}`;
            store.dispatch("map/setLink", {
                id,
                link: {
                    from: currentSegment.from,
                    to: coord,
                    distance: L.latLng(coord).distanceTo(currentSegment.from),
                    data: [],
                },
            });

            // mapObjects.value.links.push(segment);
            currentLinkSegments.push(segment);
            currentSegment.from = coord;

            store.dispatch("objects/showSlide", {
                route: "objects.links.create",
                props: {},
            });
        };

        const onDefaultLocation = () => {
            if (!defaultLocation.value) {
                notify({
                    group: "snackbar",
                    title: "Default Locations is not set.",
                    text: "First, create a default location first.",
                    data: {
                        type: "warning",
                    },
                });

                return;
            }

            const location = {
                type: "point",
                data: {
                    center: defaultLocation.value,
                },
            };

            mapUtils.centerAtLocation(map.value, location);
        };

        const defautMapContext = [
            {
                text: "Create Sink Here",
                callback: onCreateSink,
            },
            {
                text: "Create Source Here",
                callback: onCreateSource,
            },
            {
                text: "Start Link Creation",
                callback: onCreateLink,
            },
            "-",
            {
                text: "Set Default Location",
                callback: (location) => (defaultLocation.value = location),
            },
            "-",
            {
                text: "Zoom in",
                callback: (o) => map.value.zoomIn(),
            },
            {
                text: "Zoom out",
                callback: (o) => map.value.zoomOut(),
            },
        ];

        const sinkCreationMapContext = [
            {
                text: "Finish Sink Creation",
                callback: () => store.dispatch("objects/showSlide", {
                    route: "objects.sinks.create",
                    props: {},
                }),
            },
            {
                text: "Remove Sink",
                callback: removeMarker,
            },
        ];

        const sourceCreationMapContext = [
            {
                text: "Finish Source Creation",
                callback: () => store.dispatch("objects/showSlide", {
                    route: "objects.sources.create",
                    props: {},
                }),
            },
            {
                text: "Remove Source",
                callback: removeMarker,
            },
        ];

        const linkCreationMapContext = [
            {
                text: "Stop Link Creation",
                callback: onStopLink,
            },
            {
                text: "Next Point Here",
                callback: onNextPoint,
            },
        ];

        const linkStopMapContext = [
            {
                text: "Finish Link Creation",
                callback: onFinishLink,
            },
            {
                text: "Start New Segment",
                callback: (m) => onCreateLink(m, true),
            }
        ];

        const linkCreationMarkerContext = [
            () => "-",
            (m) => ({
                text: "Start here",
                callback: () => onStartMarker(m),
            }),
            (m) => ({
                text: "Connect here",
                callback: () => onNextPoint(m),
            }),
            (m) => ({
                text: "Finish here",
                callback: () => onFinishMarker(m),
            }),
        ];

        const linkStopMarkerContext = [
            () => "-",
            (m) => ({
                text: "Start Segment Here",
                callback: () => onCreateLink(m, true),
            }),
        ];

        const linkCreationSegmentContext = (m) => {
            return [
                "-",
                {
                    text: "Remove segment",
                    callback: () => onRemoveSegment(m),
                },
                {
                    text: "Segment Properties",
                    callback: () => onSegmentProperties(m),
                },
            ];
        };

        const circleCreationSegmentContext = (m) => {
            return [
                "-",
                {
                    text: "Start here",
                    callback: () => onStartMarker(m),
                },
                {
                    text: "Connect here",
                    callback: () => onNextPoint(m),
                },
                {
                    text: "Finish here",
                    callback: () => onFinishMarker(m),
                },
            ];
        };

        const onCenterLocation = (loc, move = true) => {
            if (move) mapUtils.centerAtLocation(map.value, loc);

            if (
                !mapObjects.value.sources &&
                !mapObjects.value.sinks &&
                !mapObjects.value.links
            )
                return;

            const sources = mapObjects.value.sources.getLayers();
            const sinks = mapObjects.value.sinks.getLayers();
            const markers = [...sources, ...sinks];

            const location = L.latLng(loc.data?.center);
            const marker = markers.find(
                (m) => m.getLatLng().distanceTo(location) === 0
            );

            mapUtils.focusMarker(map.value, marker, mapObjects.value);
        };

        const onMarkerClick = (instance) => {
            const _type = instance.template.category.type;

            switch (_type) {
                case "sink":
                    if (props.simulation) {
                        store.dispatch("map/selectMarker", {
                            marker: instance.location.data.center,
                            type: 'sinks',
                        });
                    } else {
                        store.dispatch("objects/showSlide", {
                            route: "objects.sinks.show",
                            props: instance.id,
                        });
                        onCenterLocation(instance.location, false);
                    }
                    break;
                case "source":
                    if (props.simulation) {
                        store.dispatch("map/selectMarker", {
                            marker: instance.location.data.center,
                            type: 'sources',
                        });
                    } else {
                        store.dispatch("objects/showSlide", {
                            route: "objects.sources.show",
                            props: instance.id,
                        });
                        onCenterLocation(instance.location, false);
                    }
                    break;
                default:
                    break;
            }
        };

        const onLinkClick = (link) => {
            if (!props.preview) {
                store.dispatch("objects/showSlide", {
                    route: "objects.links.show",
                    props: link.id,
                });
            } else {
                if (selectedLink.value[link.id]) {
                    store.dispatch("map/unsetLink", link.id);
                } else {
                    store.dispatch("map/setLink", {
                        id: link.id,
                        link,
                    });
                }
                store.dispatch("map/selectMarker", {
                    marker: link,
                    type: 'links',
                });
            }
        };

        const loadMarkers = (markers = null) => {
            if (!instances.value.length) return;
            
            mapUtils.removeAllInstances(map.value, mapObjects.value);
            if (props.preview) {    
                if (localStorage.getItem('objectsSources') == 'false') {
                    instances.value.forEach((element, index) => {
                        if (element.template.category.type == 'source') {
                            instances.value.splice(index, 1);
                        }
                    });
                }
                if (localStorage.getItem('objectsSinks') == 'false') {
                    instances.value.forEach((element, index) => {
                        if (element.template.category.type == 'sink') {
                            instances.value.splice(index, 1);
                        }
                    });
                }
            }
            
            mapUtils.addInstances(
                map.value,
                markers ?? instances.value,
                mapObjects.value,
                props.preview && !props.simulation ? () => {} : onMarkerClick,
                props.simulation
            );

            if (lineLink.length) {
                for (const _sourceLayer of mapObjects.value.sources.getLayers()) {
                    _sourceLayer.options.contextmenuItems = [];
                    for (const _contextItem of linkCreationMarkerContext) {
                        _sourceLayer.options.contextmenuItems.push(
                            _contextItem(_sourceLayer)
                        );
                    }
                }

                for (const _sinkLayer of mapObjects.value.sinks.getLayers()) {
                    _sinkLayer.options.contextmenuItems = [];
                    for (const _contextItem of linkCreationMarkerContext) {
                        _sinkLayer.options.contextmenuItems.push(
                            _contextItem(_sinkLayer)
                        );
                    }
                }
            }
        };

        const loadLinks = (markers = null) => {
            if (!links.value.length) return;

            mapUtils.removeAllLinks(map.value, mapObjects.value);
            if (props.preview) { 
                if (localStorage.getItem('objectsLinks') == 'false') {
                    links.value = [];
                }
            }

            mapUtils.addLinks(
                map.value,
                markers ?? links.value,
                mapObjects.value,
                props.preview && !props.simulation ? () => {} : onLinkClick,
                props.simulation,
                selectedLink.value
            );
        };

        const loadCircles = () => mapUtils.removeAllCircles(map.value, mapObjects.value);

        const refreshInstances = () => {
            if (storeInstances.value.length) {
                instances.value = storeInstances.value;
                return;
            }

            window.axios.get(route("objects.markers")).then(({ data }) => {
                if (!data.instances.length) return;
                instances.value = data.instances.map((i) => ({
                    ...i,
                    selected: true,
                }));
            });
        };

        const refreshLinks = () => {
            if (storeLinks.value.length) {
                links.value = storeLinks.value;
                return;
            }

            window.axios.get(route("objects.markers")).then(({ data }) => {
                if (!data.links.length) return;

                links.value = data.links.map((l) => ({
                    ...l,
                    selected: true,
                }));
            });
        };

        const showNotification = (title, text, type) => {
            notify({
                group: "notifications",
                title: title,
                text: text,
                duration: 5000,
                data: {
                    type: type,
                },
            });
            store.commit("objects/setNotify", {});
        };

        watch(
            () => store.getters["objects/notify"],
            (e) => {
                if (e.title)
                    showNotification(e.title, e.text, e.type);
            },
            { immediate: true }
        );

        onMounted(() => {
            map.value = mapUtils.init("map", center.value, zoom.value, {
                drawControl: true,
                contextmenu: true,
                contextmenuWidth: 140,
                contextmenuItems: props.preview ? [] : defautMapContext,
            });

            window.map = map.value;

            map.value.on("moveend", ({ target: map }) => {
                lazilyGetMapCenter(map);

                if (!selectedMarker.value && !props.simulation) {
                    const visibleInstances = mapUtils.getInstancesInView(
                        map,
                        instances.value
                    );
                    loadMarkers(visibleInstances);

                    const visibleLinks = mapUtils.getLinksInView(map, links.value);
                    loadLinks(visibleLinks);
                }
            });

            map.value.on("zoomend", ({ target: map }) => lazilyGetMapZoom(map));

            lazilyGetMapView(map.value);
            refreshInstances();
            refreshLinks();
        });

        const lazilyGetMapCenter = window._.debounce((map) => {
            center.value = map.getCenter();
            lazilyGetMapView(map);
        }, 500);

        const lazilyGetMapView = window._.debounce((map) => {
            store.dispatch("map/setView", map.getBounds());
        }, 500);

        const lazilyGetMapZoom = window._.debounce((map) => {
            zoom.value = map.getZoom();
            lazilyGetMapView(map);
        }, 500);

        const unsubscribeFromActions = store.subscribeAction(
            ({ type, payload }) => {
                if (type === "map/centerAt") {
                    const { marker } = payload;
                    onCenterLocation(marker);
                }

                if (type === "map/doRefreshMap") {
                    mapUtils.removeAllInstances(map.value, mapObjects.value);
                    refreshInstances();
                    refreshLinks();
                }

                if (type === "map/unfocusMarker") {
                    mapUtils.focusMarker(map.value, null, mapObjects.value);
                }
            }
        );

        onBeforeUnmount(() => {
            map.value.off("moveend");
            map.value.off("zoomend");

            unsubscribeFromActions();
        });

        const showTestNotification = () => {
            notify({
                group: "notifications",
                title: "Test Notification",
                text: "Test notification description",
                data: {
                    type: "danger",
                },
            });
        };

        return {
            onDefaultLocation,
            showTestNotification,
        };
    },
};
</script>
