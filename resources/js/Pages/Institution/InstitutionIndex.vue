<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Institution
            </h2>
        </template>

        <div>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">
                        Sources
                    </h3>
                    <p class="text-sm text-gray-500">
                        A list of all the Sources that belong to this Team
                    </p>
                </div>
                <div class="w-full border-t border-gray-300"></div>
                <ul class="divide-y divide-gray-200">
                    <li
                        v-for="(source, index) in sources"
                        :key="index"
                    >
                        <a
                            href="
                        #"
                            class="block hover:bg-gray-50"
                        >
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        Back End Developer
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Full-time
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <!-- Heroicon name: solid/users -->
                                            <svg
                                                class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                            </svg>
                                            Engineering
                                        </p>
                                        <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                            <!-- Heroicon name: solid/location-marker -->
                                            <svg
                                                class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            Remote
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <!-- Heroicon name: solid/calendar -->
                                        <svg
                                            class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                        <p>
                                            Closing on
                                            <time datetime="2020-01-07">January 7, 2020</time>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex p-5 h-full gap-3">
            <div class="w-full md:w-8/12">
                <leaflet-map :markers="markers"></leaflet-map>
            </div>
            <div class="flex flex-col w-4/12 gap-5">
                <div class="h-96 bg-white overflow-y-auto shadow rounded-lg divide-y divide-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">
                            Sources
                        </h3>
                        <p class="text-sm text-gray-500">
                            A list of all the Sources that belong to this Team
                        </p>
                    </div>

                    <ul class="divide-y divide-gray-200">
                        <li
                            v-for="(source, index) in sources"
                            :key="index"
                        >
                            <a
                                href="#"
                                class="block hover:bg-gray-50"
                            >
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            {{source.name}}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{}}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500">
                                                <!-- Heroicon name: solid/users -->
                                                <svg
                                                    class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                    aria-hidden="true"
                                                >
                                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                                </svg>
                                                Team Name Goes Here
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0 sm:ml-6">
                                                <!-- Heroicon name: solid/location-marker -->
                                                <svg
                                                    class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                    aria-hidden="true"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                {{}}
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </a>

                        </li>
                    </ul>

                </div>
                <div class="h-96 bg-white overflow-y-auto shadow rounded-lg divide-y divide-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">
                            Sinks
                        </h3>
                        <p class="text-sm text-gray-500">
                            A list of all the Sink that belong to this Team
                        </p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <!-- Content goes here -->
                    </div>
                </div>
            </div>
        </div>

    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import { ref } from 'vue';

export default {
    components: {
        AppLayout,
        LeafletMap,
    },

    props: [
        'sources',
        'sinks',
    ],

    setup(props, context) {
        const markers = ref([]);
        console.log(props.sources);
        for (const source of props.sources) {
            markers.value.push(source.data);
        }

        return {
            markers,
        };
    },

    data() {
        return {
            users: [
                {
                    id: "1",
                    name: "George Mantellos",
                    username: "geocfu",
                },
                {
                    id: "2",
                    name: "David Fernandes",
                    username: "davidfs",
                },
                {
                    id: "3",
                    name: "Luis Miguel Campos",
                    username: "luismc",
                },
                {
                    id: "4",
                    name: "Carlos Nuno Marques",
                    username: "carlosnm",
                },
            ],
            teams: [
                {
                    name: "George's team",
                },
            ],
        };
    },
};
</script>

<style scoped>
</style>
