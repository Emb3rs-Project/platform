<template>
    <AppLayout>
        <div class="bg-white h-screen overflow-y-scroll">
            <div class="grid grid-cols-2 gap-4">
                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <h2 class="text-lg font-bold">Simulation Session Details</h2>

                     <div class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 text-xs bg-gray-50">
                        session uuid : {{ session.simulation_uuid }} <br />
                        created_at : {{ session.created_at }}
                    </div>

                </div>

                <div class="py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                    <h2 class="text-lg font-bold">Simulation Step Data</h2>

                    <p>No Data yet...</p>
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
    session: Object,
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
