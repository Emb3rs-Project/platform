<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                Objects | Locations
            </h2>
        </template>
        <div class="p-5">
            <h1 class="text-lg font-bold">New Location</h1>
        </div>
        <div class="flex p-5 h-content gap-2">
            <div class="w-6/12 md:overflow-y-auto md:pr-4">
                <input-row
                    heading="Details | Location"
                    desc="Enter a name for your location"
                    v-model="locationName"
                >
                    Location Name
                </input-row>

                <select-row
                    class="mt-5"
                    desc="Choose how you want to define your Location"
                    :options="locationTypes"
                    v-model="locationType"
                >
                    What type of location?
                </select-row>

                <radio-row
                    class="mt-5"
                    desc="Select how you want to share your Location"
                    :options="locationSharingOptions"
                    v-model="locationSharing"
                >
                    Location sharing options
                </radio-row>
            </div>

            <div class=" w-6/12">
                <leaflet-map></leaflet-map>
            </div>
        </div>
        <div class="w-full my-5 px-16 flex justify-end">
            <jet-button @click="createLocation">Create Location</jet-button>
        </div>
    </app-layout>
</template>

<script>
import { ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia'

import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from '@/Jetstream/InputError'


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
        const locationName = ref('');
        const locationType = ref('Point');
        const locationSharing = ref('');

        const locationTypes = [
            'Point',
            'Circle',
            'Polygon',
        ];
        const locationSharingOptions = [
            'I want to share my Location with my Institution',
            'I want to share my Location with everyone'
        ];

        const createLocation = () => {
            Inertia.post(route(''), {
                name: locationName.value,
                type: locationType.value,
                sharing: locationSharing.value,
            }, {
                errorBag: 'createLocation',
                preserveScroll: true
            })
        }

        watch(locationName, (locationName, oldLocationName) => {
            console.log(locationName);
        });

        watch(locationType, (locationType, oldLocationType) => {
            console.log(locationType);
        });

        watch(locationSharing, (locationSharing, oldLocationSharing) => {
            console.log(locationSharing);
        });

        return {
            locationName,
            locationType,
            locationSharing,
            locationTypes,
            locationSharingOptions,
            form,
            createLocation
        }
    }
};
</script>

<style>
</style>
