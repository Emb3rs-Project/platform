<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects
            </h2>
        </template>
        <div class="p-5">
            <h1 class="text-lg font-bold">Projects</h1>
        </div>
        <div class="flex p-5 h-screen md:h-content gap-2">
            <div class="w-6/12 md:overflow-y-auto">
                <!-- <div class="flex flex-col"> -->
                <div class="overflow-x bg-red-500">
                    <!-- <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"> -->
                    <!-- <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"> -->
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
                                :class="(index % 2) ? 'bg-gray-50' : 'bg-white'"
                            >
                                <!-- bg-white is on odd rows -->
                                <!-- bg-gray is on even rows -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{project.id}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{project.name}}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ project.description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ project.location.name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                                    <a href="#">
                                        <svg
                                            class="h-5 w-5 text-yellow-500"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </a>
                                    <a href="#">
                                        <svg
                                            class="h-5 w-5 text-yellow-500"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- </div> -->
                    <!-- </div> -->
                </div>
                <!-- </div> -->
            </div>
            <div class="w-6/12">
                <leaflet-map></leaflet-map>
            </div>
        </div>
        <div class="w-full my-5 px-16 flex justify-end">
            <jet-link-button path="projects.create">
                Create New Project
            </jet-link-button>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import JetLinkButton from "@/Jetstream/LinkButton";

export default {
    components: {
        AppLayout,
        LeafletMap,
        JetLinkButton,
    },

    props: [
        'projects'
    ],

    setup(props, context) {
        console.log(props.projects);
    }
};
</script>

<style>
</style>
