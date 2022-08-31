<template>
    <SiteHead title="Projects" />

    <AppLayout>
        <div>
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <h1 class="mb-8 font-bold text-3xl">Projects</h1>

                <div class="flex justify-end mb-5">
                    <PrimaryLinkButton class="w-48" path="projects.create">
                        Create a Project
                    </PrimaryLinkButton>
                </div>

                <div class="flex flex-col gap-8">
                    <div class="shadow">
                        <div v-if="projects.length">
                            <AmazingIndexTable
                                v-model="projects"
                                :columns="tableColumns"
                                :hasCheckbox="false"
                                headerClasses="shadow overflow-hidden sm:rounded-lg"
                            >
                                <!-- ID -->
                                <template #header-id> ID </template>
                                <template #body-id="{ item }">
                                    <td class="text-left pl-4">
                                        {{ item.id }}
                                    </td>
                                </template>

                                <!-- Name -->
                                <template #header-name> Name </template>
                                <template #body-name="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        {{ item.name }}
                                    </td>
                                </template>

                                <!-- Description -->
                                <template #header-description>
                                    Description
                                </template>
                                <template #body-description="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                    >
                                        {{ item.description }}
                                    </td>
                                </template>

                                <!-- Actions -->
                                <template #header-actions> </template>
                                <template #body-actions="{ item }">
                                    <td
                                        class="pr-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'projects.simulations.create',
                                                    item.id
                                                )
                                            "
                                            as="button"
                                            type="button"
                                            class="focus:outline-none text-indigo-600 hover:text-indigo-900 divide-x"
                                        >
                                            Create a Simulation
                                        </Link>

                                        <div
                                            className="border-r border-gray-500"
                                        />

                                        <Link
                                            :href="
                                                route('projects.show', item.id)
                                            "
                                            as="button"
                                            type="button"
                                            class="focus:outline-none"
                                        >
                                            <DetailIcon
                                                class="text-gray-500 font-medium text-sm w-5"
                                            />
                                        </Link>

                                        <Link
                                            :href="
                                                route('projects.edit', item.id)
                                            "
                                            as="button"
                                            type="button"
                                            class="focus:outline-none"
                                        >
                                            <EditIcon
                                                class="text-gray-500 font-medium text-sm w-5"
                                            />
                                        </Link>

                                        <Link
                                            :href="
                                                route(
                                                    'projects.destroy',
                                                    item.id
                                                )
                                            "
                                            method="delete"
                                            as="button"
                                            type="button"
                                            class="focus:outline-none"
                                        >
                                            <TrashIcon
                                                class="text-red-500 font-medium text-sm w-5"
                                            />
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

                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout";
import AmazingIndexTable from "@/Components/Tables/AmazingIndexTable.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";

export default {
    components: {
        SiteHead,
        AppLayout,
        AmazingIndexTable,
        TrashIcon,
        EditIcon,
        DetailIcon,
        PrimaryLinkButton,
        Link,
    },
    props: {
        projects: {
            type: Array,
            required: true,
        },
    },
    setup(props) {
        const tableColumns = ["id", "name", "description", "actions"];

        return {
            tableColumns,
        };
    },
};
</script>

<style></style>
