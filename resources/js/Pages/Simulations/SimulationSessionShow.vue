<template>
    <AppLayout>
        <div class="bg-white h-screen overflow-y-scroll">
            <div class="grid grid-cols-1 gap-4 h-full">
                <div class="pt-16 px-4 sm:px-6 lg:pt-20 lg:px-8">
                    <div class="flex justify-between">
                        <button
                            class="bg-gray-600 hover:bg-gray-900 font-bold py-1 px-2 my-2 rounded-md text-white flex"
                            @click="back">

                            <ChevronLeftIcon class="w-6 h-6"></ChevronLeftIcon>
                            <span>Simulation Details</span>

                        </button>
                        <button class="bg-red-600 hover:bg-red-900 font-bold py-1 px-2 my-2 rounded-md text-white"
                                @click="shouldDelete = true">Delete Session
                        </button>
                    </div>
                    <h2 class="text-lg font-bold">Simulation Session Details</h2>

                    <div
                        class="flex justify-between border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-gray-50">
                        <div>
                            <p><strong>Project :</strong> {{ session.simulation.project.name }}</p>
                            <p><strong>Simulation name :</strong> {{ session.simulation.name }}</p>
                            <p><strong>Session uuid :</strong> {{ session.simulation_uuid }}</p>
                            <p><strong>Created_at :</strong> {{ moment(session.created_at).format('DD/MM/YYYY HH:mm:ss') }}</p>
                            <ToggleButton v-model="advancedMode" label="Advanced Mode" />

                        </div>


                        <div >
                            <div v-if="session.challenge.length > 0" class="flex">
                                <jet-label value="Challenge Participation: "/> &nbsp;
                                <jet-label :value="session.challenge[0].name"/>
                            </div>

                            <field label="Submit Challenge Participation" v-else-if="isEnrolled">
                                <VSelect :options="challenges"
                                         class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                         label="name" value="pivot.id"
                                         @update:modelValue="confirmEnroll = true"
                                         v-model="challenge"/>

                            </field>
                        </div>

                    </div>



                    <div class="flex justify-end">
                        <div class="flex my-2 gap-2">

                            <primary-button
                                @click.prevent.stop="downloadData({key:'csv'})">
                                Download Results in CSV <DownloadIcon class="ml-2"/>
                            </primary-button>

                            <primary-button
                                @click.prevent.stop="downloadData({key:'json'})">
                                Download Results in JSON <DownloadIcon class="ml-2"/>
                            </primary-button>

                            <primary-button
                                @click.prevent.stop="downloadInputs({ project: {...session.simulation.project}}, `${session.simulation.project.name}-${session.simulation.name}-${session.simulation_uuid}-INPUTS`, session.simulation.id)">
                                Download Inputs <DownloadIcon class="ml-2"/>
                            </primary-button>

                        </div>
                    </div>
                </div>

                <div class=" px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-center">
                        <h2 class="text-lg font-bold">Simulation Progress</h2>
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


                    <template v-if="!advancedMode">
                        <!-- component -->
                        <div class=" py-6 flex flex-col justify-center sm:py-12 bg-gray-200">
                            <div class="py-3 sm:max-w-xl sm:mx-auto w-full px-2 sm:px-0">

                                <div class="relative text-gray-700 antialiased text-sm font-semibold">

                                    <!-- Vertical bar running through middle -->
                                    <div class="hidden sm:block w-1 bg-blue-300 absolute h-full left-1/2 transform -translate-x-1/2"></div>

                                    <!-- Left section, set by justify-start and sm:pr-8 -->
                                    <!-- Right section, set by justify-end and sm:pl-8 -->
                                    <div class=" sm:mt-0 " v-for="(report,index ) of processedReports" :key="report.id">
                                        <div class="flex flex-col sm:flex-row items-center">
                                            <div class="flex  w-full mx-auto items-center" :class="{'justify-start': index%2 === 0, 'justify-end': index%2 !== 0 }">
                                                <div class="w-full sm:w-1/2" :class="{'sm:pr-8': index%2 === 0, 'sm:pl-8': index%2 !== 0 }">
                                                    <div class="bg-white rounded shadow">

                                                        <div
                                                             class="border shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs "
                                                            :class="{' border-red-300 bg-red-50': report.errors , 'border-green-300 bg-green-50': !report.errors}">
                                                            <p>Module : {{ report.module }} </p>
                                                            <p>Function : {{ report.function }} </p>
                                                            <p>Created_at : {{ moment(report.created_at).format('DD/MM/YYYY') }} </p>
                                                            <p v-if="solverModules.hasOwnProperty(report.module) && solverModules[report.module] !== null">
                                                                Solver : {{ solverModules[report.module] }} </p>
                                                            <p>Duration : {{ bench(report.created_at, report.function) }} </p>

                                                            <div class="my-2" v-if="!report.errors">
                                                                <div class="gap-2 flex justify-around">

                                                                    <template v-if="!['SIMULATION STARTED', 'SIMULATION FINISHED'].includes(report.function)">
                                                                        <button class="inline-flex items-center justify-center w-8 h-8 mr-2 text-pink-100 transition-colors duration-150 bg-gray-700 rounded-full focus:shadow-outline hover:bg-gray-600"
                                                                                v-tippy="'Download Input Data'"
                                                                                @click.prevent.stop="downloadObjectAsJson(report.data, `${report.module}-${report.function}-INPUT`,report.id, 'data')">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m-6 3.75l3 3m0 0l3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                                                                            </svg>

                                                                        </button>

                                                                        <button class="inline-flex items-center justify-center w-8 h-8 mr-2 text-pink-100 transition-colors duration-150 bg-gray-700 rounded-full focus:shadow-outline hover:bg-gray-600"
                                                                                v-tippy="'Download Output Data'"
                                                                                @click.prevent.stop="downloadObjectAsJson(report.output, `${report.module}-${report.function}-OUTPUT`,report.id, 'output')">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h-.75A2.25 2.25 0 004.5 9.75v7.5a2.25 2.25 0 002.25 2.25h7.5a2.25 2.25 0 002.25-2.25v-7.5a2.25 2.25 0 00-2.25-2.25h-.75m0-3l-3-3m0 0l-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-7.5a2.25 2.25 0 01-2.25-2.25v-.75" />
                                                                            </svg>

                                                                        </button>

                                                                    </template>

                                                                    <a class="inline-flex items-center justify-center w-8 h-8 mr-2 text-pink-100 transition-colors duration-150 bg-cyan-700 rounded-full focus:shadow-outline over:bg-cyan-900"
                                                                            v-tippy="'Show Report'"
                                                                        v-if="reportsHtml[report.id]"
                                                                        target="_blank"
                                                                        :href="route('session.report.show', {session : session.id, report: report.id})">

                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                                        </svg>


                                                                    </a>

                                                                    <a v-if="report.function === 'optimize_network'"
                                                                       class="inline-flex items-center justify-center w-8 h-8 mr-2 text-pink-100 transition-colors duration-150 bg-cyan-700 rounded-full focus:shadow-outline over:bg-cyan-900"
                                                                       target="_blank"
                                                                       v-tippy="'Show Network Report'"
                                                                       :href="route('session.map.show', {session : report.id})">

                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                                                        </svg>

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
                                                            <div v-else class="mt-2">
                                                                <button
                                                                    @click.prevent.stop="downloadObjectAsJson(report.data, `${report.module}-${report.function}-INPUT`,report.id, 'data')"
                                                                   class="bg-cyan-700 hover:bg-cyan-900 py-2 text-left font-medium text-sm px-4 rounded-lg text-white block w-full mb-2">Download Input
                                                                    <ChevronRightIcon class="w-5 h-5 float-right"></ChevronRightIcon>
                                                                </button>
                                                                Error :<span class="font-bold">{{ report.errors.detail }}</span>
                                                            </div>

                                                        </div>


                                                    </div>

                                                </div>
                                            </div>
                                            <div class="rounded-full bg-green-600 text-white border-white border-4 w-8 h-8 absolute left-1/2 -translate-y-4 sm:translate-y-0 transform -translate-x-1/2 flex items-center justify-center">

                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>

                                            </div>
                                        </div>


                                        <!-- Loading -->
                                        <div class="mt-6 sm:mt-0 sm:mb-12" v-if="index === Object.keys(processedReports).length -1 && report.function !== 'SIMULATION FINISHED'">
                                            <div class="flex flex-col sm:flex-row items-center">
                                                <div class="flex justify-start w-full mx-auto items-center">
                                                    <div class="w-full sm:w-1/2 sm:pr-8" >
                                                        <div class="bg-white rounded shadow">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rounded-full bg-blue-500 border-white border-4 w-8 h-8 absolute left-1/2 -translate-y-4 sm:translate-y-0 transform -translate-x-1/2 flex items-center justify-center">
                                                    <svg  xmlns="http://www.w3.org/2000/svg" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div>

                            </div>
                        </div>

                    </template>
                    <template v-else>
                        <div v-for="report of processedReports" :key="report.id">
                            <div v-if="report.errors"
                                 class="border border-red-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-red-50">
                                <p>Module : {{ report.module }} </p>
                                <p>Function : {{ report.function }} </p>
                                <p>Created_at : {{ moment(report.created_at).format('DD/MM/YYYY HH:mm:ss') }} </p>
                                <p v-if="solverModules.hasOwnProperty(report.module) && solverModules[report.module] !== null">
                                    Solver : {{ solverModules[report.module] }} </p>
                                <p>Duration : {{ bench(report.created_at, report.function) }} </p>
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
                                <p>Created_at : {{ moment(report.created_at).format('DD/MM/YYYY HH:mm:ss') }} </p>
                                <p v-if="solverModules.hasOwnProperty(report.module) && solverModules[report.module] !== null">
                                    Solver : {{ solverModules[report.module] }} </p>
                                <p>Duration : {{ bench(report.created_at, report.function) }} </p>
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
                                    <a v-if="report.function === 'optimize_network'"
                                       target="_blank"
                                       style="margin-top: 10px"
                                       :href="route('session.map.show', {session : report.id})"
                                       class="bg-cyan-700 hover:bg-cyan-900 py-2 text-left font-medium text-sm px-4 rounded-lg text-white block w-full">Network
                                        Report
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
                    </template>


                </div>
            </div>
        </div>
        <jet-confirmation-modal
            :show="confirmEnroll"
            @close="confirmEnroll = false">
            <template #title> Challenge Submit</template>

            <template #content>
                are you sure you want to submit to {{ challenge?.name }}?
            </template>

            <template #footer>
                <SecondaryOutlinedButton @click="confirmEnroll = false">
                    Cancel
                </SecondaryOutlinedButton>

                <PrimaryButton
                    class="ml-2"
                    @click="enroll">
                    Confirm
                </PrimaryButton>
            </template>
        </jet-confirmation-modal>

        <jet-confirmation-modal
            :show="shouldDelete"
            @close="shouldDelete = false">
            <template #title> Delete Simulation Session</template>

            <template #content>
                Are you sure you want to remove this simulation session ?
            </template>

            <template #footer>
                <SecondaryOutlinedButton @click="shouldDelete = false">
                    Cancel
                </SecondaryOutlinedButton>

                <PrimaryButton
                    class="ml-2"
                    @click="deleteSession">
                    Confirm
                </PrimaryButton>
            </template>
        </jet-confirmation-modal>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout";
import {computed, onMounted, onUnmounted, ref, getCurrentInstance} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import VSelect from "vue-select";
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import {ChevronUpIcon, ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/solid'
import {Codemirror} from 'vue-codemirror'
import {json} from "@codemirror/lang-json";
import {Inertia} from "@inertiajs/inertia";
import axios from 'axios'
import moment from 'moment'
import JSZip from 'jszip'
import JetLabel from "@/Jetstream/Label";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import {notify} from "@kyvg/vue3-notification";
import DownloadIcon from "../../Components/Icons/DownloadIcon.vue";
import Button from "../../Jetstream/Button.vue";
import Field from "../../Components/Field.vue";
import ToggleButton from "../../Components/ToggleButton.vue";

const props = defineProps({
    session: Object,
    reports: Array,
    reportsHtml: Array,
    challenges: Array,
    solverModules: Object,
    isEnrolled: Boolean
});

let downloadOption = ref({})
let challenge = ref(null)
const downloadOptions = [{value: 'Download in CSV', key: 'csv'}, {value: 'Download in JSON', key: 'json'}]
const advancedMode = ref(false)
const shouldDelete = ref(false)

const confirmEnroll = ref(false);
const enroll = () => {
    confirmEnroll.value = false
    let value = challenge.value
    axios.post('/submit-challenge', {
        challenge_user: value?.pivot?.id,
        challenge: value?.id,
        session: props.session.id
    }).then(({data}) => {
        if (!data.error) {
            notify({
                group: "notifications",
                title: "Challenge",
                text: 'Simulation submitted.',
                data: {
                    type: "success",
                },
            });
            Inertia.get(route('session.show', {id: props.session.id}))
        }
        confirmEnroll.value = false
    })
}

const modulesJson = []
let first = props.session.created_at
let start = null

const bench = (date, functi) => {
    if (functi === 'SIMULATION STARTED') {
        first = date
        start = date
        return '0:00:00'
    } else if (functi === 'SIMULATION FINISHED') {
        let ms = moment(date).diff(moment(start));
        let d = moment.duration(ms);
        return Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
    }
    let ms = moment(date).diff(moment(first));
    let d = moment.duration(ms);
    let s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss");
    first = date
    return s
}

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
    return axios.post('/csv-report/' + props.session.id, {
        isJson
    })
        .then(({data}) => {
            return data
        })
}

const downloadData = async (event) => {
    if (event && event.key === 'json') {
        let data = await downloadFullJson(true)
        await downloadObjectAsJson(JSON.stringify(data), `${props.session.simulation.project.name}-${props.session.simulation.name}-${props.session.simulation_uuid}-FULL`)
    } else if (event && event.key === 'csv') {
        let data = await downloadFullJson(false)
        const zip = new JSZip();
        await zip.loadAsync(data, {base64: true});
        const blob = await zip.generateAsync({type: "blob"});

        const element = document.createElement("a");
        element.setAttribute("href", window.URL.createObjectURL(blob));
        element.setAttribute("download", 'data.zip');
        element.style.display = "none";
        document.body.appendChild(element);
        element.click();
        document.body.removeChild(element);
    }
}

const downloadInputs = async (data, exportName, simulationId) => {
    const extra = await getJsonFrom('extra', simulationId)
    console.log(extra)
    downloadObjectAsJson(JSON.stringify({...extra, ...data}), exportName)
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
    if (type === 'extra' || (report[type] && report[type][0] === 'Loading...' || report[type] === '["Loading..."]')) {
        return axios.post(`/json-report/${type}/${id}`)
            .then(({data}) => {
                if (type === 'extra') {
                    return data
                }
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

