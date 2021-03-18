<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Project
            </h2>
        </template>

        <div class="grid grid-cols-1 p-5 gap-y-10">
            <div>
                <div class="grid grid-cols-3">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Project</h3>
                        <p class="mt-1 text-sm text-gray-600">Project Configuration</p>
                    </div>
                    <div class="col-span-2">
                        <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <input-row
                                    heading="Project"
                                    desc="The name of the Project"
                                    v-model="project.name"
                                    :disabled="true"
                                >
                                    Name
                                </input-row>

                                <input-row
                                    desc="The description of the Project"
                                    v-model="project.description"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Description
                                </input-row>

                                <input-row
                                    desc="The Project's creation date"
                                    v-model="project.created_at"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Created at
                                </input-row>

                                <input-row
                                    desc="The Project's last update date"
                                    v-model="project.updated_at"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Updated At
                                </input-row>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="project.location">
                <div class="grid grid-cols-3">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Location</h3>
                        <p class="mt-1 text-sm text-gray-600">Project Location Configuration</p>
                    </div>
                    <div class="col-span-2">
                        <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <input-row
                                    heading="Project's Location"
                                    desc="The name of the Location of this Project"
                                    v-model="project.location.name"
                                    :disabled="true"
                                >
                                    Name
                                </input-row>

                                <input-row
                                    desc="The description of the Location of this Project"
                                    v-model="project.description"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Description
                                </input-row>

                                <input-row
                                    desc="The Locations Project's creation date"
                                    v-model="project.location.created_at"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Created at
                                </input-row>

                                <input-row
                                    desc="The Project's last update date"
                                    v-model="project.location.updated_at"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Updated At
                                </input-row>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-for="(simulation, index) in simulations"
                :key="index"
            >
                <div class="grid grid-cols-3">
                    <div>
                        <div v-if="index == 0">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Simulation(s)</h3>
                            <p class="mt-1 text-sm text-gray-600">Project Simulation Configuration(s)</p>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <input-row
                                    heading="Simulation"
                                    desc="The status of the Simulation of this Project"
                                    v-model="simulation.status"
                                    :disabled="true"
                                >
                                    Status
                                </input-row>

                                <input-row
                                    desc="The Simulation's creation date"
                                    v-model="simulation.created_at"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Created at
                                </input-row>

                                <input-row
                                    desc="The Simulation's last update date"
                                    v-model="simulation.updated_at"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Updated At
                                </input-row>

                                <input-row
                                    heading="Simulation Target"
                                    desc="The Simulation's Target"
                                    v-model="simulation.targetData"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Target
                                </input-row>

                                <input-row
                                    heading="Simulation Type"
                                    desc="The name of the Simulation Type of this Project"
                                    v-model="simulation.simulation_type.name"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Name
                                </input-row>

                                <input-row
                                    desc="The description of the Simulation Type of this Project"
                                    v-model="simulation.simulation_type.description"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Description
                                </input-row>

                                <input-row
                                    desc="The value of the Simulation Type of this Project"
                                    v-model="simulation.simulation_type.value"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Value
                                </input-row>

                                <input-row
                                    desc="The unit of the Simulation Type of this Project"
                                    v-model="simulation.simulation_type.unit.name"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Unit
                                </input-row>

                                <input-row
                                    desc="The symbol of the Simulation Type of this Project"
                                    v-model="simulation.simulation_type.unit.symbol"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                    Symbol
                                </input-row>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="w-full my-5 px-16 flex justify-end gap-x-10">
            <secondary-link-button :path="'simulations.create'">
                Create simulation
            </secondary-link-button>
            <primary-link-button
                :path="'projects.edit'"
                :parameter="project.id"
            >
                Edit Project
            </primary-link-button>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from "@/Layouts/AppLayout";
    import LeafletMap from "@/Components/LeafletMap";
    import InputRow from "@/Components/InputRow";
    import PrimaryLinkButton from "@/Components/PrimaryLinkButton";
    import SecondaryLinkButton from "@/Components/SecondaryLinkButton";

    export default {
        components: {
            AppLayout,
            LeafletMap,
            InputRow,
            PrimaryLinkButton,
            SecondaryLinkButton
        },

        props: {
            project: {
                type: Object,
                required: true,
            },
            simulations: {
                type: Array,
                required: true,
            },
        },
    };
</script>

<style scoped>
</style>


