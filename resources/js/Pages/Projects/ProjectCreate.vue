<template>
    <SiteHead title="Project Create" />

    <AppLayout>
        <AmazingMap ref="map" :preview="true" />

        <SlideOver
            title="Create a Project"
            headerBackground="bg-purple-600"
            subtitleTextColor="text-gray-100"
            alwaysOpen
        >
            <!-- {{ form.errors }} -->
            <!-- Project Name -->
            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <TextInput
                        v-model="form.name"
                        description="The name that this Project is going to have."
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

            <!-- Project Description -->
            <div
                class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
            >
                <div class="sm:col-span-3">
                    <TextInput
                        v-model="form.description"
                        description="The description that this Project is going to have."
                        label="Description"
                    />
                </div>
                <JetInputError
                    v-show="form.errors.description"
                    :message="form.errors.description"
                    class="mt-2"
                />
            </div>

            <!-- Project Area -->
            <div class="divide-y">
                <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                    <div class="block text-base font-medium text-gray-900 sm:pt-1">
                        <p>Project Area</p>
                    </div>
                </div>
                <div class="space-y-1 px-4 sm:space-y-0 sm:gap-4 sm:px-6 sm:py-5">
                    <div class="sm:grid sm:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 sm:pt-1 pb-10">
                                NorthEast
                            </label>
                            <label class="block text-sm font-medium text-gray-500 sm:pt-1">
                                SouthWest
                            </label>
                        </div>
                        <div class="col-span-2">
                            <div class="block text-sm font-medium text-gray-900 sm:pt-1 pb-4" v-if="selectArea[1]">
                                <p>lat: {{ selectArea[0][0] }}</p>
                                <p>lng: {{ selectArea[0][1] }}</p>
                            </div>
                            <div class="block text-sm font-medium text-gray-900 sm:pt-1" v-if="selectArea[1]">
                                <p>lat: {{ selectArea[1][0] }}</p>
                                <p>lng: {{ selectArea[1][1] }}</p>
                            </div>
                        </div>
                        <div class="flex place-content-center">
                            <SecondaryButton
                                variant="location"
                                type="button"
                                @click="onSelectArea()"
                            >
                                Select Area
                                <CursorClickIcon class="h-6 w-auto text-blue-500" aria-hidden="true" />
                            </SecondaryButton>
                        </div>
                    </div>
                </div>
            </div>

            <template #actions>
                <SecondaryOutlinedButton
                    type="button"
                    :disabled="form.processing"
                    @click="onCancel"
                >
                    Cancel
                </SecondaryOutlinedButton>
                <PrimaryButton @click="onSubmit()" :disabled="form.processing">
                    Save
                </PrimaryButton>
            </template>
        </SlideOver>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useForm } from "@inertiajs/inertia-vue3";

import mapUtils from "@/Utils/map.js";
import { CursorClickIcon } from "@heroicons/vue/solid";
import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useStore } from "vuex";

const store = useStore();

const props = defineProps({
    locations: {
        type: Array,
        default: [],
    },
});

const map = ref(null);

const form = useForm({
    name: "",
    description: "",
    _data: {},
});

const location = ref({});

const locations = computed(() =>
    props.locations.map((l) => ({
        key: l.id,
        value: l.name,
    }))
);

const selectArea = computed(() => store.getters["map/selectedArea"]);
const onSelectArea = () => {
    mapUtils.setArea(true);
    store.dispatch("map/startArea", true);
};

const onCancel = () => {
    Inertia.visit(route("projects.index"));
};

watch(
    location,
    (location) => {
        if (location.key) form.location_id = location.key;
    },
    { deep: true }
);

const onSubmit = () => {
    if (!selectArea.value[0]) {
        store.commit("objects/setNotify", {
            title: 'Select Area',
            text: 'Please, select area on the map.',
            type: 'danger'
        });
        return;
    }
    console.log(form._data);
    form._data = { polygon: store.getters["map/selectedArea"] };
    form.post(route("projects.store"), {
        onError: (error) => {
            store.commit("objects/setNotify", {...error});
        },
        onSuccess: () => {
            Inertia.visit(route("projects.index"));
        },
    });
};
</script>
