<template>
    <SiteHead title="Sinks"/>

    <AppLayout>

        <div>

            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <h1 class="mb-8 font-bold text-3xl">Sinks</h1>

                <div class="flex justify-end">


                    <div class="mb-5">

                        <PrimaryButton v-tippy="'Import Sinks'" class="w-48 mr-2" @click="importInstance('Sink')">
                            Import Sinks
                        </PrimaryButton>
                        <PrimaryButton v-tippy="'Export Sinks'" class="w-48" @click="executeAction = true">
                            Export Sinks
                        </PrimaryButton>
                    </div>
                </div>

                <div class="flex flex-col gap-8">
                    <div class="shadow">
                        <div v-if="sinks.data.length">
                            <AmazingIndexTable
                                v-model="sinks.data"
                                :columns="tableColumns"
                                :hasCheckbox="true"
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

                                </template>
                            </AmazingIndexTable>
                        </div>
                        <div
                            v-else
                            class="flex items-center place-content-center bg-gray-50 h-20 md:h-64"
                        >
                            <h1 class="text-2xl font-extrabold text-gray-300 uppercase">
                                Empty
                            </h1>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <pagination class="mt-6" :links="sinks.links"/>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
    <DialogModal :show="executeAction" @close="closeModal">
        <template #title>
           Export Sinks
        </template>

        <template #content class="my-auto">
            sinks selected: {{ pluck(sinks.data.filter((itm) => itm.selected),'id').length > 0 ? pluck(sinks.data.filter((itm) => itm.selected),'id') : 'ALL' }}
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
    <DialogModal :show="executeActionImport" @close="closeModalImport">
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
                @click="closeModalImport">
                Cancel
            </SecondaryOutlinedButton>

            <PrimaryButton @click="submitImport">
                Submit
            </PrimaryButton>
        </template>

    </DialogModal>
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
import {computed, onBeforeUnmount, onMounted, ref, toRefs} from "vue";
import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout";
import AmazingIndexTable from "@/Components/Tables/AmazingIndexTable.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";
import PlayIcon from "@/Components/Icons/PlayIcon.vue";
import Pagination from "@/Components/Pagination";
import {notify} from "@kyvg/vue3-notification";
import moment from 'moment'
import PrimaryButton from "../../../Components/PrimaryButton";
import DialogModal from "../../../Jetstream/DialogModal";
import SelectMenu from "../../../Components/Forms/SelectMenu";
import Field from "../../../Components/Field";
import SecondaryOutlinedButton from "../../../Components/SecondaryOutlinedButton";
import {Inertia} from "@inertiajs/inertia";
import DeleteModal from '../../../Components/Modals/DeleteModal';
import axios from "axios";


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
        Pagination,
        DeleteModal
    },
    props: {
        sinks: {
            type: [Array, Object],
            required: true,
        },

    },
    setup (props) {
        const tableColumns = ["id", "name", "created_at", "actions"];
        const executeAction = ref(false)
        const currentProject = ref({})
        let sinks = toRefs(props).sinks
        const SinkData = sinks.value.data
        window.timeouts = []

        const modalIsOpen = ref(false);
        const currentModal = ref(null);
        const modalComponent = computed(() =>
            currentModal.value ? currentModal.value : false
        );




        const showModal = (item, modal) => {
            currentModal.value = modal;
            itemToDelete.value = item;

            modalIsOpen.value = true;
        };

        function closeModal() {
            executeAction.value = false
        }

        function submit() {
            axios.post('/sinks/export', {
                ids: pluck(sinks.value.data.filter((itm) => itm.selected),'id')
            },{responseType: 'blob'})
                .then(({data}) => {
                    const url = window.URL.createObjectURL(new Blob([data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'sinks.xlsx'); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                    closeModal()
                })

        }

        const onConfirmation = () => {
            Inertia.delete(
                route("projects.simulations.destroy", [itemToDelete.value.project.id, itemToDelete.value.id]),
                {
                    onSuccess: (data) => {
                        notify({
                            group: "notifications",
                            title: "Simulation",
                            text: 'Simulation Deleted Successfully',
                            data: {
                                type: "success",
                            },
                        });
                    },
                    onError: (error) => {
                        notify({
                            group: "notifications",
                            title: "Simulation",
                            text: 'General error, please try again.',
                            data: {
                                type: "danger",
                            },
                        });
                    },
                }
            );
        }

        function pluck(array, key) {
            return array.map(function(obj) {
                return obj[key];
            });
        }

        const executeActionImport = ref(false)
        function closeModalImport() {
            executeActionImport.value = false
        }
        const file = ref(null);

        function importInstance(action) {
            form.action = action
            executeActionImport.value = true
        }

        function submitImport() {
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
            closeModalImport()
            notify({
                group: "notifications",
                title: "Import",
                text: 'The file is being processed in the background. You will receive a notification when finished.',
                data: {
                    type: "success",
                },
            });
        }
        const form = useForm({
            file: null,
            action: null,
        })

        return {
            tableColumns,
            executeAction,
            currentProject,
            modalComponent,
            modalIsOpen,
            DeleteModal,
            onConfirmation,
            showModal,
            moment,
            SinkData,
            closeModal,
            submit,
            pluck,
            executeActionImport,
            closeModalImport,
            submitImport,
            form,
            importInstance,
            file
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
