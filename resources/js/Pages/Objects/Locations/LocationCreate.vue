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
        <form @submit.prevent="submit">
            <div class="flex p-5 h-content gap-2">
                <div class="w-6/12 md:overflow-y-auto md:pr-4">
                    <input-row
                        heading="Details | Location"
                        desc="Enter a name for your location"
                        v-model="form.locationName"
                    >
                        Location Name
                    </input-row>

                    <select-row
                        class="mt-5"
                        desc="Choose how you want to define your Location"
                        :options="locationTypes"
                        v-model="form.locationType"
                    >
                        What type of location?
                    </select-row>

                    <radio-row
                        class="mt-5"
                        desc="Select how you want to share your Location"
                        :options="locationSharingOptions"
                        v-model="form.locationSharing"
                    >
                        Location sharing options
                    </radio-row>

                </div>

                <div class=" w-6/12">
                    <leaflet-map></leaflet-map>
                </div>
            </div>
            <div class="w-full my-5 px-16 flex justify-end">
                <jet-button :disabled="form.processing">Create Location</jet-button>
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
        const form = useForm({
            locationName: '',
            locationType: 'Point',
            locationSharing: ''
        });

        const locationTypes = [
            'Point',
            'Circle',
            'Polygon',
        ];
        const locationSharingOptions = [
            'I want to share my Location with my Institution',
            'I want to share my Location with everyone'
        ];

        const submit = () => {
            console.log('submitting');
            // TODO: Uncomment when backend is ready to accept data
            // form.value.post(route('objects.locations.create'));
        }

        return {
            form,
            locationTypes,
            locationSharingOptions,
            submit
        }
    }
};
</script>

<style>
</style>
