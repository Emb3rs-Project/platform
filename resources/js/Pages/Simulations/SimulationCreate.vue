<template>
    <AppLayout>
        <AmazingMap preview simulation/>
        <div v-if="form.simulation_metadata" class="absolute left-2 bg-gray-50 opacity-80 hover:opacity-100
                z-10 overflow-y-hidden inset-y-0 mt-20 h-40 rounded-md">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- <h2 class="text-lg font-bold">Simulation Details</h2> -->

                <div class="py-5 text-left">
                    <h2 class="text-md font-semibold">Simulation Metadata</h2>
                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 bg-gray-50 text-xs">
                        Name : <b>{{ form.simulation_metadata.name }}</b> <br />
                        Type : <b>{{ form.simulation_metadata.data.type }}</b>
                    </div>

                    <!-- PLACE HERE INFO ABOUT SIMULATION -->
                </div>
            </div>
        </div>
        <SlideOver title="Create a Simulation" headerBackground="bg-purple-600" subtitleTextColor="text-gray-100"
            alwaysOpen>
            <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div class="sm:col-span-3">
                    <TextInput v-model="form.name" description="The name that this Simulation is going to have."
                        label="Name" :required="true" />
                </div>
                <JetInputError v-show="form.errors.name" :message="form.errors.name" class="mt-2" />
            </div>

            <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-between">
                            <label for="sim_metadata" class="block text-sm font-medium text-gray-700">
                                Simulation Metadata
                            </label>
                            <span class="text-sm text-gray-500" id="input-required">
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect :options="simulation_metadata" label="name" value="id"
                                v-model="form.simulation_metadata" />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            The simulation Metadata to use
                        </p>
                    </div>
                </div>
                <JetInputError v-show="form.errors.name" :message="form.errors.name" class="mt-2" />
            </div>

            <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-end">
                            <button class="bg-green-600 py-1 px-2 my-2 rounded-md text-white" @click="selectAllSinks">Select All</button>
                        </div>
                        <div class="flex justify-between">
                            <label for="sim_metadata" class="block text-sm font-medium text-gray-700">
                                Sinks
                            </label>
                            <span class="text-sm text-gray-500" id="input-required">
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect :options="sinks" label="name" value="id" :multiple="true"
                                v-model="form.extra.sinks" />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            Sinks to use in this Simulation
                        </p>
                    </div>
                </div>
                <JetInputError v-show="form.errors.name" :message="form.errors.name" class="mt-2" />
            </div>

            <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-end">
                            <button class="bg-green-600 py-1 px-2 my-2 rounded-md text-white" @click="selectAllSources">Select All</button>
                        </div>
                        <div class="flex justify-between">
                            <label for="sim_metadata" class="block text-sm font-medium text-gray-700">
                                Sources
                            </label>
                            <span class="text-sm text-gray-500" id="input-required">
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect :options="sources" label="name" value="id" :multiple="true"
                                v-model="form.extra.sources" />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            Sinks to use in this Simulation
                        </p>
                    </div>
                </div>
                <JetInputError v-show="form.errors.name" :message="form.errors.name" class="mt-2" />
            </div>

            <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-end">
                            <button class="bg-green-600 py-1 px-2 my-2 rounded-md text-white" @click="selectAllLinks">Select All</button>
                        </div>
                        <div class="flex justify-between">
                            <label for="sim_metadata" class="block text-sm font-medium text-gray-700">
                                Links
                            </label>
                            <span class="text-sm text-gray-500" id="input-required">
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect :options="links" label="name" value="id" :multiple="true"
                                @option:deselected="onDeselected" @option:selected="onSelected"
                                v-model="form.extra.links" />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            Links to use in this Simulation
                        </p>
                    </div>
                </div>
                <JetInputError v-show="form.errors.name" :message="form.errors.name" class="mt-2" />
            </div>

            <template #actions>
                <SecondaryOutlinedButton type="button" :disabled="form.processing" @click="onCancel">
                    Cancel
                </SecondaryOutlinedButton>
                <PrimaryButton @click="onSubmit" :disabled="form.processing">
                    Save
                </PrimaryButton>
            </template>
        </SlideOver>
    </AppLayout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout";
import JetInputError from "@/Jetstream/InputError";
import { computed, ref, watch } from "vue";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import mapUtils from "@/Utils/map.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

import { polygon, marker } from "leaflet";
import VSelect from "vue-select";
import { COUNTRIES } from "./data/countries";

export default {
  components: {
    AppLayout,
    AmazingMap,
    JetInputError,
    SlideOver,
    TextInput,
    PrimaryButton,
    SecondaryOutlinedButton,
    VSelect
  },

  props: {
    instances: {
      type: Array,
      required: true,
    },
    links: {
      type: Array,
      required: true,
    },
    project: {
      type: Object,
      required: true,
    },
    simulation_metadata: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();

    const selectedMarker = computed(
        () => store.getters["map/selectedMarker"]
    );
    const selectedLink = computed(
        () => store.getters["map/currentLinks"]
    );

    const form = useForm({
        name: "Simulation Name",
        simulation_metadata: props.simulation_metadata[1],
        extra: {
            input_data: {
                actorshare: [0.5, 0.1, 0.1, 0.1, 0.1, 0.1, 0, 0, 0, 0, 0],
                md: "pool",
                rls: [
                    ["source 1", "source 1 ext tech"],
                    ["source 2", "source 2 ext tech"],
                    ["source 3", "source 3 ext tech"],
                    ["source 4", "source 4 ext tech"],
                    ["sink 5", "sink 5 ext tech"],
                    ["sink 6", "sink 6 ext tech"],
                    ["sink 7", "sink 7 ext tech"],
                    ["sink 8", "sink 8 ext tech"],
                    ["sink 9", "sink 9 ext tech"],
                    ["sink 10", "sink 10 ext tech"],
                    ["sink 11", "sink 11 ext tech"]
                ],
                cost: null,
                gmax: null,
                lmax: null,
                util: null,
                fc_pip: 50,
                vc_pip: 700,
                network: null,
                chp_pars: null,
                el_price: null,
                agent_ids: null,
                fc_dig_st: 350,
                fc_dig_tr: 200,
                flow_temp: 100,
                objective: null,
                prod_diff: null,
                vc_dig_st: 700,
                vc_dig_tr: 500,
                vc_pip_ex: 1.3,
                water_den: 1000,
                offer_type: null,
                recurrence: null,
                block_offer: null,
                ground_temp: 8,
                nr_of_hours: 48,
                return_temp: 70,
                ambient_temp: 25,
                data_profile: null,
                el_dependent: null,
                invest_pumps: 0,
                vc_dig_st_ex: 1.1,
                vc_dig_tr_ex: 1.3,
                co2_emissions: null,
                co2_intensity: 25.0,
                discount_rate: [4, 5],
                heat_capacity: 4.18,
                horizon_basis: null,
                platform_sets: {
                    "REGION": [
                        "Greece"
                    ],
                    "EMISSION": [
                        "co2"
                    ],
                    "TIMESLICE": [
                        1,
                        2,
                        3,
                        4,
                        5,
                        6,
                        7,
                        8,
                        9,
                        10,
                        11,
                        12,
                        13,
                        14,
                        15,
                        16,
                        17,
                        18,
                        19,
                        20,
                        21,
                        22,
                        23,
                        24,
                        25,
                        26,
                        27,
                        28,
                        29,
                        30,
                        31,
                        32,
                        33,
                        34,
                        35,
                        36,
                        37,
                        38,
                        39,
                        40,
                        41,
                        42,
                        43,
                        44,
                        45,
                        46,
                        47,
                        48
                    ],
                    "YEAR": [
                        2023
                    ],
                    "MODE_OF_OPERATION": [
                        1,
                        2
                    ],
                    "STORAGE": [
                        "tankstorage"
                    ],
                    "platform_budget_limit": 150000000.0
                },
                "start_datetime": null,
                "is_in_community": [],
                "prod_diff_option": null,
                "project_duration": 10,
                "platform_storages": [
                    {
                        "storage": "tankstorage",
                        "capital_cost_storage": 750,
                        "dicount_rate_sto": 0.04,
                        "operational_life_sto": 100,
                        "storage_max_charge": 1500,
                        "storage_max_discharge": 1500,
                        "l2d": 0,
                        "tag_heating": 1,
                        "tag_cooling": 0,
                        "storage_return_temp": 50,
                        "storage_supply_temp": 80,
                        "storage_ambient_temp": 15,
                        "residual_storage_capacity": 0,
                        "max_storage_capacity": 1500,
                        "storage_level_start": 0,
                        "u_value": 0.21
                    }
                ],
                community_settings: null,
                network_resolution: "low",
                yearly_demand_rate: null,
                factor_street_terrain: 0.1,
                factor_street_overland: 0.4,
                platform_annual_emission_limit: [
                    {
                        emission: "co2",
                        annual_emission_limit: 15000000000000
                    }
                ],
                ex_grid: [],
                user: {
                    md: "centralized",
                    horizon_basis: "years",
                    recurrence: 1,
                    data_profile: "hourly",
                    yearly_demand_rate: 0.05,
                    start_datetime: "2018-01-01",
                    prod_diff_option: "noPref",
                    util: [0.7]
                }
            },
            links: [],
            sinks: [],
            sources: [],
            steps: 0,
        },
    });

    const onSubmit = () => {
        form.post(route("projects.simulations.store", { id: props.project.id }));
    };
    const onCancel = () => {
        form.extra.links.forEach((link) => onDeselected(link));
        form.extra.links = [];
        form.extra.sinks = [];
        form.extra.sources = [];
    };

    const selectAllSinks = () => { form.extra.sinks = sinks.value }
    const selectAllSources = () => { form.extra.sources = sources.value }
    const selectAllLinks = () => { form.extra.links = links.value }

    const project_poly = polygon(props.project.data.polygon);
    const project_instances = props.instances.filter((r) =>
        project_poly
            .getBounds()
            .contains(marker(r.location.data.center).getLatLng())
    );

    const sinks = computed(() => {
        if (!project_instances) return [];
        return project_instances.filter((i) => i.template.category.type == "sink");
    });

    const sources = computed(() => {
        if (!project_instances) return [];
        return project_instances.filter((i) => i.template.category.type == "source");
    });

    const links = computed(() => {
        return props.links;
    });

    const stepInfo = computed(() => {
        return [];
        // if (!form.simulation_metadata) return [];
        // const steps = Object.keys(form.simulation_metadata.data).filter(
        //     (a) => !["start", "type"].includes(a)
        // );

        // return steps.map((s) => ({
        //     step: s,
        //     module: form.simulation_metadata.data[s].module.name,
        //     function: form.simulation_metadata.data[s].function,
        // }));
    });

    const onSelected = (value) => {
        value.forEach(element => {
            if (!selectedLink.value[element.id]) {
                store.dispatch("map/setLink", {
                    id: element.id,
                    link: element,
                });
            }
        });
    };

    const onDeselected = (value) => {
        store.dispatch("map/unsetLink", value.id);
    };

    watch(
        form.extra,
        (instances, oldInstances) => {
            if (oldInstances) {
                mapUtils.setMarker(instances);

                store.dispatch("map/selectMarker", {
                    marker: null,
                    type: null,
                });
            }
        },
        { immediate: true, deep: true }
    );

    watch(
        selectedMarker,
        () => {
            const type = store.getters["map/selectedMarkerType"];
            let index = -1;
            switch (type) {
                case 'sources':
                    index = form.extra.sources.findIndex((e) => JSON.stringify(e.location.data.center) == JSON.stringify(selectedMarker.value));
                    if (index != -1) {
                        form.extra.sources.splice(index, 1)
                    } else {
                        let elementSource = sources.value.find((e) => JSON.stringify(e.location.data.center) == JSON.stringify(selectedMarker.value));
                        if (elementSource) {
                            form.extra.sources.push(elementSource);
                        } else {
                            store.commit("objects/setNotify", {
                                title: 'Source',
                                text: 'Marker outside the simulation area',
                                type: 'warning'
                            });
                        }
                    }
                    break;
                case 'sinks':
                    index = form.extra.sinks.findIndex((e) => JSON.stringify(e.location.data.center) == JSON.stringify(selectedMarker.value));
                    if (index != -1) {
                        form.extra.sinks.splice(index, 1);
                    } else {
                        let elementSink = sinks.value.find((e) => JSON.stringify(e.location.data.center) == JSON.stringify(selectedMarker.value));
                        if (elementSink) {
                            form.extra.sinks.push(elementSink);
                        } else {
                            store.commit("objects/setNotify", {
                                title: 'Sink',
                                text: 'Marker outside the simulation area',
                                type: 'warning'
                            });
                        }
                    }
                    break;
                case 'links':
                    index = form.extra.links.findIndex((e) => e.id == selectedMarker.value.id);
                    if (index != -1) {
                        store.dispatch("map/unsetLink", selectedMarker.value.id);
                        form.extra.links.splice(index, 1)
                    } else {
                        store.dispatch("map/setLink", {
                            id: selectedMarker.value.id,
                            link: selectedMarker.value,
                        });
                        form.extra.links.push(links.value.find((e) => e.id == selectedMarker.value.id));
                    }
                    break;
            }
        },
        { immediate: true, deep: true }
    );

    return {
        form,
        links,
        sinks,
        sources,
        onDeselected,
        onSelected,
        onSubmit,
        onCancel,
        selectAllLinks,
        selectAllSinks,
        selectAllSources
    };
  },
}
</script>
