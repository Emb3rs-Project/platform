<template>
    <AppLayout>
        <div class="bg-white h-screen overflow-y-scroll">
            <div class="grid grid-cols-2 gap-4 h-full">
                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <div class="flex justify-between">
                        <button
                            class="bg-gray-600 hover:bg-gray-900 font-bold py-1 px-2 my-2 rounded-md text-white flex"
                            @click="back">

                            <ChevronLeftIcon class="w-6 h-6"></ChevronLeftIcon>
                            <span>Simulation Details</span>

                        </button>
                        <button class="bg-red-600 hover:bg-red-900 font-bold py-1 px-2 my-2 rounded-md text-white"
                                @click="deleteSession">Delete Session
                        </button>
                    </div>
                    <h2 class="text-lg font-bold">Simulation Session Details</h2>

                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-gray-50">
                        <p><strong>Project :</strong> {{ session.simulation.project.name }}</p>
                        <p><strong>Simulation name :</strong> {{ session.simulation.name }}</p>
                        <p><strong>session uuid :</strong> {{ session.simulation_uuid }}</p>
                        <p><strong>created_at :</strong> {{ moment(session.created_at).format('DD/MM/YYYY HH:mm:ss') }}
                        </p>
                    </div>

                </div>

                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <div class="flex justify-between">
                        <h2 class="text-lg font-bold">Simulation Step Data</h2>
                        <Field class="px-2 ml-auto" label="Download Data"
                               hint="Download Data">
                            <SelectMenu placeholder="Download Data"
                                        @update:modelValue="downloadData" :options="downloadOptions"></SelectMenu>
                        </Field>

                        <span style="cursor:pointer; text-decoration: underline"
                              class="ml-auto"
                              @click.prevent.stop="downloadObjectAsJson(JSON.stringify({...session.simulation.extra, project: {...session.simulation.project}}), `${session.simulation.project.name}-${session.simulation.name}-${session.simulation_uuid}-INPUTS`)">
                                Download Inputs
                            </span>
                    </div>
                    <div v-if="reports.length == 0" class="flex h-full items-center justify-center">
                        <svg aria-hidden="true"
                             class="mr-2 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                             viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor"/>
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill"/>
                        </svg>
                        <p>Waiting for module response...</p>
                    </div>
                    <div v-for="report of processedReports" :key="report.id">
                        <div v-if="report.errors"
                             class="border border-red-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-red-50">
                            <p>Module : {{ report.module }} </p>
                            <p>Function : {{ report.function }} </p>
                            <div class="my-2">
                                <Disclosure v-slot="{ open }">
                                    <DisclosureButton
                                        @click="getJsonFrom('data', report.id, false)"
                                        class="flex w-full justify-between rounded-lg bg-gray-700 px-4 py-2 text-left text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                                        <span>Input Data</span>
                                        <span style="cursor:pointer; text-decoration: underline"
                                              class="ml-auto mr-4"
                                              @click.prevent.stop="downloadObjectAsJson(report.data, `${report.module}-${report.function}-INPUT`,report.id, 'data')">
                                            Download
                                        </span>
                                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''"
                                                       class="h-5 w-5 "/>
                                    </DisclosureButton>
                                    <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                                        <Codemirror v-model="report.data" :extensions="extensions"
                                                    :style="{ height: '400px' }" :autofocus="true"
                                                    :indent-with-tab="true"
                                                    :tabSize="2" disabled></Codemirror>
                                    </DisclosurePanel>
                                </Disclosure>
                            </div>
                            <p>Error : <span class="font-bold">{{ report.errors.detail }}</span></p>
                        </div>

                        <div v-if="!report.errors"
                             class="border border-green-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-green-50">
                            <p>Module : {{ report.module }} </p>
                            <p>Function : {{ report.function }} </p>
                            <div class="my-2">
                                <Disclosure v-slot="{ open }">
                                    <DisclosureButton
                                        @click="getJsonFrom('data', report.id, false)"
                                        class="flex w-full justify-between rounded-lg bg-gray-700 px-4 py-2 text-left text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                                        <span>Input Data</span>
                                        <span style="cursor:pointer; text-decoration: underline"
                                              class="ml-auto mr-4"
                                              @click.prevent.stop="downloadObjectAsJson(report.data, `${report.module}-${report.function}-INPUT`,report.id, 'data')">
                                            Download
                                        </span>
                                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''"
                                                       class="h-5 w-5 "/>
                                    </DisclosureButton>
                                    <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                                        <Codemirror v-model="report.data" :extensions="extensions"
                                                    :style="{ height: '400px' }" :autofocus="true"
                                                    :indent-with-tab="true"
                                                    :tabSize="2" disabled></Codemirror>
                                    </DisclosurePanel>
                                </Disclosure>
                            </div>
                            <div class="my-2">
                                <Disclosure v-slot="{ open }">
                                    <DisclosureButton
                                        @click="getJsonFrom('output', report.id)"
                                        class="flex w-full justify-between rounded-lg bg-gray-700 px-4 py-2 text-left text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75">
                                        <span>Output Data</span>
                                        <span style="cursor:pointer; text-decoration: underline" class="ml-auto mr-4"
                                              @click.prevent.stop="downloadObjectAsJson(report.output, `${report.module}-${report.function}-OUTPUT`,report.id, 'output')">Download</span>
                                        <ChevronUpIcon :class="open ? 'rotate-180 transform' : ''"
                                                       class="h-5 w-5 "/>
                                    </DisclosureButton>
                                    <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                                        <Codemirror v-model="report.output" :extensions="extensions"
                                                    :style="{ height: '400px' }" :autofocus="true"
                                                    :indent-with-tab="true"
                                                    :tabSize="2" disabled></Codemirror>
                                    </DisclosurePanel>
                                </Disclosure>
                            </div>
                            <div class="mt-5 mb-2">
                                <a
                                    v-if="reportsHtml[report.id]"
                                    target="_blank"
                                    :href="route('session.report.show', {session : session.id, report: report.id})"
                                    class="bg-cyan-700 hover:bg-cyan-900 py-2 text-left font-medium text-sm px-4 rounded-lg text-white block w-full">Report
                                    <ChevronRightIcon class="w-5 h-5 float-right"></ChevronRightIcon>
                                </a>

                                <a v-if="report.function === 'SIMULATION FINISHED' && shouldShowTheFinalReport"
                                   target="_blank"
                                   :href="route('session.final-report.show', {session : session.id})"
                                   class="bg-cyan-700 hover:bg-cyan-900 py-2 text-left font-medium text-sm px-4 rounded-lg text-white block w-full">Final
                                    Report
                                    <ChevronRightIcon class="w-5 h-5 float-right"></ChevronRightIcon>
                                </a>
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
import {useForm} from "@inertiajs/inertia-vue3";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import CheckboxRow from "@/Components/CheckboxRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";
import {computed, onMounted, onUnmounted, ref, getCurrentInstance} from "vue";
import SiteHead from "@/Components/SiteHead.vue";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {polygon, marker} from "leaflet";
import VSelect from "vue-select";
import {COUNTRIES} from "./data/countries";
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import {ChevronUpIcon, ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/solid'
import {Codemirror} from 'vue-codemirror'
import {json} from "@codemirror/lang-json";
import {Inertia} from "@inertiajs/inertia";
import axios from 'axios'
import moment from 'moment'
import JSZip from 'jszip'

const props = defineProps({
    session: Object,
    reports: Array,
    reportsHtml: Array
});

let downloadOption = ref({})
const downloadOptions = [{label: 'CSV', value: 'csv'}, {label: 'JSON', value: 'json'}]


const modulesJson = []

const stepInfo = computed(() => {
    return []
});

const processedReports = computed(() => {
    return props.reports.map((a) => ({
        ...a,
        data: JSON.stringify(a.data, null, 2),
        output: JSON.stringify(JSON.parse(a.output), null, 2),
        output_data: JSON.parse(a.output) || []
    }))
})

const shouldShowTheFinalReport = computed(() => {
    //let reports = processedReports.value

    //return reports && reports.filter((report) => report.output_data.hasOwnProperty('report')).length > 1
    let reportsObject = {...props.reportsHtml}
    let reportCount = Object.values(reportsObject).filter(report => report)
    return props.reportsHtml && reportCount.length > 1
});


const extensions = [json()]

const slug = (str) => {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    let from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
    let to = "aaaaeeeeiiiioooouuuunc------";
    for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
}

const showReport = (id) => Inertia.get(route('session.report.show', {session: props.session.id, report: id}))
const deleteSession = () => Inertia.delete(route('session.delete', props.session.id))
const back = () => Inertia.get(route('projects.simulations.show', {
    project: props.session.simulation.project_id,
    simulation: props.session.simulation_id
}))

const downloadFullJson = (isJson) => {
    return axios.post('/csv-report/'+props.session.id, {
        isJson
    })
        .then(({data}) => {
            return data
        })
}

const downloadData = async (event) => {
    if (event && event.value === 'json') {
        let data = await downloadFullJson(true)
        await downloadObjectAsJson(JSON.stringify(data), `${props.session.simulation.project.name}-${props.session.simulation.name}-${props.session.simulation_uuid}-FULL`)
    } else if (event && event.value === 'csv') {
        let data = await downloadFullJson(false)
        const zip = new JSZip();
        await zip.loadAsync(data, {base64: true});
        const blob = await zip.generateAsync({type:"blob"});

        const element = document.createElement("a");
        element.setAttribute("href", window.URL.createObjectURL(blob));
        element.setAttribute("download", 'data.zip');
        element.style.display = "none";
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
}

const downloadObjectAsJson = async (exportObj, exportName, id = null, type = null) => {
    let isEmpty = exportObj === '[\n' +
        '  "Loading..."\n' +
        ']'
    if (isEmpty) {
        await getJsonFrom(type, id, type === 'output')
        let report = props.reports.find(report => report.id === id)
        exportObj = type === 'output' ? report[type] : JSON.stringify(report[type])
    }
    let dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(exportObj);
    let downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href", dataStr);
    downloadAnchorNode.setAttribute("download", slug(exportName) + ".json");
    document.body.appendChild(downloadAnchorNode); // required for firefox
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
}

const getJsonFrom = async (type, id, convert = true) => {
    let report = props.reports.find(report => report.id === id)
    if (report[type][0] === 'Loading...' || report[type] === '["Loading..."]') {
        return axios.post(`/json-report/${type}/${id}`)
            .then(({data}) => {
                if (convert) {
                    report[type] = JSON.stringify(data)
                } else {
                    report[type] = data
                }

            })
    }
}

let updateInterval = 0;

</script>

