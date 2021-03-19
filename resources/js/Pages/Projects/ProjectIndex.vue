<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects
            </h2>
        </template>

        <div class="flex flex-col p-5 h-full gap-5">
            <!-- List Projects w/Map -->
            <div class="flex gap-5">
                <div class="flex flex-col gap-5 sm:w-full md:w-6/12">
                    <!-- List Projects -->
                    <div>
                        <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">
                                Projects
                            </h3>
                            <p class="text-sm text-gray-500">
                                A list of all the Projects
                            </p>
                        </div>
                        <div class="min-w-full max-h-content overflow-y-auto overflow-x-auto shadow sm:rounded-b-md">
                            <div v-if="projects.length">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                ID
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Name
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Description
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            >
                                                Location
                                            </th>
                                            <th
                                                scope="col"
                                                class="relative px-6 py-3"
                                            >
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(project, index) in projects"
                                            :key="index"
                                            :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                                        >
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ project.id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ project.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ project.description }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a
                                                    :class="{
                                                    'font-bold text-green-700 cursor-pointer hover:text-green-500':
                                                        project.location,
                                                }"
                                                    @click="centerAtLocation(project.location)"
                                                >
                                                    {{ project.location ? project.location.name : "Not Assigned" }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                                                <inertia-link :href="route('projects.show', project.id)">
                                                    <detail-icon class="text-gray-600 font-medium text-sm w-5"></detail-icon>
                                                </inertia-link>
                                                <inertia-link :href="route('projects.edit', project.id)">
                                                    <edit-icon class="text-gray-600 font-medium text-sm w-5"></edit-icon>
                                                </inertia-link>
                                                <button
                                                    class="focus:outline-none"
                                                    @click="onDelete(project)"
                                                >
                                                    <trash-icon class="text-red-600 font-medium text-sm w-5"></trash-icon>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div
                                v-else
                                class="flex items-center place-content-center bg-gray-50 h-64"
                            >
                                <h1 class="text-2xl font-extrabold text-gray-300 uppercase">No Sources Found</h1>
                            </div>

                        </div>
                    </div>
                    <!-- /List Projects -->
                </div>

                <div class="sm:w-full md:w-6/12">
                    <leaflet-map
                        :markers="markers"
                        ref="map"
                    ></leaflet-map>
                </div>
            </div>
            <!-- /List Projects w/Map -->
            <div class="w-full px-10 flex justify-end">
                <primary-link-button :path="'projects.create'">
                    Create New Project
                </primary-link-button>

            </div>
        </div>

    </app-layout>
</template>

<script>
    import { ref } from "vue";

    import useUniqueLocations from "@/Composables/useUniqueLocations";

    import AppLayout from "@/Layouts/AppLayout";
    import LeafletMap from "@/Components/LeafletMap";
    import PrimaryLinkButton from "@/Components/PrimaryLinkButton";
    import TrashIcon from "@/Icons/TrashIcon.vue";
    import EditIcon from "@/Icons/EditIcon.vue";
    import DetailIcon from "@/Icons/DetailIcon.vue";

    export default {
        components: {
            AppLayout,
            LeafletMap,
            PrimaryLinkButton,
            TrashIcon,
            EditIcon,
            DetailIcon
        },

        props: {
            projects: {
                type: Array,
                required: true
            }
        },

        setup(props) {
            const map = ref(null);
            const markers = ref([]);

            const uniqueProjects = useUniqueLocations(props.projects);

            for (const project of uniqueProjects.value) {
                markers.value.push(project.data);
            }

            function onDelete(project) {
                // we need to diplay a modal here
                this.$inertia.delete(route("projects.destroy", project.id));
            };

            function centerAtLocation(location) {
                const marker = this.markers.find((m) => m.id === location.geo_object.id);
                map.value.centerAtLocation(marker);
            }

            return {
                map,
                markers,
                onDelete,
                centerAtLocation
            };
        },
    };
</script>

<style>
</style>
