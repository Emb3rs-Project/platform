<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Objects | Sources
            </h2>
        </template>
        <div class="p-5">
            <h1 class="text-lg font-bold">Sources</h1>
        </div>
        <div class="flex p-5 h-screen md:h-content gap-2">
            <div class="w-6/12 md:overflow-y-auto">
                <div class="overflow-x bg-red-500">
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
                                v-for="(i, index) in sources"
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
                                    <a
                                        :class="{
                      'font-bold text-green-700 cursor-pointer hover:text-green-500':
                        i.location,
                    }"
                                        @click="centerAtLocation(i.location)"
                                    >
                                        {{ i.location ? i.location.name : "Not Assigned" }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                                    <a :href="route('objects.sources.show', i.id)">
                                        <detail-icon class="text-gray-600 font-medium text-sm w-5"></detail-icon>
                                    </a>
                                    <a :href="route('objects.sources.edit', i.id)">
                                        <edit-icon class="text-gray-600 font-medium text-sm w-5"></edit-icon>
                                    </a>
                                    <a
                                        href="#"
                                        @click="onDelete(i)"
                                    >
                                        <trash-icon class="text-gray-600 font-medium text-sm w-5"></trash-icon>
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
                <leaflet-map
                    :markers="markers"
                    ref="map"
                ></leaflet-map>
            </div>
        </div>
        <div class="w-full my-5 px-16 flex justify-end">
            <jet-link-button
                path="objects.sources.create"
                class="my-10"
            >
                Create New Source
            </jet-link-button>
        </div>
    </app-layout>
</template>

<script>
    import { ref } from "vue";

    import useUniqueLocations from "@/Composables/useUniqueLocations";

    import AppLayout from "@/Layouts/AppLayout";
    import LeafletMap from "@/Components/LeafletMap";
    import JetLinkButton from "@/Jetstream/LinkButton";
    import TrashIcon from "@/Icons/TrashIcon.vue";
    import EditIcon from "@/Icons/EditIcon.vue";
    import DetailIcon from "@/Icons/DetailIcon.vue";

    export default {
        components: {
            AppLayout,
            LeafletMap,
            JetLinkButton,
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
