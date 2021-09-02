<template>
  <div
    id="map"
    class="min-h-screen min-w-full z-0"
  ></div>

  <button
    type="button"
    class="fixed left-16 lg:left-80 top-20 lg:top-4 z-10 inline-flex items-center p-2 border-2 border-gray-400 rounded-full shadow-sm text-gray-200 bg-gray-50 hover:bg-gray-100"
    @click="onDefaultLocation"
  >
    <BookmarkIcon
      class="h-8 w-auto text-blue-500"
      aria-hidden="true"
    />
  </button>

</template>

<script>
import { computed, onBeforeUnmount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import L from "leaflet";
import { usePage } from "@inertiajs/inertia-vue3";
import route from "../../../../vendor/tightenco/ziggy/src/js";

import mapUtils from "@/Utils/map.js";

import "beautifymarker";
import "leaflet-contextmenu";
// CSS for Markers
import "beautifymarker/leaflet-beautify-marker-icon.css";
import "leaflet-contextmenu/dist/leaflet.contextmenu.min.css";

import { BookmarkIcon } from "@heroicons/vue/solid";

export default {
  components: {
    BookmarkIcon,
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
  },

  setup(props) {
    const store = useStore();

    const map = ref(null);

    const mapObjects = ref({
      sources: null,
      sinks: null,
      links: [],
    });

    const instances = ref([]);
    const storeInstances = computed(() => store.getters["objects/instances"]);

    watch(instances, () => loadMarkers(), {
      deep: true,
    });

    watch(storeInstances, (values) => (instances.value = values), {
      deep: true,
    });

    const currentSegment = {
      from: null,
      start: null,
    };

    const user = ref(usePage().props.value.user);

    const center = computed({
      get() {
        if (!props.center.length) return user.value?.data?.map?.center;

        return props.center;
      },
      set(value) {
        store.dispatch("map/setData", { center: value });
      },
    });

    const zoom = computed({
      get() {
        if (props.zoom === -1) return user.value?.data?.map?.zoom;

        return props.zoom;
      },
      set(value) {
        store.dispatch("map/setData", { zoom: value });
      },
    });

    const onCreateSink = (marker) => {
      store.dispatch("map/selectMarker", {
        marker: marker.latlng,
        color: "green-700",
      });
      store.dispatch("objects/showSlide", {
        route: "objects.sinks.create",
        props: {},
      });
    };

    const onCreateSource = (marker) => {
      store.dispatch("map/selectMarker", {
        marker: marker.latlng,
        color: "red-700",
      });
      store.dispatch("objects/showSlide", {
        route: "objects.sources.create",
        props: {},
      });
    };

    const selectedMarkerLatlng = computed(
      () => store.getters["map/selectedMarker"]
    );
    const selectedMarker = ref();

    watch(selectedMarkerLatlng, (newValue) => {
      if (selectedMarker.value) map.value.removeLayer(selectedMarker.value);

      selectedMarker.value = mapUtils.addPoint(map.value, newValue, {
        icon: "plus",
        textClass: "text-" + store.getters["map/selectedMarkerColor"],
        borderClass: "border-" + store.getters["map/selectedMarkerColor"],
      });
    });

    const onCreateLink = (value) => {
      store.dispatch("objects/showSlide", {
        route: "objects.links.create",
        props: {},
      });

      store.commit("map/startLinks");

      map.value.contextmenu.removeAllItems();
      for (const a of linkCreationMapContext)
        map.value.contextmenu.insertItem(a);

      for (const m of mapObjects.value.sources.getLayers()) console.log(m);

      for (const m of mapObjects.value.sinks.getLayers())
        for (const a of linkCreationMarkerContext)
          m.options.contextmenuItems.push(a(m));
    };

    const onStopLink = () => {
      store.dispatch("objects/closeSlide");
      map.value.contextmenu.removeAllItems();
      for (const a of defautMapContext) map.value.contextmenu.insertItem(a);
    };

    const onStartMarker = (value) => {
      const start = mapUtils.addCircle(map.value, value.getLatLng());
      currentSegment.from = start.getLatLng();
      currentSegment.start = start.getLatLng();
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

      mapObjects.value.links.push(segment);
      currentSegment.from = coord;

      store.dispatch("objects/showSlide", {
        route: "objects.links.create",
        props: {},
      });
    };

    const onRemoveSegment = (value) => {
      const points = value.getLatLngs();
      const segmentIndex = mapObjects.value.links.findIndex(
        (s) =>
          s.getLatLngs().includes(points[0]) &&
          s.getLatLngs().includes(points[1])
      );

      const id = `${points[0]}${points[1]}`;
      store.dispatch("map/unsetLink", id);

      map.value.removeLayer(value);
      mapObjects.value.links.splice(segmentIndex, 1);
      if (mapObjects.value.links.length > 0)
        currentSegment.from =
          mapObjects.value.links[segmentIndex - 1].getLatLngs()[1];
      else {
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
      const coord = value.latlng;
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

      mapObjects.value.links.push(segment);
      currentSegment.from = coord;

      store.dispatch("objects/showSlide", {
        route: "objects.links.create",
        props: {},
      });
    };

    const onSetDefaultLocation = (location) => {
      store.dispatch("map/setDefaultLocation", {
        defaultLocation: location.latlng,
      });
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
        callback: onSetDefaultLocation,
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

    const linkCreationMarkerContext = [
      () => "-",
      (m) => ({
        text: "Start here",
        callback: () => onStartMarker(m),
      }),
      (m) => ({
        text: "Finish here",
        callback: () => onFinishMarker(m),
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

    const onCenterLocation = (loc, move = true) => {
      if (move) mapUtils.centerAtLocation(map.value, loc);
      const allMarkers = mapObjects.value.all.getLayers();
      const geo = L.latLng(loc.data?.center);
      const m = allMarkers.find((_m) => _m.getLatLng().distanceTo(geo) === 0);
      mapUtils.focusMarker(map.value, m, mapObjects.value);
    };

    const onMarkerClick = (instance) => {
      const _type = instance.template.category.type;

      switch (_type) {
        case "sink":
          store.dispatch("objects/showSlide", {
            route: "objects.sinks.show",
            props: instance.id,
          });
          onCenterLocation(instance.location, false);
          break;
        case "source":
          store.dispatch("objects/showSlide", {
            route: "objects.sources.show",
            props: instance.id,
          });
          onCenterLocation(instance.location, false);
          break;
        default:
          break;
      }
    };

    const loadMarkers = () => {
      if (!instances.value.length) return;

      mapUtils.removeAllInstances(map.value, mapObjects.value);

      mapUtils.addInstances(
        map.value,
        instances.value,
        mapObjects.value,
        onMarkerClick
      );
    };

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

    const defaultLocation = computed(
      () => store.getters["map/defaultLocation"]
    );

    const onDefaultLocation = () => {
      if (!defaultLocation.value.length) return;

      const location = {
        type: "point",
        data: {
          center: defaultLocation.value,
        },
      };

      mapUtils.centerAtLocation(map.value, location);
    };

    onMounted(() => {
      map.value = mapUtils.init("map", center.value, zoom.value, {
        drawControl: true,
        contextmenu: true,
        contextmenuWidth: 140,
        contextmenuItems: defautMapContext,
      });

      window.map = map.value;

      map.value.on(
        "moveend",
        _.debounce(({ target }) => (center.value = target.getCenter()), 1000)
      );
      map.value.on(
        "zoomend",
        _.debounce(({ target }) => (zoom.value = target.getZoom()), 1000)
      );

      refreshInstances();
    });

    const unsubscribeFromActions = store.subscribeAction(
      ({ type, payload }) => {
        if (type === "map/centerAt") {
          const { marker } = payload;
          onCenterLocation(marker);
        }

        if (type === "map/doRefreshMap") {
          mapUtils.removeAllInstances(map.value, mapObjects.value);
          refreshInstances();
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

    return {
      onDefaultLocation,
      center,
      onCenterLocation,
      selectedMarkerLatlng,
      instances,
    };
  },
};
</script>
