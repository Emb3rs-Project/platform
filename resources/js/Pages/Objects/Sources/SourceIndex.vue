<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Objects | Sources
            </h2>
        </template>

        <div class="flex flex-col md:flex-row h-screen md:h-table-and-map p-5 gap-5">
            <div class="w-full h-96 md:h-full md:w-1/2">
                <div class="flex flex-col max-h-full">
                    <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">
                            Sources
                        </h3>
                        <p class="text-sm text-gray-500">
                            A list of all the Sources
                        </p>
                    </div>
                    <div class="overflow-y-auto overflow-x-auto shadow sm:rounded-b-md ">
                        <div v-if="sources.length">
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
                                        v-for="(source, index) in sources"
                                        :key="index"
                                        :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                                    >
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ source.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ source.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ source.template.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ source.template.category.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a
                                                :class="{
                                                    'font-bold text-green-700 cursor-pointer hover:text-green-500':
                                                        source.location,
                                                }"
                                                @click="centerAtLocation(source.location)"
                                            >
                                                {{ source.location ? source.location.name : "Not Assigned" }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                                            <inertia-link :href="route('objects.sources.show', source.id)">
                                                <detail-icon class="text-gray-500 font-medium text-sm w-5"></detail-icon>
                                            </inertia-link>
                                            <inertia-link :href="route('objects.sources.edit', source.id)">
                                                <edit-icon class="text-gray-500 font-medium text-sm w-5"></edit-icon>
                                            </inertia-link>
                                            <button
                                                class="focus:outline-none"
                                                @click="onDelete(source)"
                                            >
                                                <trash-icon class="text-red-500 font-medium text-sm w-5"></trash-icon>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            v-else
                            class="flex items-center place-content-center bg-gray-50 h-20 md:h-64"
                        >
                            <h1 class="text-2xl font-extrabold text-gray-300 uppercase">No Sources Found</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full h-full md:w-1/2">
                <leaflet-map
                    :markers="markers"
                    ref="map"
                ></leaflet-map>
            </div>
        </div>

        <div class="w-full px-16 flex justify-end">
            <primary-link-button :path="'objects.sources.create'">
                Create New Source
            </primary-link-button>
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
            DetailIcon,
        },
        props: {
            sources: {
                type: Array,
                required: true,
            },
        },

        setup(props) {
            const map = ref(null);
            const markers = ref([]);
            console.log(props.sources);
            const uniqueSources = useUniqueLocations(props.sources)

            for (const source of uniqueSources.value) {
                markers.value.push(source.data);
            }

            function onDelete(source) {
                // we need to diplay a modal here
                this.$inertia.delete(route("objects.sources.destroy", source.id));
            };

            function centerAtLocation(location) {
                const marker = this.markers.find((m) => m.id === location.geo_object.id);
                map.value.centerAtLocation(marker);
            }

            return {
                markers,
                onDelete,
                centerAtLocation
            };
        }
    };
</script>

<style>
</style>
