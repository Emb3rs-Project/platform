<template>
    <AppLayout>
        <div class="bg-white h-screen overflow-y-scroll">
            <div class="grid grid-cols-2 gap-4">
                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <div class="flex justify-between">
                        <button class="bg-gray-600 hover:bg-gray-900 font-bold py-1 px-2 my-2 rounded-md text-white"
                            @click="back"><ChevronLeftIcon class="w-6 h-6"></ChevronLeftIcon></button>
                        <button class="bg-red-600 hover:bg-red-900 font-bold py-1 px-2 my-2 rounded-md text-white"
                            @click="deleteSession">Delete Session</button>
                    </div>
                    <h2 class="text-lg font-bold">Simulation Session Details</h2>

                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-gray-50">
                        <p>session uuid : {{ session.simulation_uuid }}</p>
                        <p>created_at: {{ session.created_at }}</p>
                    </div>

                </div>

                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <h2 class="text-lg font-bold">Simulation Step Data</h2>
                    <div v-if="reports.length == 0">
                        <p>No Data yet...</p>
                    </div>
                    <div v-for="report of processedReports" :key="report.id">
                        <div v-if="report.errors"
                            class="border border-red-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-red-50">
                            <p>Module : {{ report.module }} </p>
                            <p>Function : {{ report.function }} </p>
                            <p>Error : <span class="font-bold">{{ report.errors.message }}</span></p>
                        </div>

                        <div v-if="!report.errors"
                            class="border border-green-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-green-50">
                            <p>Module : {{ report.module }} </p>
                            <p>Function : {{ report.function }} </p>
                            <div class="my-2">
                                <Disclosure v-slot="{ open }">
                                    <DisclosureButton
                                        class="flex w-full justify-between rounded-lg bg-gray-700 px-4 py-2 text-left text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                                        <span>Input Data</span>
                                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''"
                                            class="h-5 w-5 " />
                                    </DisclosureButton>
                                    <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                                        <Codemirror v-model="report.data" :extensions="extensions"
                                            :style="{ height: '400px' }" :autofocus="true" :indent-with-tab="true"
                                            :tabSize="2" disabled></Codemirror>
                                    </DisclosurePanel>
                                </Disclosure>
                            </div>
                            <div class="my-2">
                                <Disclosure v-slot="{ open }">
                                    <DisclosureButton
                                        class="flex w-full justify-between rounded-lg bg-gray-700 px-4 py-2 text-left text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                                        <span>Output Data</span>
                                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''"
                                            class="h-5 w-5 " />
                                    </DisclosureButton>
                                    <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                                        <Codemirror v-model="report.output" :extensions="extensions"
                                            :style="{ height: '400px' }" :autofocus="true" :indent-with-tab="true"
                                            :tabSize="2" disabled></Codemirror>
                                    </DisclosurePanel>
                                </Disclosure>
                            </div>
                            <div class="mt-5 mb-2">
                             <a
                             v-if="report.output_data.report"
                             target="_blank"
                             :href="route('session.report.show', {session : session.id, report: report.id})"
                             class="bg-cyan-700 hover:bg-cyan-900 py-2 text-left font-medium text-sm px-4 rounded-lg text-white block w-full" >Report <ChevronRightIcon class="w-5 h-5 float-right"></ChevronRightIcon></a>
                            </div>
                        </div>
                    </div>
                    <pre>
                    <!-- {{ reports }} -->
                    </pre>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout";
import { useForm } from "@inertiajs/inertia-vue3";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import CheckboxRow from "@/Components/CheckboxRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";
import { computed, onMounted, onUnmounted, ref } from "vue";
import SiteHead from "@/Components/SiteHead.vue";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { polygon, marker } from "leaflet";
import VSelect from "vue-select";
import { COUNTRIES } from "./data/countries";
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/solid'
import { Codemirror } from 'vue-codemirror'
import { json } from "@codemirror/lang-json";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    session: Object,
    reports: Array
});

const stepInfo = computed(() => {
    return []
});

const processedReports = computed(() => {
    return props.reports.map((a) => ({ ...a, data: JSON.stringify(a.data, null, 2), output: JSON.stringify(JSON.parse(a.output), null, 2), output_data : JSON.parse(a.output) }))
})

const extensions = [json()]

const showReport = (id) => Inertia.get(route('session.report.show', {session : props.session.id, report: id}))
const deleteSession = () => Inertia.delete(route('session.delete', props.session.id))
const back = () => Inertia.get(route('projects.simulations.show', { project: props.session.simulation.project_id, simulation: props.session.simulation_id }))

let updateInterval = 0;

</script>

