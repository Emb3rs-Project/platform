<template>
    <!-- Source Information -->
    <div
        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
        <PropertyDisclosure defaultOpen title="Information">
            <div class="my-4 flex">
                <div class="w-full">
                    <SelectMenu  :class="{'w-5/6': selectedTemplate.info}"
                        v-model="selectedTemplate"
                        :options="templates"
                        label="Template"
                    />
                </div>

                <div class="mt-6" v-if="selectedTemplate.info">
                  <button
                    title="Info"
                    type="button"
                    class="inline-flex items-center h-10 px-2.5 py-2 border border-transparent text-xs font-medium border-gray-300 rounded shadow-sm text-blue-600 hover:text-white bg-white hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    @click="infoTemplateModalIsVisible = true"
                  >
                    <InfoIcon class="font-medium text-sm w-5" />
                  </button>
                </div>
                <div v-for="(error, key) in errors" :key="key">
                    <div v-if="key === 'template_id'">
                        <div v-for="(e, eIdx) in error" :key="eIdx">
                            <JetInputError :message="e" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-1 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-4 sm:py-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                        Location
                    </label>
                </div>
                <div class="sm:col-span-1">
                    <div>
                        <TextInput
                            v-model="selectedLocation.key.lat"
                            @update:modelValue="updateMarker()"
                            :disabled="!custom"
                            min="-90"
                            max="90"
                            type="number"
                            unit="lat"
                        />
                    </div>
                </div>
                <div class="sm:col-span-1">
                    <div>
                        <TextInput
                            v-model="selectedLocation.key.lng"
                            @update:modelValue="updateMarker()"
                            :disabled="!custom"
                            min="-180"
                            max="180"
                            type="number"
                            unit="lng"
                        />
                    </div>
                </div>
                <div class="flex items-center">
                    <jet-checkbox
                        id="custom-marker"
                        name="custom-marker"
                        v-model:checked="custom"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label
                        for="custom-marker"
                        class="ml-2 block text-sm text-gray-900"
                    >
                        Fill in your own coordinates
                    </label>
                </div>
            </div>
        </PropertyDisclosure>
    </div>

    <!-- Source Properties-->
    <div
        v-if="properties.length"
        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
        <PropertyDisclosure defaultOpen title="Properties">
            <div
                class="my-6"
                v-for="(property, propertyIdx) in properties"
                :key="propertyIdx"
            >
                <div v-if="property.property.inputType === 'text'">
                    <TextInput
                        v-model="source.data[property.property.symbolic_name]"
                        :unit="property.unit.symbol"
                        :description="property.property.description"
                        :label="property.property.name"
                        :placeholder="property.property.name"
                        :required="property.required"
                    />
                    <template v-if="showUploader(property.property.name)">
                        <excel-uploader
                            :field-name="property.property.name"
                            @input="(value) =>{ source.data[property.property.symbolic_name] = value }"/>

                    </template>
                </div>
                <div v-else-if="property.property.inputType === 'select'">
                    <SelectMenu
                        v-model="source.data[property.property.symbolic_name]"
                        :options="property.property.data.options"
                        :description="property.property.description"
                        :disabled="selectedTemplate ? false : true"
                        :required="property.required"
                        :label="property.property.name"
                    />
                </div>
                <div v-for="(error, key) in errors" :key="key">
                    <div v-if="property.property.symbolic_name === key">
                        <div v-for="(e, eIdx) in error" :key="eIdx">
                            <JetInputError :message="e" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
        </PropertyDisclosure>
    </div>
    <div v-else>
        <div
            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
        >
            <div class="col-span-3 text-center">
                <p class="block font-bold text-2xl text-gray-200 p-4">
                    No properties found.
                </p>
            </div>
        </div>
    </div>

    <!-- Source Advanced Properties -->
    <div
        v-if="advancedProperties.length"
        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
        <PropertyDisclosure title="Advanced Properties">
            <div>
                <fieldset class="space-y-5">
                    <legend class="sr-only">Advanced Properties Enable</legend>
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <input
                                id="advancedProperties"
                                aria-describedby="advancedProperties-description"
                                name="advancedProperties"
                                type="checkbox"
                                class="focus:ring-indigo-500 h-4 w-4 text-blue-600 border-gray-300 rounded"
                                v-model="withAdvancedProperties"
                            />
                        </div>
                        <div class="ml-3 text-sm">
                            <label
                                for="advancedProperties"
                                class="font-medium text-gray-700"
                            >
                                Enable advanced properties
                            </label>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div
                class="my-6"
                v-for="(
                    advancedProperty, advancedPropertyIdx
                ) in advancedProperties"
                :key="advancedPropertyIdx"
            >
                <div v-if="advancedProperty.property.inputType === 'text'">
                    <TextInput
                        v-model="
                            source.data[advancedProperty.property.symbolic_name]
                        "
                        :unit="advancedProperty.unit.symbol"
                        :description="advancedProperty.property.description"
                        :label="advancedProperty.property.name"
                        :placeholder="advancedProperty.property.name"
                        :required="advancedProperty.required"
                        :disabled="!withAdvancedProperties"
                    />
                </div>
                <div
                    v-else-if="advancedProperty.property.inputType === 'select'"
                >
                    <SelectMenu
                        v-model="
                            source.data[advancedProperty.property.symbolic_name]
                        "
                        :options="advancedProperty.property.data.options"
                        :description="advancedProperty.property.description"
                        :required="advancedProperty.required"
                        :label="advancedProperty.property.name"
                        :disabled="!withAdvancedProperties"
                    />
                </div>
                <div v-for="(error, key) in errors" :key="key">
                    <div v-if="advancedProperty.property.symbolic_name === key">
                        <div v-for="(e, eIdx) in error" :key="eIdx">
                            <JetInputError :message="e" class="mt-2" />
                        </div>
                    </div>
                </div>
            </div>
        </PropertyDisclosure>
    </div>
    <div v-else>
        <div
            class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
        >
            <div class="col-span-3 text-center">
                <p class="block font-bold text-2xl text-gray-200 p-4">
                    No advanced properties found.
                </p>
            </div>
        </div>
    </div>

    <InfoTemplateModal
        v-model="infoTemplateModalIsVisible"
        :info="selectedTemplate.info"
    />
</template>

<script>
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";

import mapUtils from "@/Utils/map.js";
import JetCheckbox from "@/Jetstream/Checkbox";
import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import InfoIcon from "@/Components/Icons/InfoIcon.vue";
import InfoTemplateModal from "@/Components/Modals/InfoTemplateModal.vue";
import ExcelUploader from "../../../../Components/ExcelUploader";

import {
    sortProperties,
    validateProperies,
    DEFAULT_TEMPLATE,
} from "@/Utils/helpers";

export default {
    components: {
        JetCheckbox,
        PropertyDisclosure,
        SelectMenu,
        TextInput,
        JetInputError,
        InfoIcon,
        InfoTemplateModal,
        ExcelUploader
    },

    props: {
        templates: {
            type: Array,
            required: true,
        },
        locations: {
            type: Array,
            required: true,
        },
        nextStepRequest: {
            type: Boolean,
            required: true,
        },
    },

    emits: ["completed", "incompleted"],

    setup(props, ctx) {
        const store = useStore();
        const custom = ref(false);
        const storeSource = computed(() => store.getters["source/source"]);
        const storeTemplate = computed(() => store.getters["source/template"]);

        const selectedMarker = store.getters["map/selectedMarker"];

        const infoTemplateModalIsVisible = ref(false);

        const selectedLocation = ref({
            key: {lat: selectedMarker.lat, lng: selectedMarker.lng},
            value: "Selected Marker",
        });

        // We deep copy the store data, so we manipulate it freely and commit our changes back, when we are ready
        const source = ref(window._.cloneDeep(storeSource.value));

        const errors = ref({});

        const templates = computed(() =>
            props.templates.map((t) => ({
                key: t.id,
                value: t.name,
                info: t.values.help,
                properties: sortProperties(t.template_properties),
            }))
        );
        const selectedTemplate = ref(
            storeTemplate.value ?? templates.value[0] ?? DEFAULT_TEMPLATE
        );

        const locations = computed(() =>
            props.locations.map((l) => ({
                key: l.id,
                value: l.name,
            }))
        );

        const properties = computed(() => {
            if (!selectedTemplate.value.properties.length) return [];

            return sortProperties(
                window._.cloneDeep(
                    selectedTemplate.value.properties.filter((p) => !p.advanced)
                )
            );
        });

        const withAdvancedProperties = computed({
            get: () => store.getters["source/withAdvancedProperties"],
            set: (value) =>
                store.commit("source/setAdvancedPropertiesOption", {
                    withAdvancedProperties: value,
                }),
        });

        const advancedProperties = computed(() => {
            if (!selectedTemplate.value.properties.length) return [];

            return sortProperties(
                window._.cloneDeep(
                    selectedTemplate.value.properties.filter((p) => p.advanced)
                )
            );
        });

        watch(
            selectedTemplate,
            (selectedTemplate) => {
                withAdvancedProperties.value = false;

                if (!selectedTemplate.properties.length) source.value.data = {};

                for (const property of properties.value) {
                    const inputType = property.property.inputType;

                    if (property.property) {
                        const placeholder = inputType === "select" ? {} : "";

                        const key = property.property.symbolic_name;
                        if (selectedTemplate != storeTemplate.value) {
                            source.value.data[key] =
                            property.property.default_value ?? placeholder;
                            store.commit("source/setSelectedEquipment", {
                                selectedEquipment: window._.cloneDeep(ref([])),
                            });
                            store.commit("source/setSelectedProcesses", {
                                selectedProcesses: window._.cloneDeep(ref([])),
                            });
                        }
                    }
                }

                store.commit("source/setTemplate", {
                    template: selectedTemplate,
                });
            },
            { immediate: true, deep: true }
        );

        watch(
            withAdvancedProperties,
            (enabled) => {
                if (!enabled) {
                    for (const property in source.value.data) {
                        const found = advancedProperties.value.find(
                            (p) => p.property.symbolic_name === property
                        );

                        if (found) delete source.value.data[property];
                    }
                } else {
                    for (const property of advancedProperties.value) {
                        const inputType = property.property.inputType;

                        if (property.property) {
                            const placeholder =
                                inputType === "select" ? {} : "";

                            const key = property.property.symbolic_name;

                            source.value.data[key] =
                                property.property.default_value ?? placeholder;
                        }
                    }
                }
            },
            { deep: true }
        );

        const commitSource = window._.debounce(
            () =>
                store.commit("source/setSourceData", {
                    data: window._.cloneDeep(source.value.data),
                }),
            500
        );

        watch(source, () => commitSource(), {
            deep: true,
            immediate: true,
        });

        const userSelectedProperties = computed(() =>
            selectedTemplate.value.properties.filter((p) => {
                if (p.advanced && !withAdvancedProperties.value) return false;

                return true;
            })
        );

        watch(
            () => props.nextStepRequest,
            (nextStepRequest) => {
                if (!nextStepRequest) return;

                errors.value = validateProperies(
                    source.value,
                    userSelectedProperties.value,
                    selectedTemplate.value
                );

                if (selectedTemplate.value === DEFAULT_TEMPLATE) errors.value;

                for (const property in source.value.data) {
                    const found = userSelectedProperties.value.find(
                        (p) => p.property.symbolic_name === property
                    );

                    if (found) continue;

                    delete source.value.data[property];
                }

                store.commit("source/setLocation", {
                    location: selectedLocation.value,
                });

                if (!Object.keys(errors.value).length) ctx.emit("completed");
                else ctx.emit("incompleted");
            }
        );

        const selectedMarkerLatlng = computed(
            () => store.getters["map/selectedMarkerPosition"]
        );

        watch(selectedMarkerLatlng, (position) => {
            const newPosition = window._.cloneDeep(position)
            selectedLocation.value.key = newPosition.position;
        });

        const updateMarker = () => {
            if (selectedLocation.value.key.lat > 90) selectedLocation.value.key.lat = 90;
            else if (selectedLocation.value.key.lat < -90) selectedLocation.value.key.lat = -90;

            if (selectedLocation.value.key.lng > 180) selectedLocation.value.key.lng = 180;
            else if (selectedLocation.value.key.lng < -180) selectedLocation.value.key.lng = -180;

            mapUtils.setPoint(selectedLocation.value.key, 'instance')
        };

        const showUploader = (field) => {
            let fields = [
                'Real Hourly Capacity',
                'Real Daily Capacity',
                'Real Monthly Capacity',
                // 'Real Yearly Capacity',
            ]

            return fields.includes(field)
        }

        return {
            selectedMarker,
            userSelectedProperties,
            source,
            templates,
            selectedTemplate,
            locations,
            selectedLocation,
            properties,
            advancedProperties,
            withAdvancedProperties,
            errors,
            custom,
            infoTemplateModalIsVisible,
            updateMarker,
            showUploader
        };
    },
};
</script>
