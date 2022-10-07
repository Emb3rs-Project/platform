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
                                    <td v-if="item.status === 'RUNNING'"
                                        class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500 w-48">
                                        <lv-progressbar :value="item.progress" color="#38b2ac"/>
                                    </td>
                                    <td v-else
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
                                        class="pr-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end">

                                        <spinner-icon v-if="item.status === 'RUNNING'"
                                                      class="text-green-600 font-medium text-sm w-5"/>

                                        <button v-else @click="runSimulation(route('projects.simulations.run', {
                                            project: item.project.id,
                                            simulation: item.id,
                                            onRow: true,
                                            page: mySimulations.current_page
                                            }))">
                                                <play-icon class="text-green-600 font-medium text-sm w-5"></play-icon>
                                        </button>

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

                                        <Link
                                            :href="
                                                            route(
                                                                'projects.simulations.edit',
                                                                [
                                                                    item.project.id,
                                                                    item.id,
                                                                ]
                                                            )
                                                        "
                                        >
                                            <edit-icon
                                                class="text-gray-500 font-medium text-sm w-5"
                                            ></edit-icon>
                                        </Link>

                                        <button
                                            class="focus:outline-none"
                                            @click=" showModal(item, DeleteModal)"
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
import {Link} from "@inertiajs/inertia-vue3";
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
import PrimaryButton from "../../Components/PrimaryButton";
import DialogModal from "../../Jetstream/DialogModal";
import SelectMenu from "../../Components/Forms/SelectMenu";
import Field from "../../Components/Field";
import SecondaryOutlinedButton from "../../Components/SecondaryOutlinedButton";
import {Inertia} from "@inertiajs/inertia";
import SpinnerIcon from "../../Components/Icons/SpinnerIcon";
import {broadcast} from "../../Mixins/vueEcho";
import DeleteModal from '../../Components/Modals/DeleteModal';


export default {
    components: {
        SpinnerIcon,
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
        const tableColumns = ["id", "name", "project", "metadata", "created_at", "status", "actions"];
        const executeAction = ref(false)
        const currentProject = ref({})
        let mySimulations = toRefs(props).mySimulations
        const simulations = mySimulations.value.data
        window.timeouts = []

        const modalIsOpen = ref(false);
        const currentModal = ref(null);
        const itemToDelete = ref(null);
        const modalComponent = computed(() =>
            currentModal.value ? currentModal.value : false
        );

        const runSimulation = (route) => {
            axios.post(route)
        }

        const reloadGrid = () => {
            Inertia.reload({only: ['mySimulations']})
            window.timeoutReload = setTimeout(reloadGrid, 2000)
        }

        const reloadSimulation = (id, repeat = false) => {
            axios.post('/get-simulation', {
                id: id
            }).then(({data}) => {
                let index = simulations.findIndex(({id}) => id === data.id)
                if (index >= 0) {
                    simulations[index] = data
                }
            })

            if (repeat) {
                window.timeouts[`timeoutSimulationReload-${id}`] = setTimeout(reloadSimulation, 2000, id, true)
            }
        }

        const sendToCreateSimulation = () => {
            let project = currentProject.value
            Inertia.visit(route(
                'projects.simulations.create',
                project.key
            ))
        }

        const clearTimeouts = () => {
            Object.keys(window.timeouts).forEach(timeout => {
                clearTimeout(window.timeouts[timeout])
            })
            window.timeouts = []
        }

        onMounted(() => {
            broadcast().channel('my-simulations')
                .listen('.simulation-updated', (e) => {
                    reloadSimulation()
                })
                .listen('.simulation-run', (e) => {
                    notify({
                        group: "notifications",
                        title: "Simulation",
                        text: e.data.description,
                        data: {
                            type: "success",
                        },
                    });
                    reloadSimulation(e.data.id, true)
                })
                .listen('.simulation-finished', (e) => {
                    clearTimeout(window.timeouts[`timeoutSimulationReload-${e.data.id}`])
                    reloadSimulation(e.data.id)
                })
            simulations.forEach((simulation) => {
                if (simulation.status === 'RUNNING') {
                    reloadSimulation(simulation.id, true)
                }
            })
        })

        onBeforeUnmount(() => {
            broadcast().leave('my-simulations')
            clearTimeouts()

        })

        const showModal = (item, modal) => {
            currentModal.value = modal;
            itemToDelete.value = item;

            modalIsOpen.value = true;
        };

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

        return {
            tableColumns,
            executeAction,
            currentProject,
            modalComponent,
            modalIsOpen,
            DeleteModal,
            onConfirmation,
            showModal,
            sendToCreateSimulation,
            moment,
            runSimulation
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
