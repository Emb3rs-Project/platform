<template>
    <SiteHead title="My Simulations"/>

    <AppLayout>

        <div>

            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <h1 class="mb-8 font-bold text-3xl">My Simulations</h1>

                <div class="flex justify-end mb-5">
                    <PrimaryButton class="w-48" @click="executeAction = true">
                        New Simulation
                    </PrimaryButton>
                </div>

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
                                    Simulation Type
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
                                <!-- Created at -->
                                <template #header-created_at>
                                    Created at
                                </template>
                                <template #body-created_at="{ item }">
                                    <td
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                    >
                                        {{ moment(item.created_at).format('DD/MM/YYYY HH:mm:ss') }}
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
                            <h1 class="text-2xl font-extrabold text-gray-300 uppercase">
                                No Simulations Found
                            </h1>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <pagination class="mt-6" :links="mySimulations.links"/>
                    </div>
                </div>
            </div>

            <DialogModal :show="executeAction" @close="executeAction = false">
                <template #title>
                    Create new simulation
                </template>

                <template #content class="my-auto">
                    <Field label="Project"
                        hint="Select a project first to create a new a simulation">
                        <SelectMenu v-model="currentProject" :options="projects"></SelectMenu>
                    </Field>
                </template>

                <template #footer>

                    <SecondaryOutlinedButton
                        class="mr-2"
                        @click="executeAction = false">
                        Cancel
                    </SecondaryOutlinedButton>

                    <PrimaryButton @click="sendToCreateSimulation">
                        Create Simulation
                    </PrimaryButton>
                </template>

            </DialogModal>
        </div>
    </AppLayout>
</template>

<script>
import {Link} from "@inertiajs/inertia-vue3";
import { ref} from "vue";
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
import moment from 'moment'
import PrimaryButton from "../../Components/PrimaryButton";
import DialogModal from "../../Jetstream/DialogModal";
import SelectMenu from "../../Components/Forms/SelectMenu";
import Field from "../../Components/Field";
import SecondaryOutlinedButton from "../../Components/SecondaryOutlinedButton";
import {Inertia} from "@inertiajs/inertia";

export default {
    components: {
        SecondaryOutlinedButton,
        Field,
        SelectMenu,
        DialogModal,
        PrimaryButton,
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
        projects: {
            type: [Array, Object],
            required: true,
        },

    },
    setup (props) {
        const tableColumns = ["id", "name", "project", "metadata","created_at", "status", "actions"];
        const executeAction = ref(false)
        const currentProject = ref({})

        const sendToCreateSimulation = () => {
           let project = currentProject.value
            Inertia.visit(route(
                'projects.simulations.create',
                project.key
            ))
        }

        return {
            tableColumns,
            executeAction,
            currentProject,
            sendToCreateSimulation,
            moment
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
