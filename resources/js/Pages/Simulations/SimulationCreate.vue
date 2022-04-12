<template>
    <AppLayout>
        <div class="bg-white h-screen overflow-y-scroll">
            <div class="w-1/2 py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
                <h2 class="text-lg font-bold">Simulation Details</h2>

                <div v-if="form.simulation_metadata" class="py-5 text-left">
                    <h2 class="text-md font-semibold">Simulation Metadata</h2>
                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 bg-gray-50 text-xs"
                    >
                        Name : <b>{{ form.simulation_metadata.name }}</b> <br />
                        Type : <b>{{ form.simulation_metadata.data.type }}</b>
                    </div>

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
            </div>
        </div>
        <SlideOver
            title="Create a Simulation"
            headerBackground="bg-purple-600"
            subtitleTextColor="text-gray-100"
            alwaysOpen
        >
            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <TextInput
                        v-model="form.name"
                        description="The name that this Simulation is going to have."
                        label="Name"
                        :required="true"
                    />
                </div>
                <JetInputError
                    v-show="form.errors.name"
                    :message="form.errors.name"
                    class="mt-2"
                />
            </div>

            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-between">
                            <label
                                for="sim_metadata"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Simulation Metadata
                            </label>
                            <span
                                class="text-sm text-gray-500"
                                id="input-required"
                            >
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect
                                :options="simulation_metadata"
                                label="name"
                                value="id"
                                v-model="form.simulation_metadata"
                            />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            The simulation Metadata to use
                        </p>
                    </div>
                </div>
                <JetInputError
                    v-show="form.errors.name"
                    :message="form.errors.name"
                    class="mt-2"
                />
            </div>

            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-between">
                            <label
                                for="sim_metadata"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Sinks
                            </label>
                            <span
                                class="text-sm text-gray-500"
                                id="input-required"
                            >
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect
                                :options="sinks"
                                label="name"
                                value="id"
                                :multiple="true"
                                v-model="form.extra.sinks"
                            />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            Sinks to use in this Simulation
                        </p>
                    </div>
                </div>
                <JetInputError
                    v-show="form.errors.name"
                    :message="form.errors.name"
                    class="mt-2"
                />
            </div>

            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <div>
                        <div class="flex justify-between">
                            <label
                                for="sim_metadata"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Sources
                            </label>
                            <span
                                class="text-sm text-gray-500"
                                id="input-required"
                            >
                                Required
                            </span>
                        </div>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <VSelect
                                :options="sources"
                                label="name"
                                value="id"
                                :multiple="true"
                                v-model="form.extra.sources"
                            />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            Sinks to use in this Simulation
                        </p>
                    </div>
                </div>
                <JetInputError
                    v-show="form.errors.name"
                    :message="form.errors.name"
                    class="mt-2"
                />
            </div>

            <template #actions>
                <SecondaryOutlinedButton
                    type="button"
                    :disabled="form.processing"
                    @click="onCancel"
                >
                    Cancel
                </SecondaryOutlinedButton>
                <PrimaryButton @click="onSubmit" :disabled="form.processing">
                    Save
                </PrimaryButton>
            </template>
        </SlideOver>
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

import { polygon, marker } from "leaflet";
import VSelect from "vue-select";

const props = defineProps({
    instances: Array,
    project: Object,
    simulation_metadata: Array,
});

const form = useForm({
    name: "Simulation Name",
    simulation_metadata: props.simulation_metadata[1],
    extra: {
        input_data: {},
        sinks: [],
        sources: [],
        steps: 0,
    },
});

const onSubmit = () => {
    form.extra.steps = stepInfo.value.length;
    form.post(route("projects.simulations.store", { id: props.project.id }));
};
const onCancel = () => {};

const project_poly = polygon(props.project.data.polygon);
const project_instances = props.instances.filter((r) =>
    project_poly
        .getBounds()
        .contains(marker(r.location.data.center).getLatLng())
);

const sinks = computed(() => {
    if (!project_instances) return [];
    return project_instances.filter((i) => i.template.category.type == "sink");
});

const sources = computed(() => {
    if (!project_instances) return [];
    return project_instances.filter(
        (i) => i.template.category.type == "source"
    );
});

const stepInfo = computed(() => {
    if (!form.simulation_metadata) return [];
    const steps = Object.keys(form.simulation_metadata.data).filter(
        (a) => !["start", "type"].includes(a)
    );

    return steps.map((s) => ({
        step: s,
        module: form.simulation_metadata.data[s].module.name,
        function: form.simulation_metadata.data[s].function,
    }));
});
</script>
