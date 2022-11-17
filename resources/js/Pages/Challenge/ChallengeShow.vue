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
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout";
import InputRow from "@/Components/InputRow";
import {ChevronLeftIcon} from '@heroicons/vue/solid'
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    challenge: {
        type: Object,
        required: true,
    }
})
const back = () => Inertia.get(route('challenges.index'))
const goal = props.challenge.goal ? props.challenge.goal.name : ''
let restrictions = ''
if (props.challenge.restrictions) {
    restrictions = props.challenge.restrictions.flatMap((restriction) => {
        return restriction.name + ': ' + restriction.pivot.value
    }).join('\n')
}

</script>

