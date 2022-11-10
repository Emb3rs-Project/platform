<template>
    <SiteHead title="Challenges"/>

    <AppLayout>
        <div>
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <h1 class="mb-8 font-bold text-3xl">Challenges</h1>

                <div class="flex justify-end mb-5">
                    <PrimaryLinkButton v-if="1!==1" class="w-48" path="challenges.create">
                        Create a Challenge
                    </PrimaryLinkButton>
                </div>

                <div class="flex flex-col gap-8">
                    <div class="shadow">
                        <div v-if="challenges.length">
                            <AmazingIndexTable
                                v-model="challenges"
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
                                        <Link
                                            v-tippy="'Edit Challenge'"
                                            :href="
                                                route('challenges.edit', item.id)
                                            "
                                            as="button"
                                            type="button"
                                            class="focus:outline-none"
                                        >
                                            <EditIcon
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

    props: ["challenges"],

    setup (props, context) {
        const tableColumns = ["id", "name", "description", "actions"];
        console.log(props.challenges);

        return {
            tableColumns
        }
    },
};
</script>

<style>
</style>
