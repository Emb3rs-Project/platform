<template>
    <SiteHead title="Challenges"/>

    <AppLayout>
        <div>
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <h1 class="mb-8 font-bold text-3xl">Challenges Subscribed</h1>
                <div class="flex flex-col gap-8">
                    <div class="shadow">
                        <div v-if="challenges.length">
                            <AmazingIndexTable
                                v-model="challenges"
                                :columns="tableColumns"
                                :hasCheckbox="false"
                                headerClasses="shadow overflow-hidden sm:rounded-lg"
                            >

                                <!-- Name -->
                                <template #header-name> Name</template>
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
                                <template #header-status> Status</template>
                                <template #body-status="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                    >
                                        {{ item.status === 1 ? 'In Progress' : 'Completed'}}
                                    </td>
                                </template>

                                <!-- Actions -->
                                <template #header-actions></template>
                                <template #body-actions="{ item }">
                                    <td
                                        class="pr-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end"
                                    >
                                        <Link
                                            v-tippy="'View Challenge'"
                                            :href="
                                                route('challenges.show', item.id)
                                            "
                                            as="button"
                                            type="button"
                                            class="focus:outline-none"
                                        >
                                            <DetailIcon
                                                class="text-gray-500 font-medium text-sm w-5"
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
                                No Challenges Found
                            </h1>
                        </div>
                    </div>

                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="mb-8 font-bold text-3xl">Challenges Available</h1>
                <div class="flex flex-col gap-8">
                    <div class="shadow">
                        <div v-if="challengesAvailable.length">
                            <AmazingIndexTable
                                v-model="challengesAvailable"
                                :columns="tableColumns"
                                :hasCheckbox="false"
                                headerClasses="shadow overflow-hidden sm:rounded-lg"
                            >
                                <!-- Name -->
                                <template #header-name> Name</template>
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
                                <template #header-status> Status</template>
                                <template #body-status="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                    >
                                        {{ item.status === 1 ? 'In Progress' : 'Completed'}}
                                    </td>
                                </template>
                                <!-- Actions -->
                                <template #header-actions></template>
                                <template #body-actions="{ item }">
                                    <td
                                        class="pr-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end"
                                    >
                                        <Link
                                            v-tippy="'View Challenge'"
                                            :href="
                                                route('challenges.show', item.id)
                                            "
                                            as="button"
                                            type="button"
                                            class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 disabled:bg-blue-600 inline-flex justify-center items-center px-3 py-2 leading-4 border border-transparent text-sm font-medium uppercase tracking-widest rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            More Detail
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
                                No Challenges Found
                            </h1>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </AppLayout>
</template>

<script>
import {Link, useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";

import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout";
import AmazingIndexTable from "@/Components/Tables/AmazingIndexTable.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";
import PrimaryButton from "../../Components/PrimaryButton";
import DialogModal from "../../Jetstream/DialogModal";
import Field from "../../Components/Field";
import SecondaryOutlinedButton from "../../Components/SecondaryOutlinedButton";
import DeleteModal from "@/Components/Modals/DeleteModal.vue";
import {notify} from "@kyvg/vue3-notification";
import {computed, ref} from "vue";

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
        DialogModal,
        Field,
        SecondaryOutlinedButton,
        PrimaryButton,
        DeleteModal
    },

    props: ["challenges", "challengesAvailable"],

    setup (props, context) {
        const tableColumns = [ "name", "description","status", 'started_at', "actions"];
        console.log(props.challenges);

        return {
            tableColumns
        }
    },
};
</script>

<style>
</style>
