<template>
    <AppLayout>
        <div class="bg-white h-screen overflow-y-scroll">
            <div class="grid grid-cols-2 gap-4">
                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
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

                        <div v-if="report.output.report"
                            class="border border-green-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-green-50">
                            <p>Module : {{ report.module }} </p>
                            <p>Function : {{ report.function }} </p>
                            <p class="font-bold text-sm"><a href="#">Module Report</a></p>
                        </div>

                        <div v-if="report.output"
                            class="border border-green-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-green-50">
                            <p>Module : {{ report.module }} </p>
                            <p>Function : {{ report.function }} </p>
                            <p class="font-bold text-sm">Function Provides no Report</p>
                            <div class="my-2">
                                <Disclosure v-slot="{ open }">
                                    <DisclosureButton
                                        class="flex w-full justify-between rounded-lg bg-gray-700 px-4 py-2 text-left text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                                        <span>Input Data</span>
                                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''"
                                            class="h-5 w-5 text-purple-500" />
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
                                            class="h-5 w-5 text-purple-500" />
                                    </DisclosureButton>
                                    <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                                        <Codemirror v-model="report.output" :extensions="extensions"
                                            :style="{ height: '400px' }" :autofocus="true" :indent-with-tab="true"
                                            :tabSize="2" disabled></Codemirror>
                                    </DisclosurePanel>
                                </Disclosure>
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
import { computed, ref } from "vue";
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
import { ChevronUpIcon } from '@heroicons/vue/solid'
import { Codemirror } from 'vue-codemirror'
import { json } from "@codemirror/lang-json";

const props = defineProps({
    session: Object,
    reports: Array
});

const stepInfo = computed(() => {
    return []
});

const processedReports = computed(() => {
    return props.reports.map((a) => ({ ...a, data: JSON.stringify(a.data, null, 2), output: JSON.stringify(JSON.parse(a.output), null, 2) }))
})

const extensions = [json()]
</script>


