<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Simulations
            </h2>
        </template>
        <div class="grid grid-cols-2 grid-flow-row">
            <div>
                <table class="table-fixed">
                    <thead>
                        <tr>
                            <th
                                class="text-xl"
                                v-for="column in tableColumns"
                                :key="column"
                            >
                                {{column}}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="bg-white-500 hover:bg-orange-500 rounded text-xl outline-none"
                            v-for="simulation in simulations"
                            :key="simulation.id"
                        >
                            <td>
                                {{simulation.id}}
                            </td>
                            <td>
                                {{simulation.name}}
                            </td>
                            <td>
                                {{simulation.type}}
                            </td>
                            <td>
                                {{simulation.size}}
                            </td>
                            <td>
                                {{simulation.fluid}}
                            </td>
                            <td>
                                {{simulation.temperature}}
                            </td>
                            <td>
                                {{simulation.presure}}
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div>
                <div class="w-full">
                    <leaflet-map></leaflet-map>
                </div>
            </div>
        </div>

        <div>
        </div>

        <div>
            <jet-link-button path="simulations.create">
                Create New Location
            </jet-link-button>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import JetLinkButton from "@/Jetstream/LinkButton";
import LeafletMap from "@/Components/LeafletMap";

export default {
    components: {
        AppLayout,
        JetLinkButton,
        LeafletMap
    },
    data() {
        return {
            // tableColumns: [
            //     { name: "id" },
            //     { name: "name" },
            //     { name: "type" },
            //     { name: "size" },
            //     { name: "fuild" },
            //     { name: "temperature" },
            //     { name: "presure" }
            // ],
            tableColumns: [
                "id",
                "name",
                "type",
                "size",
                "fuild",
                "temperature",
                "presure"
            ],
            simulations: [{
                id: "1",
                name: "simulation-2",
                type: "yes",
                size: "true",
                fuild: "water",
                temperature: "500 C",
                presure: "10 psi"
            }]
        }
    }
};
</script>

<style>
</style>
