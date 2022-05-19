<template>
    <AppLayout>
        <div class="bg-white h-screen overflow-y-scroll">
            <div class="w-1/2 py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <h2 class="text-lg font-bold">Simulation Details</h2>

                <div v-if="form.simulation_metadata" class="py-5 text-left">
                    <h2 class="text-md font-semibold">Simulation Metadata</h2>
                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 bg-gray-50 text-xs"
                    >
                        Name : <b>{{ form.simulation_metadata.name }}</b> <br />
                        Type : <b>{{ form.simulation_metadata.data.type }}</b>
                    </div>

                   <!-- PLACE HERE INFO ABOUT SIMULATION -->
                </div>
            </div>
        </div>

        <SlideOver
            title="Create a Simulation"
            headerBackground="bg-purple-600"
            subtitleTextColor="text-gray-100"
            alwaysOpen
        >
            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <TextInput
                        v-model="form.name"
                        description="The name that this Simulation is going to have."
                        label="Name"
                        :required="true"
                    />
                </div>
                <JetInputError
                    v-show="form.errors.name"
                    :message="form.errors.name"
                    class="mt-2"
                />
            </div>

            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-between">
                            <label
                                for="sim_metadata"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Simulation Metadata
                            </label>
                            <span
                                class="text-sm text-gray-500"
                                id="input-required"
                            >
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect
                                :options="simulation_metadata"
                                label="name"
                                value="id"
                                v-model="form.simulation_metadata"
                            />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            The simulation Metadata to use
                        </p>
                    </div>
                </div>
                <JetInputError
                    v-show="form.errors.name"
                    :message="form.errors.name"
                    class="mt-2"
                />
            </div>

            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-between">
                            <label
                                for="sim_metadata"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Sinks
                            </label>
                            <span
                                class="text-sm text-gray-500"
                                id="input-required"
                            >
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect
                                :options="sinks"
                                label="name"
                                value="id"
                                :multiple="true"
                                v-model="form.extra.sinks"
                            />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            Sinks to use in this Simulation
                        </p>
                    </div>
                </div>
                <JetInputError
                    v-show="form.errors.name"
                    :message="form.errors.name"
                    class="mt-2"
                />
            </div>

            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-between">
                            <label
                                for="sim_metadata"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Sources
                            </label>
                            <span
                                class="text-sm text-gray-500"
                                id="input-required"
                            >
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect
                                :options="sources"
                                label="name"
                                value="id"
                                :multiple="true"
                                v-model="form.extra.sources"
                            />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            Sinks to use in this Simulation
                        </p>
                    </div>
                </div>
                <JetInputError
                    v-show="form.errors.name"
                    :message="form.errors.name"
                    class="mt-2"
                />
            </div>

            <template #actions>
                <SecondaryOutlinedButton
                    type="button"
                    :disabled="form.processing"
                    @click="onCancel"
                >
                    Cancel
                </SecondaryOutlinedButton>
                <PrimaryButton @click="onSubmit" :disabled="form.processing">
                    Save
                </PrimaryButton>
            </template>
        </SlideOver>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout";
import { useForm } from "@inertiajs/inertia-vue3";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import CheckboxRow from "@/Components/CheckboxRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";
import { computed, ref } from "vue";
import SiteHead from "@/Components/SiteHead.vue";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

import { polygon, marker } from "leaflet";
import VSelect from "vue-select";
import { COUNTRIES } from "./data/countries";

const props = defineProps({
    instances: Array,
    project: Object,
    simulation_metadata: Array,
});

const form = useForm({
    name: "Simulation Name",
    simulation_metadata: props.simulation_metadata[1],
    extra: {
        input_data: {
            network_resolution: "low",
            invest_pumps: 0,
            fc_dig_st: 350,
            vc_dig_st: 700,
            vc_dig_st_ex: 1.1,
            fc_dig_tr: 200,
            vc_dig_tr: 500,
            vc_dig_tr_ex: 1.3,
            ambient_temp: 25,
            ground_temp: 8,
            flow_temp: 100,
            return_temp: 70,
            heat_capacity: 4.18,
            water_den: 1000,
            fc_pip: 50,
            vc_pip: 700,
            vc_pip_ex: 1.3,
            factor_street_terrain: 0.1,
            factor_street_overland: 0.4,
            platform_sets: {
                REGION: [],
                EMISSION: [],
                TIMESLICE: [],
                YEAR: [],
                MODE_OF_OPERATION: [],
                STORAGE: [],
            },
            platform_annual_emission_limit: [],
            platform_storages: [],
            md: "pool",
            offer_type: null,
            prod_diff: null,
            network: null,
            el_dependent: null,
            nr_of_hours: 48,
            objective: null,
            community_settings: null,
            block_offer: null,
            is_in_community: {},
            chp_pars: null,
            el_price: null,
            start_datetime: null,
            util: null,
            rls: null,
            discount_rate: null,
            project_duration: null,
            co2_intensity: null,
            horizon_basis: null,
            data_profile: null,
            recurrence: null,
            yearly_demand_rate: null,
            prod_diff_option: null,
            agent_ids: null,
            co2_emissions: null,
            gmax: null,
            lmax: null,
            cost: null,
        },
        sinks: [],
        sources: [],
        steps: 0,
    },
});

const onSubmit = () => {
    form.extra.steps = stepInfo.value.length;
    form.extra.input_data.platform_annual_emission_limit = Object.keys(
        teo_platform_annual_emission_limit.value
    ).map((a) => ({
        emission: a,
        annual_emission_limit: Number(
            teo_platform_annual_emission_limit.value[a]
        ),
    }));

    form.extra.input_data.platform_sets.TIMESLICE = Array(teo_timeslice).map(
        (_, i) => i
    );

    form.extra.input_data.platform_storages =
        form.extra.input_data.platform_sets.STORAGE.map((a) => {
            const records = Object.keys(teo_platform_storages.value).filter(
                (key) => key.includes(a)
            );

            return records.reduce(
                (p, c) => {
                    p[c.replace(`${a}_`, "")] = teo_platform_storages.value[c];
                    return p;
                },
                { storage: a }
            );
        });
    if (!!form.extra.input_data.community_settings)
        form.extra.input_data.community_settings = JSON.parse(
            form.extra.input_data.community_settings
        );

    form.post(route("projects.simulations.store", { id: props.project.id }));
};
const onCancel = () => {};

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
    return project_instances.filter(
        (i) => i.template.category.type == "source"
    );
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

const hasCFConvertSink = computed(() =>
    stepInfo.value.find((a) => a.function === "cf:sink:convert_sinks")
);
const hasCFConvertSource = computed(() =>
    stepInfo.value.find(
        (a) => a.function === "cf:source_detailed:convert_sources"
    )
);

const hasGISCreateNetwork = computed(() =>
    stepInfo.value.find((a) => a.function === "gis:create_network")
);
const hasGISOptimizeNetwork = computed(() =>
    stepInfo.value.find((a) => a.function === "gis:optimize_network")
);
const GISOptimizeNetworkProps = [
    {
        path: "invest_pumps",
        label: "Investment Costs for Pumps",
        description: "Investment costs for pumps in EUR. Set to 0 by default.",
    },
    {
        path: "fc_dig_st",
        label: "Fixed Digging Cost for Street",
        description:
            "Fixed digging cost for streets in EUR/m. Set to 350 by default.",
    },
    {
        path: "vc_dig_st",
        label: "Variable Digging Cost for Street",
        description:
            "Variable digging cost for streets in EUR/m. Set to 700 by default.",
    },
    {
        path: "vc_dig_st_ex",
        label: "Exponent Street",
        description:
            "Exponent of the digging cost for street. Set to 1.1 by default.",
    },
    {
        path: "fc_dig_tr",
        label: "Fixed Digging Cost for Terrain",
        description:
            "Fixed digging cost for terrains in EUR/m. Set to 200 by default.",
    },
    {
        path: "vc_dig_tr",
        label: "Variable Digging Cost for Terrain",
        description:
            "Variable digging cost for terrains in EUR/m. Set to 500 by default.",
    },
    {
        path: "vc_dig_tr_ex",
        label: "Exponent Terrain",
        description:
            "Exponent of the digging cost for terrain. Set to 1.1 by default.",
    },
    {
        path: "ambient_temp",
        label: "Average Ambient Temperature",
        description:
            "Yearly average ambient temperature in °C. Set to 25 by default.",
    },
    {
        path: "ground_temp",
        label: "Average Ground Temperature",
        description:
            "Yearly average ground temperature in °C. Set to 8 by default.",
    },
    {
        path: "flow_temp",
        label: "Average Flow Temperature",
        description:
            "Yearly average flow temperature in °C. Set to 100 by default.",
    },
    {
        path: "return_temp",
        label: "Average Return Temperature",
        description:
            "Yearly average return temperature in °C. Set to 70 by default.",
    },
    {
        path: "heat_capacity",
        label: "Heat Capacity",
        description:
            "Heat capacity in J/kgK at a certain temperature (average of flow and return temperatures). Set to 4.18 by default.",
    },
    {
        path: "water_den",
        label: "Water Density",
        description:
            "Water density in kg/m3 at a certain temperature (average of flow and return temperatures). Set to 1000 by default.",
    },
    {
        path: "fc_pip",
        label: "Fixed Piping Cost",
        description:
            "Fixed component of the piping cost in EUR/m. Set to 50 by default.",
    },
    {
        path: "vc_pip",
        label: "Variable Piping Cost",
        description:
            "Fixed component of the piping cost in EUR/m. Set to 700 by default.",
    },
    {
        path: "vc_pip_ex",
        label: "Exponent Piping",
        description: "Exponent of the piping cost. Set to 1.3 by default.",
    },
    {
        path: "factor_street_terrain",
        label: "Cost Factor Street vs Terrain",
        description:
            "Determines how much cheaper it is to lay 1 m of pipe into a terrain than into a street. Expressed in decimals: 0.1 means it is 10% cheaper.",
    },
    {
        path: "factor_street_overland",
        label: "Cost Factor Street vs Overland",
        description:
            "Determines how much cheaper it is to place 1 m of the pipe over the ground than putting it into the street. Expressed in decimals: 0.4 means it is 40% cheaper.",
    },
];

const hasTEOBuildModel = computed(() =>
    stepInfo.value.find((a) => a.function === "teo:buildmodel")
);

const teo_timeslice = ref(48);
const teo_platform_annual_emission_limit = ref({});
const teo_platform_storages = ref({});
const teo_platform_storages_props = [
    {
        label: "capital cost storage",
        path: "capital_cost_storage",
        default: 100,
    },
    { label: "dicount rate sto", path: "dicount_rate_sto", default: 0.1 },
    {
        label: "operational life sto",
        path: "operational_life_sto",
        default: 100,
    },
    { label: "storage max charge", path: "storage_max_charge", default: 10000 },
    {
        label: "storage max discharge",
        path: "storage_max_discharge",
        default: 10000,
    },
    { label: "l2d", path: "l2d", default: 1 },
    { label: "tag heating", path: "tag_heating", default: 1 },
    { label: "tag cooling", path: "tag_cooling", default: 0.001 },
    { label: "storage return temp", path: "storage_return_temp", default: 50 },
    { label: "storage supply temp", path: "storage_supply_temp", default: 80 },
    {
        label: "storage ambient temp",
        path: "storage_ambient_temp",
        default: 20,
    },
    {
        label: "residual storage capacity",
        path: "residual_storage_capacity",
        default: 100,
    },
    {
        label: "max storage capacity",
        path: "max_storage_capacity",
        default: 450000,
    },
    { label: "storage level start", path: "storage_level_start", default: 1 },
    { label: "u value", path: "u_value", default: 0.14 },
];

const hasMMShortTerm = computed(() =>
    stepInfo.value.find((a) => a.function === "market:shortterm")
);

const mm_util = ref({});

const hasMMLongTerm = computed(() =>
    stepInfo.value.find((a) => a.function === "market:longterm")
);
const hasBMBase = computed(() =>
    stepInfo.value.find((a) => a.function === "business:bm")
);
</script>
