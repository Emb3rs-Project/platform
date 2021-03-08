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
                <jet-input-error
                    :message="form.errors.locationName"
                    class="mt-2"
                />

                <select-row
                    class="mt-5"
                    desc="Choose how you want to define your Location"
                    :options="locationTypes"
                    v-model="locationType"
                >
                    What type of location?
                </select-row>
                <jet-input-error
                    :message="form.errors.locationType"
                    class="mt-2"
                />

                <radio-row
                    class="mt-5"
                    desc="Select how you want to share your Location"
                    :options="locationSharingOptions"
                    v-model="locationSharing"
                >
                    Location sharing options
                </radio-row>
                <jet-input-error
                    :message="form.errors.locationSharing"
                    class="mt-2"
                />
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
        JetInputError
    },
    setup() { }, // TODO: use tthe composition api
    data() {
        return {
            locationName: '',
            locationType: 'Point',
            locationSharing: '',

            locationTypes: [
                'Point',
                'Circle',
                'Polygon',
            ],
            locationSharingOptions: [
                'I want to share my Location with my Institution',
                'I want to share my Location with everyone'
            ],

            form: this.$inertia.form({
                locationName: null,
                locationType: null,
                locationSharing: null,
            }),
        }
    },
    methods: {
        createLocation() {
            this.form.post(route(''), {
                errorBag: 'createLocation',
                preserveScroll: true
            });
        }
    },
    watch: {
        locationName(newLocationName, oldLocationName) {
            console.log(newLocationName);
        },
        locationType(newLocationType, oldlocationType) {
            console.log(oldlocationType, newLocationType);
        },
        locationSharing(newLocationSharing, oldLocationSharing) {
            console.log(oldLocationSharing, newLocationSharing);
        },
    }

};
</script>

<style>
</style>
