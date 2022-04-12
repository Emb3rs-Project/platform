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

                    <!-- Simulation Steps -->
                    <!-- <h2 class="pt-2">
                        Simulation Steps
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-green-100 text-gray-800"
                        >
                            Start
                        </span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-gray-100 text-gray-800"
                        >
                            Middle
                        </span>

                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-red-100 text-gray-800"
                        >
                            Finish
                        </span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-orange-100 text-gray-800"
                        >
                            User
                        </span>
                    </h2>
                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs"
                        :class="{
                            'bg-gray-50':
                                index != 0 && index != stepInfo.length - 1,
                            'bg-green-100': index == 0,
                            'bg-red-100': index == stepInfo.length - 1,
                        }"
                        v-for="(step, index) of stepInfo"
                        :key="step"
                    >
                        {{ step.step }} - {{ step.module }} (<b>{{
                            step.function
                        }}</b
                        >)
                    </div> -->

                    <h2 class="text-md font-semibold">Modules Inputs</h2>
                    <!-- CF MODULE INPUTS -->
                    <!-- cf:sink:convert_sinks -->
                    <div
                        v-if="hasCFConvertSink && false"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-100"
                    >
                        <p class="font-mono font-bold">cf:sink:convert_sinks</p>
                        <p class="my-2 text-center">Doesn't need Inputs</p>
                    </div>
                    <!-- cf:source_detailed:convert_sources -->
                    <div
                        v-if="hasCFConvertSource && false"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-100"
                    >
                        <p class="font-mono font-bold">
                            cf:source_detailed:convert_sources
                        </p>
                        <p class="my-2 text-center">Doesn't need Inputs</p>
                    </div>
                    <!-- GIS MODULE INPUTS -->
                    <!-- gis:create_network -->
                    <div
                        v-if="hasGISCreateNetwork"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-50"
                    >
                        <p class="font-mono font-bold">gis:create_network</p>

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
                                            Network Resolution
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="['low', 'high']"
                                            label="name"
                                            value="id"
                                            v-model="
                                                form.extra.input_data
                                                    .network_resolution
                                            "
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Network Resolution
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- gis:optimize_network -->
                    <div
                        v-if="hasGISOptimizeNetwork"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-50"
                    >
                        <p class="font-mono font-bold">gis:optimize_network</p>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                            v-for="{
                                path,
                                label,
                                description,
                            } of GISOptimizeNetworkProps"
                            :key="path"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="form.extra.input_data[path]"
                                    :description="description"
                                    :label="label"
                                    :required="true"
                                />
                            </div>
                        </div>
                    </div>
                    <!-- TEO MODULE INPUTS -->
                    <!-- teo:buildmodel -->
                    <div
                        v-if="hasTEOBuildModel"
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md text-xs bg-gray-100"
                    >
                        <p class="font-mono font-bold">teo:buildmodel</p>

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
                                            Region
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="COUNTRIES"
                                            label="name"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets.REGION
                                            "
                                            :multiple="true"
                                            :reduce="(country) => country.name"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It sets the regions to be modelled, e.g.
                                        different countries, cities, counties
                                        etc. For the prupose of this analysis it
                                        is enough to have one region name. For
                                        each of them, the supply-demand balances
                                        for all the energy vectors are ensured.
                                        In some occasions it might be
                                        computationally more convenient to model
                                        different countries within the same
                                        region and differentiate them simply by
                                        creating ad hoc fuels and technologies
                                        for each of them.
                                    </p>
                                </div>
                            </div>
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
                                            Emission
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="['co2']"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets.EMISSION
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It includes any kind of emission
                                        potentially deriving from the operation
                                        of the defined technologies. Typical
                                        examples would include atmospheric
                                        emissions of greenhouse gasses, such as
                                        CO2. The user must fill in 'co2' as a
                                        mandatory entry. Other entries are also
                                        allowed
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <TextInput
                                    v-model="teo_timeslice"
                                    description="It represents the time split of each modelled year, therefore the time resolution of the model. between [1-8784]"
                                    label="Timeslice"
                                    :required="true"
                                />
                            </div>
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
                                            Year
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[]"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets.YEAR
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                            :reduce="(a) => Number(a)"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It represents the time frame of the
                                        model, it contains all the years to be
                                        considered in the analysis.
                                    </p>
                                </div>
                            </div>
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
                                            Mode of operation
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[
                                                1, 2, 3, 4, 5, 6, 7, 8, 9, 10,
                                                11, 12, 13, 14, 15, 16, 17, 18,
                                            ]"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets
                                                    .MODE_OF_OPERATION
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                            :reduce="(a) => Number(a)"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It defines the number of modes of
                                        operation that the technologies can
                                        have. If a technology can have various
                                        input or output fuels and it can choose
                                        the mix (i.e. any linear combination) of
                                        these input or output fuels, each mix
                                        can be accounted as a separate mode of
                                        operation. The user must input atleast 1
                                        mode of operation. There muts be two
                                        modes of operation if storage is used in
                                        the model
                                    </p>
                                </div>
                            </div>
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
                                            Storage
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <VSelect
                                            :options="[]"
                                            v-model="
                                                form.extra.input_data
                                                    .platform_sets.STORAGE
                                            "
                                            :multiple="true"
                                            :taggable="true"
                                        />
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        It includes storage facilities in the
                                        model.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="
                                form.extra.input_data.platform_sets.EMISSION
                                    .length > 0
                            "
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Annual emission limit
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        v-for="emission of form.extra.input_data
                                            .platform_sets.EMISSION"
                                        :key="emission"
                                        class="mt-1 relative rounded-md shadow-sm"
                                    >
                                        <div
                                            class="mt-1 flex rounded-md shadow-sm"
                                        >
                                            <input
                                                type="text"
                                                v-model="
                                                    teo_platform_annual_emission_limit[
                                                        emission
                                                    ]
                                                "
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300"
                                                placeholder="15000"
                                            />
                                            <span
                                                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm"
                                            >
                                                {{ emission }}
                                            </span>
                                        </div>
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    >
                                        Annual upper limit for a specific
                                        emission generated in the whole modelled
                                        region.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="
                                form.extra.input_data.platform_sets.STORAGE
                                    .length > 0
                            "
                            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                        >
                            <div class="sm:col-span-3">
                                <div>
                                    <div class="flex justify-between">
                                        <label
                                            for="sim_metadata"
                                            class="block text-sm font-medium text-gray-700"
                                        >
                                            Platform Storages
                                        </label>
                                        <span
                                            class="text-sm text-gray-500"
                                            id="input-required"
                                        >
                                            Required
                                        </span>
                                    </div>
                                    <div
                                        v-for="storage of form.extra.input_data
                                            .platform_sets.STORAGE"
                                        :key="storage"
                                        class="my-5 relative rounded-md shadow-sm"
                                    >
                                        <h2 class="font-bold">
                                            for Storage : {{ storage }}
                                        </h2>
                                        <div
                                            v-for="prop of teo_platform_storages_props"
                                            :key="prop"
                                            class="mt-1 flex rounded-md shadow-sm"
                                        >
                                            <input
                                                type="text"
                                                v-model="
                                                    teo_platform_storages[
                                                        `${storage}_${prop.path}`
                                                    ]
                                                "
                                                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300"
                                                :placeholder="prop.default"
                                                required
                                            />
                                            <span
                                                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm"
                                            >
                                                {{ prop.path }}
                                            </span>
                                        </div>
                                    </div>
                                    <p
                                        class="mt-2 text-sm text-gray-500 text-justify"
                                    ></p>
                                </div>
                            </div>
                        </div>
                    </div>
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
            <pre>{{ form.extra.input_data.platform_storages }}</pre>
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
    if (!form.simulation_metadata) return [];
    const steps = Object.keys(form.simulation_metadata.data).filter(
        (a) => !["start", "type"].includes(a)
    );

    return steps.map((s) => ({
        step: s,
        module: form.simulation_metadata.data[s].module.name,
        function: form.simulation_metadata.data[s].function,
    }));
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
const hasMMLongTerm = computed(() =>
    stepInfo.value.find((a) => a.function === "market:longterm")
);
const hasBMBase = computed(() =>
    stepInfo.value.find((a) => a.function === "business:bm")
);
</script>
