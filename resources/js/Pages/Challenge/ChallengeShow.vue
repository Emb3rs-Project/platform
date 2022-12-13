<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Challenge Detail
            </h2>
        </template>
        <div class="grid grid-cols-1 p-12 gap-y-10">
            <div>
                <div class="grid grid-cols-3">
                    <div>
                        <div class="flex items-center gap-1">
                            <button
                                class="bg-gray-600 hover:bg-gray-900 font-bold py-1 px-2 my-2 rounded-md text-white flex"
                                @click="back">

                                <ChevronLeftIcon class="w-6 h-6"></ChevronLeftIcon>

                            </button>
                            <h3 class="text-lg font-medium leading-6 text-gray-900">
                                Challenge Detail
                            </h3>
                        </div>
                    </div>
                    <div class="col-span-2">
                        <div
                            class="bg-white overflow-hidden shadow sm:rounded-lg"
                        >
                            <div class="px-4 py-5 sm:p-6">
                                <input-row
                                    desc="Challenge"
                                    v-model="challenge.name"
                                    :disabled="true"
                                >
                                </input-row>

                                <input-row
                                    desc="Challenge Description"
                                    v-model="challenge.description"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                </input-row>

                                <input-row
                                    desc="Challenge Goal"
                                    v-model="goal"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                </input-row>

                                <input-row
                                    desc="Challenge Restrictions"
                                    v-model="restrictions"
                                    type="textarea"
                                    :disabled="true"
                                    class="mt-14"
                                >
                                </input-row>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex sm:col-span-3 justify-start">
                    <PrimaryButton
                        v-if="!isEnrolled"
                        class="mr-4"
                        @click="confirmEnroll = true">
                        Enroll Challenge
                    </PrimaryButton>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-3">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Map
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Area</p>
                    </div>
                    <div class="col-span-2">
                        <div
                            class="bg-white overflow-hidden shadow sm:rounded-lg"
                        >
                            <div class="px-4 py-5 sm:p-6">
                                <LeafletMap
                                    ref="map"
                                    :instances="instances"
                                    :links="links"
                                ></LeafletMap>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="grid grid-cols-3">
                    <div>
                        <h3 class="text-lg font-medium leading-6 text-gray-900">
                            Participants
                        </h3>
                        <p class="mt-1 text-sm text-gray-600"></p>
                    </div>
                    <div class="col-span-2">
                        <div
                            class="bg-white overflow-hidden shadow sm:rounded-lg"
                        >
                            <div class="px-4 py-5 sm:p-6">
                                <AmazingIndexTable
                                    v-model="participants"
                                    :columns="tableColumns"
                                    :hasCheckbox="false"
                                    headerClasses="shadow overflow-hidden sm:rounded-lg"
                                >
                                    <!-- ID -->
                                    <template #header-id> ID</template>
                                    <template #body-id="{ item }">
                                        <td class="text-left pl-4">
                                            {{ item.session_id || item.pivot.id }}
                                        </td>
                                    </template>

                                    <!-- Name -->
                                    <template #header-name> User</template>
                                    <template #body-name="{ item }">
                                        <td
                                            class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                        >
                                            {{ item.name }}
                                        </td>
                                    </template>

                                    <!-- Description -->
                                    <template #header-submit_date>
                                        Submit Date
                                    </template>
                                    <template #body-submit_date="{ item }">
                                        <td
                                            class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                        >
                                            {{ moment(item.pivot.created_at).format('DD/MM/YYYY') }}
                                        </td>
                                    </template>

                                    <!-- G -->
                                    <template #header-goal_value>
                                        Goal Value
                                    </template>
                                    <template #body-goal_value="{ item }">
                                        <td
                                            class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                        >
                                            {{ item.goal_value }} <span
                                            v-if="item.goal_unit"> ({{ item.goal_unit }})</span>
                                        </td>
                                    </template>

                                    <!-- G -->
                                    <template #header-report>
                                        Report
                                    </template>
                                    <template #body-report="{ item }">
                                        <td
                                            class="text-left px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500"
                                        >
                                            <Link
                                                method="get"
                                                class="px-5 py-2 bg-slate-500 font-semibold text-white rounded-sm"
                                                as="button"
                                                :href="item.session_url"
                                            >Report
                                            </Link>
                                        </td>
                                    </template>

                                </AmazingIndexTable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <jet-confirmation-modal
            :show="confirmEnroll"
            @close="confirmEnroll = false">
            <template #title> Challenge Enroll</template>

            <template #content>
                are you sure you want to enroll the challenge?
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
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout";
import InputRow from "@/Components/InputRow";
import {ChevronLeftIcon} from '@heroicons/vue/solid'
import {Inertia} from "@inertiajs/inertia";
import AmazingIndexTable from "@/Components/Tables/AmazingIndexTable.vue";

import LeafletMap from "@/Components/LeafletMap";
import {onMounted, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import {Link} from "@inertiajs/inertia-vue3";
import moment from 'moment';
import {notify} from "@kyvg/vue3-notification";

const props = defineProps({
    challenge: {
        type: Object,
        required: true,
    },
    participants: {
        type: Array,
        required: true,
    },
    instances: {
        type: Array,
        required: true,
    },
    links: {
        type: Array,
        required: true,
    },
    user: {
        type: Object,
        default: {}
    },
    isEnrolled: {
        type: Boolean,
        default: false
    }
})
const tableColumns = ["id", "name", 'submit_date', 'goal_value', 'report'];
const back = () => Inertia.get(route('challenges.index'))
const goal = props.challenge.goal ? props.challenge.goal.name : ''
let restrictions = ''
if (props.challenge.restrictions) {
    restrictions = props.challenge.restrictions.flatMap((restriction) => {
        return restriction.name + ': ' + restriction.pivot.value
    }).join('\n')
}
const confirmEnroll = ref(false);
const enroll = () => {
    axios.post('/enroll-challenge', {
        challenge: props.challenge.id,
        user: props.user.id
    }).then(({data}) => {
        if (!data.error) {
            notify({
                group: "notifications",
                title: "Enroll",
                text: 'Enrolled with success.',
                data: {
                    type: "success",
                },
            });
            Inertia.get(route('challenges.show', {id: props.challenge.id}))
        }
        confirmEnroll.value = false
    })
}

const map = ref(null);

</script>

