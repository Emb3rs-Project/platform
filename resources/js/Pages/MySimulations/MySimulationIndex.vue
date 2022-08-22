<template>
    <SiteHead title="My Simulations"/>

    <AppLayout>
        <div class="bg-white">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <div class="flex flex-col gap-8">
                    <div class="shadow">
                        <div v-if="mySimulations.data.length">
                            <AmazingIndexTable
                                v-model="mySimulations.data"
                                :columns="tableColumns"
                                :hasCheckbox="false"
                                headerClasses="shadow overflow-hidden sm:rounded-lg"
                            >
                                <!-- ID -->
                                <template #header-id> ID</template>
                                <template #body-id="{ item }">
                                    <td class="text-left pl-4">
                                        {{ item.id }}
                                    </td>
                                </template>

                                <!-- Name -->
                                <template #header-name> Name</template>
                                <template #body-name="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        {{ item.name }}
                                    </td>
                                </template>

                                <!-- Project -->
                                <template #header-project>
                                    Project
                                </template>
                                <template #body-project="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'projects.show',
                                                    item.project.id
                                                )
                                            "
                                            as="button"
                                            type="button"
                                            class="focus:outline-none text-indigo-600 hover:text-indigo-900 divide-x"
                                        >
                                            {{ item.project.name }}
                                        </Link>
                                    </td>
                                </template>

                                <!-- Metadata -->
                                <template #header-metadata>
                                    Metadata
                                </template>
                                <template #body-metadata="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                    >
                                        {{ item.simulation_metadata.name }}
                                    </td>
                                </template>

                                <!-- Status -->
                                <template #header-status>
                                    Status
                                </template>
                                <template #body-status="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                    >
                                        {{ item.status }}
                                    </td>
                                </template>

                                <!-- Actions -->
                                <template #header-actions></template>
                                <template #body-actions="{ item }">
                                    <td
                                        class="pr-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end"
                                    >
                                        <Link
                                            method="post"
                                            as="button"
                                            :href="
                                route('projects.simulations.run', {
                                    project: item.project.id,
                                    simulation: item.id,
                                    onRow: true,
                                    page: mySimulations.current_page
                                })
                            "
                                        >
                                            <play-icon class="text-gray-500 font-medium text-sm w-5"></play-icon>

                                        </Link>

                                        <Link
                                            :href="
                                                            route(
                                                                'projects.simulations.show',
                                                                [
                                                                    item.project.id,
                                                                    item.id,
                                                                ]
                                                            )
                                                        "
                                        >
                                            <detail-icon
                                                class="text-gray-500 font-medium text-sm w-5"
                                            ></detail-icon>
                                        </Link>

                                    </td>
                                </template>
                            </AmazingIndexTable>
                        </div>
                        <div
                            v-else
                            class="flex items-center place-content-center bg-gray-50 h-20 md:h-64"
                        >
                            <h1
                                class="text-2xl font-extrabold text-gray-300 uppercase"
                            >
                                No Objects Found
                            </h1>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <pagination class="mt-6" :links="mySimulations.links"/>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";

import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout";
import AmazingIndexTable from "@/Components/Tables/AmazingIndexTable.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";
import PlayIcon from "@/Components/Icons/PlayIcon.vue";
import Pagination from "@/Components/Pagination";
import { notify } from "@kyvg/vue3-notification";

export default {
    components: {
        SiteHead,
        AppLayout,
        AmazingIndexTable,
        TrashIcon,
        EditIcon,
        PlayIcon,
        DetailIcon,
        PrimaryLinkButton,
        Link,
        Pagination
    },
    props: {
        mySimulations: {
            type: [Array, Object],
            required: true,
        },
    },
    setup (props) {
        const tableColumns = ["id", "name", "project", "metadata", "status", "actions"];

        return {
            tableColumns,
        };
    },
    watch: {
        '$page.props.flash': {
            handler (flash) {
                if (flash.success) {
                    notify({
                        group: "notifications",
                        title: "Simulation",
                        text: flash.success,
                        data: {
                            type: "success",
                        },
                    });
                }
            }
        }
    }
};
</script>

<style></style>
