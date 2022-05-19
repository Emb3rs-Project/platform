<template>
    <AppLayout>
        <div class="bg-white h-screen overflow-y-scroll">
            <div class="grid grid-cols-2 gap-4">
                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <h2 class="text-lg font-bold">Simulation Details</h2>

                    <div
                        v-if="simulation.simulation_metadata"
                        class="py-5 text-left"
                    >
                        <h2 class="text-md font-semibold">
                            Simulation Metadata
                        </h2>
                        <div
                            class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 bg-gray-50 text-xs"
                        >
                            Name :
                            <b>{{ simulation.simulation_metadata.name }}</b>
                            <br />
                            Type :
                            <b>{{
                                simulation.simulation_metadata.data.type
                            }}</b>
                        </div>

                        <!-- Simulation Steps -->
                        <h2 class="pt-2">
                            Simulation Steps
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-green-100 text-gray-800"
                            >
                                Start
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-gray-100 text-gray-800"
                            >
                                Middle
                            </span>

                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-red-100 text-gray-800"
                            >
                                Finish
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full mx-1 text-xs font-medium bg-orange-100 text-gray-800"
                            >
                                User
                            </span>
                        </h2>
                        <div
                            class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs"
                            :class="{
                                'bg-gray-50':
                                    index != 0 && index != stepInfo.length - 1,
                                'bg-green-100': index == 0,
                                'bg-red-100': index == stepInfo.length - 1,
                            }"
                            v-for="(step, index) of stepInfo"
                            :key="step"
                        >
                            {{ step.step }} - {{ step.module }} (<b>{{
                                step.function
                            }}</b
                            >)
                        </div>
                    </div>
                    <div class="p-5 border shadow-lg bg-gray-50 float-right">
                        <Link
                            method="post"
                            class="px-5 py-2 bg-slate-500 font-semibold text-white rounded-sm"
                            as="button"
                            :href="
                                route('projects.simulations.run', {
                                    project: project.id,
                                    simulation: simulation.id,
                                })
                            "
                            >Run Simulation</Link
                        >
                    </div>
                </div>

                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <h2 class="text-lg font-bold">Simulation Sessions</h2>
                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-gray-50"
                        v-for="session of simulation.simulation_sessions"
                        @click=""
                        :key="session"
                    >
                        <Link :href="route(
                            'session.show', session.id
                        )">session uuid : {{ session.simulation_uuid }}</Link> <br />
                        created_at : {{ session.created_at }}
                    </div>
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

const props = defineProps({
    project: Object,
    simulation: Object,
});

const stepInfo = computed(() => {
    return []
    // if (!props.simulation.simulation_metadata) return [];
    // const steps = Object.keys(props.simulation.simulation_metadata.data).filter(
    //     (a) => !["start", "type"].includes(a)
    // );

    // return steps.map((s) => ({
    //     step: s,
    //     module: props.simulation.simulation_metadata.data[s].module.name,
    //     function: props.simulation.simulation_metadata.data[s].function,
    // }));
});
</script>
