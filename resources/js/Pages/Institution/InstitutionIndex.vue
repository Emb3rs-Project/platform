<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Institution
            </h2>
        </template>
        <div class="flex flex-col p-5 h-full gap-5">
            <!-- List Users -->
            <div>
                <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">
                        Users
                    </h3>
                    <p class="text-sm text-gray-500">
                        A list of all the Users that belong to this Team
                    </p>
                </div>
                <div class="w-full max-h-96 bg-white shadow overflow-y-auto sm:rounded-b-md">
                    <ul class="divide-y divide-gray-200">
                        <li
                            v-for="(user, index) in users"
                            :key="index"
                        >
                            <div class="flex items-center px-4 py-4 sm:px-6">
                                <div class="min-w-0 flex-1 flex items-center">
                                    <div class="flex-shrink-0">
                                        <img
                                            class="h-12 w-12 rounded-full"
                                            :src="user.profile_photo_url"
                                            alt=""
                                        >
                                    </div>
                                    <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-indigo-600 truncate">
                                                {{ user.name }}
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500">
                                                <svg
                                                    class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                    aria-hidden="true"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M6.625 2.655A9 9 0 0119 11a1 1 0 11-2 0 7 7 0 00-9.625-6.492 1 1 0 11-.75-1.853zM4.662 4.959A1 1 0 014.75 6.37 6.97 6.97 0 003 11a1 1 0 11-2 0 8.97 8.97 0 012.25-5.953 1 1 0 011.412-.088z"
                                                        clip-rule="evenodd"
                                                    />
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5 11a5 5 0 1110 0 1 1 0 11-2 0 3 3 0 10-6 0c0 1.677-.345 3.276-.968 4.729a1 1 0 11-1.838-.789A9.964 9.964 0 005 11zm8.921 2.012a1 1 0 01.831 1.145 19.86 19.86 0 01-.545 2.436 1 1 0 11-1.92-.558c.207-.713.371-1.445.49-2.192a1 1 0 011.144-.83z"
                                                        clip-rule="evenodd"
                                                    />
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 10a1 1 0 011 1c0 2.236-.46 4.368-1.29 6.304a1 1 0 01-1.838-.789A13.952 13.952 0 009 11a1 1 0 011-1z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                <span class="truncate">
                                                    {{ user.id }}
                                                </span>
                                            </p>
                                            <p class="mt-2 flex items-center text-sm text-gray-500">

                                                <svg
                                                    class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                    aria-hidden="true"
                                                >
                                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                                </svg>
                                                <span class="truncate">
                                                    {{ user.email }}
                                                </span>
                                            </p>
                                            <p class="mt-2 flex items-centertext-sm text-gray-500">
                                                <svg
                                                    class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                    aria-hidden="true"
                                                >
                                                    <path
                                                        fillRule="evenodd"
                                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                        clipRule="evenodd"
                                                    />
                                                </svg>
                                                {{ user.created_at }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /List Users -->

            <!-- List Sources -->
            <div class="flex gap-5">
                <!-- <div class="w-full md:w-8/12">
                    <leaflet-map :markers="sourcesMapData"></leaflet-map>
                </div> -->

                <div class="w-full  max-h-96 overflow-y-auto">
                    <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">
                            Sources
                        </h3>
                        <p class="text-sm text-gray-500">
                            A list of all the Sources that belong to this Team
                        </p>
                    </div>
                    <div class="md:overflow-y-auto">
                        <div class="overflow-x">
                            <table class="min-w-full divide-y divide-gray-200 shadow">
                                <thead class="bg-gray-50">
                                    <tr>
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
                                            Template
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Category
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Location
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(i, index) in sources"
                                        :key="index"
                                        :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                                    >
                                        <!-- bg-white is on odd rows -->
                                        <!-- bg-gray is on even rows -->

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ i.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ i.template.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ i.template.category.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a :class="{
                                            'font-bold text-green-700 cursor-pointer hover:text-green-500':
                                                i.location,
                                             }">
                                                {{ i.location ? i.location.name : "Not Assigned" }}
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /List Sources -->

            <!-- List Sinks -->
            <div class="flex gap-5">
                <!-- <div class="w-full md:w-8/12 max-h-96">
                    <leaflet-map :markers="sinksMapData"></leaflet-map>
                </div> -->

                <div class="w-full max-h-96 overflow-y-auto">
                    <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">
                            Sinks
                        </h3>
                        <p class="text-sm text-gray-500">
                            A list of all the Sinks that belong to this Team
                        </p>
                    </div>
                    <div class="md:overflow-y-auto">
                        <div class="overflow-x">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
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
                                            Template
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Category
                                        </th>
                                        <th
                                            scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                        >
                                            Location
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(i, index) in sinks"
                                        :key="index"
                                        :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                                        class="hover:bg-gray-300 hover:text-white pt-3 pb-3 rounded"
                                    >
                                        <!-- bg-white is on odd rows -->
                                        <!-- bg-gray is on even rows -->

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ i.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ i.template.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ i.template.category.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a :class="{
                                                    'font-bold text-green-700 cursor-pointer hover:text-green-500':
                                                        i.location,
                                                }">
                                                {{ i.location ? i.location.name : "Not Assigned" }}
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /List Sinks -->
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
            'users',
            'sources',
            'sinks',
        ],

        setup(props) {
            const sourcesMapData = ref([]);
            const sinksMapData = ref([]);

            for (const source of props.sources) {
                if (source.data) sourcesMapData.value.push(source.data);
            }

            for (const sink of props.sinks) {
                if (sink.data) sinksMapData.value.push(sink.data);
            }
            console.log("SINKS:", sinksMapData.value);
            return {
                sourcesMapData,
                sinksMapData
            };
        }
    };
</script>

<style scoped>
</style>
