<template>
    <SiteHead title="Projects"/>

    <AppLayout>
        <div>
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <h1 class="mb-8 font-bold text-3xl">Projects</h1>

                <div class="flex justify-end mb-5">
                    <PrimaryButton v-tippy="'Import sources'" class="w-48 mr-2" @click="importInstance('Source')">
                        Import Sources
                    </PrimaryButton>
                    <PrimaryButton v-tippy="'Import Sinks'" class="w-48 mr-2" @click="importInstance('Sink')">
                        Import Sinks
                    </PrimaryButton>
                    <PrimaryLinkButton v-tippy="'Create a Project'" class="w-48" path="projects.create">
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
                                            v-tippy="'Show Project'"
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
                                            v-tippy="'Edit Project'"
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

                                        <button
                                            v-tippy="'Delete Project'"
                                            class="focus:outline-none"
                                            @click="showModal(item, DeleteModal)"
                                        >
                                            <TrashIcon class="text-red-500 font-medium text-sm w-5" />
                                        </button>
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

        <DialogModal :show="executeAction" @close="closeModal">
            <template #title>
                Import {{form.action}}
            </template>

            <template #content class="my-auto">
                <Field label="File"
                       hint="Excel file for import">
                    <input
                        type="file"
                        ref="file"
                        class="
                                        w-full
                                        px-4
                                        py-2
                                        mt-2
                                        border
                                        rounded-md
                                        focus:outline-none
                                        focus:ring-1
                                        focus:ring-blue-600
                                    "
                    />
                </Field>
                <p> Download an example file: <a :href="`/sample-import?type=${form.action}`" class="font-bold text-primary">click here</a></p>
            </template>

            <template #footer>

                <SecondaryOutlinedButton
                    class="mr-2"
                    @click="closeModal">
                    Cancel
                </SecondaryOutlinedButton>

                <PrimaryButton @click="submit">
                    Submit
                </PrimaryButton>
            </template>

        </DialogModal>
    </AppLayout>
    <component
        class="z-50"
        :is="modalComponent"
        v-if="modalComponent"
        v-model="modalIsOpen"
        @confirmation="onConfirmation"
    >
    </component>
</template>

<script>
import {Link, useForm} from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

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
import { computed, ref } from "vue";

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
    props: {
        projects: {
            type: Array,
            required: true,
        },
    },
    setup (props) {
        const tableColumns = ["id", "name", "description", "actions"];
        const file = ref(null);
        const modalIsOpen = ref(false);
        const currentModal = ref(null);
        const itemToDelete = ref(null);

        const modalComponent = computed(() =>
            currentModal.value ? currentModal.value : false
        );
        const form = useForm({
            file: null,
            action: null,
        })
        function closeModal() {
            executeAction.value = false
        }
        const showModal = (item, modal) => {
            currentModal.value = modal;
            itemToDelete.value = item;

            modalIsOpen.value = true;
        };

        const onConfirmation = (modalType) => {
            switch (modalType) {
                case "delete":
                Inertia.delete(
                    route(`projects.destroy`, itemToDelete.value.id),
                    {
                        onSuccess: (data) => {
                            notify({
                                group: "notifications",
                                title: "Project",
                                text: 'Project Deleted Successfully',
                                data: {
                                    type: "success",
                                },
                            });
                        },
                        onError: (error) => {
                            notify({
                                group: "notifications",
                                title: "Project",
                                text: 'General error, please try again.',
                                data: {
                                    type: "danger",
                                },
                            });
                        },
                    }
                );
                break;

                default:
                break;
            }
        };

        function submit() {
            if (file) {
                form.file = file.value.files[0];
            }
            form.post('/import', {
                wantsJson: true,
                preserveState: true,
                preserveScroll: true,
                onSuccess: (data) => {
                    form.reset()
                    console.log(data);
                }
            });
            closeModal()
            notify({
                group: "notifications",
                title: "Import",
                text: 'The file is being processed in the background. You will receive a notification when finished.',
                data: {
                    type: "success",
                },
            });
        }

        const executeAction = ref(false);
        function importInstance(action) {
            form.action = action
            executeAction.value = true
        }

        return {
            tableColumns,
            file,
            form,
            modalIsOpen,
            modalComponent,
            showModal,
            closeModal,
            onConfirmation,
            submit,
            executeAction,
            importInstance,
            DeleteModal
        };
    },
};
</script>

<style></style>
