<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Objects | Sink
            </h2>
        </template>
        <div class="p-5">
            <h1 class="text-lg font-bold">New Sink</h1>
        </div>
        <form @submit.prevent="submit">
            <div class="flex p-5 h-content gap-2">
                <div class="w-6/12 md:overflow-y-auto md:pr-4">
                    <input-row
                        heading="Details | Sink"
                        desc="Input a name for your Sink"
                        v-model="form.sinkName"
                    >
                        Sink Name
                    </input-row>
                    <jet-input-error
                        :message="form.errors.sinkName"
                        class="mt-2"
                    />

                    <select-row
                        class="mt-5"
                        desc="Try to identify the sector of the Sink"
                        :options="sinkSectors"
                        v-model="form.sinkSector"
                    >
                        Sink Sector
                    </select-row>

                    <select-row
                        class="mt-5"
                        desc="Try to identify the subsector of the Sink"
                        :options="sinkSubsectors"
                        v-model="form.sinkSubsector"
                    >
                        Sink SubSector
                    </select-row>

                    <select-row
                        class="mt-5"
                        desc="Try to identify the building type of the Sink"
                        :options="sinkBuildingTypes"
                        v-model="form.sinkBuildingType"
                    >
                        Sink Building Type
                    </select-row>

                    <input-row
                        class="mt-5"
                        desc="Input the custruction year of the Sink"
                        v-model="form.sinkBuildingConstructionYear"
                    >
                        Sink Construction Year
                    </input-row>
                    <jet-input-error
                        :message="form.errors.sinkBuildingConstructionYear"
                        class="mt-2"
                    />

                    <input-row
                        class="mt-5"
                        desc="Input the custruction year of the Sink"
                        v-model="form.sinkBuildingUsefulArea"
                    >
                        Sink Building Useful Area
                    </input-row>
                    <jet-input-error
                        :message="form.errors.sinkBuildingUsefulArea"
                        class="mt-2"
                    />

                    <select-row
                        class="mt-14"
                        heading="Details | Sink + Location"
                        desc="Associate this Sink with a Location"
                        :options="locations"
                        v-model="form.location"
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
                    Create Sink
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
        JetInputError,
    },
    setup() {
        const form = useForm({
            sinkName: '',
            sinkSector: 'Residential Building',
            sinkSubsector: 'SubSector 1',
            sinkBuildingType: 'Building 1',
            sinkBuildingConstructionYear: '',
            sinkBuildingUsefulArea: '',
            location: 'Greece',
        });

        const sinkSectors = [
            'Residential Building',
            'Neighbourhood',
        ];
        const sinkSubsectors = [
            'SubSector 1',
            'SubSector 2',
            'SubSector 3',
        ];
        const sinkBuildingTypes = [
            'Building 1',
            'Building 2',
            'Building 3',
            'Building 4',
        ];
        const locations = [
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
            sinkSectors,
            sinkSubsectors,
            sinkBuildingTypes,
            locations,
            submit
        }
    }
};
</script>

<style>
</style>
