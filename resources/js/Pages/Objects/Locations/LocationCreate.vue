<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Objects | Locations
            </h2>
        </template>
        <div class="p-5">
            <h1 class="text-lg font-bold">New Location</h1>
        </div>
        <div class="flex p-5 md:h-auto">
            <div class="w-6/12 h-3/4 md:overflow-y-auto md:pr-4">
                <input-row
                    heading="Details | Location"
                    desc="Enter a name for your location"
                >
                    Location Name
                </input-row>

                <input-row
                    desc="Choose how you want to define your Location"
                    class="mt-5"
                >
                    What type of location?
                </input-row>

                <radio-row
                    desc="Select how you want to share your Location"
                    :options="radioOptions"
                    class="mt-5"
                >
                    Location sharing options
                </radio-row>
            </div>

            <div class="w-6/12">
                <leaflet-map></leaflet-map>
            </div>
        </div>
        <div class="w-full my-5 px-16 flex justify-end">
            <jet-link-button path="objects.sources.create"> Create </jet-link-button>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import JetLinkButton from "@/Jetstream/LinkButton";

export default {
    components: {
        AppLayout,
        LeafletMap,
        InputRow,
        RadioRow,
        JetLinkButton,
    },
    data() {
        return {
            radioOptions: [
                'I want to share my Location with my Institution',
                'I want to share my Location with everyone'
            ]
        }
    }
};
</script>

<style>
</style>
