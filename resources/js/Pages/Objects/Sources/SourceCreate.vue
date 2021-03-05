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
        <div class="flex p-5 md:h-auto">
            <div class="w-6/12 h-3/4 md:overflow-y-auto md:pr-4">
                <input-row
                    heading="Details | Source"
                    desc="Enter a name for your source"
                >
                    Source Name
                </input-row>

                <select-row
                    desc="Choose how you want to define your Source. Dont worry if you cant find a prefect match. Choose the one that best describes your source."
                    :options="typeOptions"
                    class="mt-5"
                >
                    The type of source?
                </select-row>

                <select-row
                    desc="Try to identify the size of the source"
                    :options="sizeOptions"
                    class="mt-5"
                >
                    The size of the source?
                </select-row>

                <select-row
                    desc="Try to identify the fluid of the source"
                    :options="fluidOptions"
                    class="mt-5"
                >
                    The fluid of the source?
                </select-row>

                <select-row
                    desc="Try to identify the temperature of the source"
                    :options="temperatureOptions"
                    class="mt-5"
                >
                    The temperature of the source?
                </select-row>

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
import SelectRow from "@/Components/SelectRow";
import JetLinkButton from "@/Jetstream/LinkButton";

export default {
    components: {
        AppLayout,
        LeafletMap,
        InputRow,
        RadioRow,
        SelectRow,
        JetLinkButton,
    },
    data() {
        return {
            typeOptions: [
                'Metallurgy',
                'Timber',
            ],
            sizeOptions: [
                'Small',
                'Medium',
                'Normal',
                'Big',
                'Very Big',
            ],
            fluidOptions: [
                'Water',
                'Steam',
                'Petrolium',
            ],
            temperatureOptions: [
                'Cold',
                'Little Hot',
                'Very Hot',
            ],
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
