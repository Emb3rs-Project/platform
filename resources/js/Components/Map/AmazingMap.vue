<template>
  <div id="map" class="h-screen min-w-full z-0"></div>
</template>

<script>
import mapUtils from "@/Utils/map.js";
import { computed, onMounted, ref } from "vue";
import L from "leaflet";
import "beautifymarker";
import "leaflet-contextmenu";

// CSS for Markers
import "beautifymarker/leaflet-beautify-marker-icon.css";
import "leaflet-contextmenu/dist/leaflet.contextmenu.min.css";
import { useStore } from "vuex";

export default {
  props: {
    centerValue: {
      type: Array,
    },
    instances: {
      type: Array,
      default: [],
    },
  },
  emits: ["onMove", "onCreateRequest"],
  setup(props, { emit }) {
    // TODO: Convert to VUEX!!!

    const map = ref(null);
    const mapObjects = ref({
      sources: null,
      sinks: null,
      links: [],
    });

    const currentSegment = {
      from: null,
      start: null,
    };

    const store = useStore();

    const center = computed({
      get() {
        return props.centerValue;
      },
      set(value) {
        emit("onMove", value);
      },
    });

    const onCreateSink = (_) => {
      store.dispatch("objects/goToSlideOver", {
        route: "objects.sinks.create",
        props: {},
      });
    };

    const onCreateSource = (_) =>
      store.dispatch("objects/goToSlideOver", {
        route: "objects.sources.create",
        props: {},
      });

    const onCreateLink = (value) => {
      store.dispatch("objects/goToSlideOver", {
        route: "objects.links.create",
        props: {},
      });

      map.value.contextmenu.removeAllItems();
      for (const a of linkCreationMapContext)
        map.value.contextmenu.insertItem(a);

      for (const m of mapObjects.value.sources.getLayers()) console.log(m);

      for (const m of mapObjects.value.sinks.getLayers())
        for (const a of linkCreationMarkerContext)
          m.options.contextmenuItems.push(a(m));
    };

    const onStopLink = () => {
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

      mapObjects.value.links.push(segment);
      currentSegment.from = coord;
    };

    const onRemoveSegment = (value) => {
      const points = value.getLatLngs();
      const segmentIndex = mapObjects.value.links.findIndex(
        (s) =>
          s.getLatLngs().includes(points[0]) &&
          s.getLatLngs().includes(points[1])
      );

      map.value.removeLayer(value);
      mapObjects.value.links.splice(segmentIndex, 1);
      if (mapObjects.value.links.length > 0)
        currentSegment.from = mapObjects.value.links[
          segmentIndex - 1
        ].getLatLngs()[1];
      else {
        currentSegment.from = currentSegment.start;
      }
    };

    const onSegmentProperties = (value) => {
      value.setStyle({
        color: "#F9A602",
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

      mapObjects.value.links.push(segment);
      currentSegment.from = coord;
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
      "-",
      {
        text: "Start Link Creation",
        callback: onCreateLink,
      },
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

    const linkCreationSegmentContext = (m) => [
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

    const onCenterLocation = (loc) => {
      //   console.log("AmazingMap::onCenterLocation", loc);
      mapUtils.centerAtLocation(map.value, loc.geo_object);
    };

    onMounted(() => {
      map.value = mapUtils.init("map", center.value, {
        drawControl: true,
        contextmenu: true,
        contextmenuWidth: 140,
        contextmenuItems: defautMapContext,
      });
      window.map = map.value;

      map.value.on(
        "moveend",
        ({ target }) => (center.value = target.getCenter())
      );

      if (props.instances?.length > 0) {
        mapUtils.addInstances(map.value, props.instances, mapObjects.value);
      }
    });

    return {
      center,
      onCenterLocation,
    };
  },
};
</script>

<style>
</style>
