<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Objects | Sources
            </h2>
        </template>
        <div class="p-5">
            <h1 class="text-lg font-bold">New Source</h1>
        </div>
        <form @submit.prevent="submit">
            <div class="flex p-5 h-content gap-2">
                <div class="w-6/12 md:overflow-y-auto md:pr-4">
                    <input-row
                        heading="Details | Source"
                        desc="Enter a name for your Source"
                        v-model="form.sourceName"
                    >
                        Source Name
                    </input-row>
                    <jet-input-error
                        :message="form.errors.sourceName"
                        class="mt-2"
                    />

                    <select-row
                        class="mt-5"
                        desc="Choose how you want to define your Source. Dont worry if you cant find a prefect match. Choose the one that best describes your source."
                        :options="sourceTypes"
                        v-model="form.sourceType"
                    >
                        Source Type
                    </select-row>

                    <select-row
                        class="mt-5"
                        desc="Try to idetify the size of the Source"
                        :options="sourceSizes"
                        v-model="form.sourceSize"
                    >
                        Source Size
                    </select-row>

                    <select-row
                        class="mt-5"
                        desc="Try to idetify the fluid of the Source"
                        :options="sourceFluids"
                        v-model="form.sourceFluid"
                    >
                        Source Fluid Type
                    </select-row>

                    <select-row
                        class="mt-5"
                        desc="Try to idetify the temperature of the Source"
                        :options="sourceTemperatures"
                        v-model="form.sourceTemperature"
                    >
                        Source Temperature
                    </select-row>

                    <select-row
                        class="mt-5"
                        desc="Try to idetify the presure of the Source"
                        :options="sourcePressures"
                        v-model="form.sourcePressure"
                    >
                        Source Presure
                    </select-row>

                    <select-row
                        class="mt-14"
                        heading="Details | Source + Location"
                        desc="Associate this Source with a Location"
                        :options="sourceLocations"
                        v-model="form.sourceLocation"
                    >
                        Location Name
                    </select-row>
                </div>

                <div class=" w-6/12">
                    <leaflet-map></leaflet-map>
                </div>
            </div>
            <div class="w-full my-5 px-16 flex justify-end">
                <jet-button :disabled="form.processing">
                    Create Source
                </jet-button>
            </div>
        </form>
    </app-layout>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3'

import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";

export default {
    components: {
        AppLayout,
        LeafletMap,
        InputRow,
        RadioRow,
        SelectRow,
        JetButton,
        JetInputError
    },

    setup() {
        const form = useForm({
            sourceName: '',
            sourceType: 'Metallurgy',
            sourceSize: 'Small',
            sourceFluid: 'Water',
            sourceTemperature: 'Cold',
            sourcePressure: 'Low',
            sourceLocation: 'Greece',
        });

        const sourceTypes = [
            'Metallurgy',
            'Timber',
        ];
        const sourceSizes = [
            'Small',
            'Medium',
            'Normal',
            'Big',
            'Very Big',
        ];
        const sourceFluids = [
            'Water',
            'Steam',
            'Petrolium',
        ];
        const sourceTemperatures = [
            'Cold',
            'Little Hot',
            'Very Hot',
        ];
        const sourcePressures = [
            'Low',
            'Normal',
            'High',
        ];
        const sourceLocations = [
            'Greece',
            'Portugal',
        ];

        const submit = () => {
            console.log('submitting');
            // TODO: Uncomment when backend is ready to accept data
            // form.value.post(route('objects.locations.create'));
        }

        return {
            form,
            sourceTypes,
            sourceSizes,
            sourceFluids,
            sourceTemperatures,
            sourcePressures,
            sourceLocations,
            submit
        }
    }
};
</script>

<style>
</style>
