import route from "../../../../vendor/tightenco/ziggy/src/js";

const _state = () => ({
    focusedMarker: null,
    selectedMarker: null,
    selectedMarkerColor: "green",
    selectedMarkerType: null,
    currentLinks: {},
    saveLink: false,
    startLinks: false,
    removeAllSegment: false,
    removeMarker: false,

    center: [],
    zoom: null,
    defaultLocation: [],
    view: [],
});

const getters = {
    saveLink: (state) => state.saveLink,
    removeAllSegment: (state) => state.removeAllSegment,
    removeMarker: (state) => state.removeMarker,
    selectedMarker: (state) => state.selectedMarker,
    selectedMarkerType: (state) => state.selectedMarkerType,
    selectedMarkerColor: (state) => state.selectedMarkerColor,
    currentLinks: (state) => state.currentLinks,
    startLinks: (state) => state.startLinks,
    center: (state) => state.center,
    zoom: (state) => state.zoom,
    defaultLocation: (state) => state.defaultLocation,
    view: (state) => state.view,
};

const actions = {
    centerAt: () => {},
    refreshMap: ({ dispatch }) => {
        // DO LOGIC HERE
        // GET INFO FROM DB or FROM ANOTHER STATE OR WTV
        // THEN TRIGGER THE doREFRESHMAP!

        dispatch("doRefreshMap");
    },
    doRefreshMap: () => {
        console.log("doRefreshMap");
    },
    selectMarker: ({ commit, state }, { marker, type, color }) => {
        commit("selectMarker", marker);
        commit("selectedMarkerType", type);
        commit("selectMarkerColor", color);
    },
    focusMarker: ({ commit }, marker) => {
        commit("focusMarker", marker);
    },
    unfocusMarker: ({ commit }) => {
        commit("focusMarker", null);
    },
    saveLink: ({ commit }, saveLink) => commit("saveLink", saveLink ),
    removeAllSegment: ({ commit }, removeAllSegment) => commit("removeAllSegment", removeAllSegment ),
    removeMarker: ({ commit }, removeMarker) => commit("removeMarker", removeMarker ),
    setLink: ({ commit }, { id, link }) => commit("setLink", { id, link }),
    unsetLink: ({ commit, state }, id) => {
        const links = state.currentLinks;
        if (links[id]) commit("unsetLink", id);
    },
    setZoom: (ctx, payload) => {
        const zoom = payload.zoom;

        window.axios
            .post(route("map-data.store"), {
                map: {
                    zoom: zoom,
                },
            })
            .then(() => {
                ctx.commit("setZoom", zoom);
            })
            .catch((error) => {
                console.error("map.js Module", error);
            });
    },
    setCenter: (ctx, payload) => {
        const center = payload.center;

        window.axios
            .post(route("map-data.store"), {
                map: {
                    center: center,
                },
            })
            .then(() => {
                ctx.commit("setCenter", center);
            })
            .catch((error) => {
                console.error("map.js Module", error);
            });
    },
    setDefaultLocation: async (ctx, payload) => {
        const location = payload.defaultLocation;

        window.axios
            .post(route("map-data.store"), {
                map: {
                    defaultLocation: location,
                },
            })
            .then(() => {
                ctx.commit("setDefaultLocation", location);
            })
            .catch((error) => {
                console.error("map.js Module", error);
            });
    },
    setView: async (ctx, payload) => {
        const NE = payload.getNorthEast();
        const SW = payload.getSouthWest();

        ctx.commit("setView", [
            [NE.lat, NE.lng],
            [SW.lat, SW.lng],
        ]);
    },
};

const mutations = {
    selectMarker: (state, marker) => (state.selectedMarker = marker),
    selectMarkerColor: (state, color) => (state.selectedMarkerColor = color),
    selectedMarkerType: (state, type) => (state.selectedMarkerType = type),
    focusMarker: (state, marker) => (state.focusedMarker = marker),
    saveLink: (state, saveLink) => {(state.saveLink = saveLink, state.startLinks = saveLink)},
    removeAllSegment: (state, removeAllSegment) => (state.removeAllSegment = removeAllSegment),
    removeMarker: (state, removeMarker) => (state.removeMarker = removeMarker),
    setLink: (state, { id, link }) => {(state.currentLinks[id] = link, state.currentLinks[id]['data'] = {})},
    unsetLink: (state, id) => delete state.currentLinks[id],
    startLinks: (state) => {(state.currentLinks = {}, state.startLinks = true)},

    setCenter: (state, center) => (state.center = [center.lat, center.lng]),
    setZoom: (state, zoom) => (state.zoom = zoom),
    setDefaultLocation: (state, location) =>
        (state.defaultLocation = [location.lat, location.lng]),
    setView: (state, view) => (state.view = view),
};

export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations,
};
