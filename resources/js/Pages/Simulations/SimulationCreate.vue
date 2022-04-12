<template>
    <AppLayout>
        <div class="bg-white min-h-screen">
            <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                    <div class="mt-12 lg:mt-0 lg:col-span-3">
                        <div class="mt-10 sm:mt-0 p-10">
                            <div class="md:grid md:grid-cols-3 md:gap-6">
                                <pre>{{ project_instances }}</pre>
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
import { useForm } from "@inertiajs/inertia-vue3";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import CheckboxRow from "@/Components/CheckboxRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";
import { ref } from "@vue/reactivity";
import { computed } from "vue";

import { polygon, marker } from "leaflet";

const props = defineProps({
    instances: Array,
    project: Object,
});

const form = useForm({
    name: "",
    type: null,
    target: null,
    location: null,
    notification: null,
    constraint_type: {},
    constraint_value: {},
    sources: {},
    sinks: {},
    simulators: {},
    links: {},
});

const project_poly = polygon(props.project.data.polygon);
const project_instances = props.instances.filter((r) =>
    project_poly
        .getBounds()
        .contains(marker(r.location.data.center).getLatLng())
);
</script>
