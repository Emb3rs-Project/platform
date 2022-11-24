<template>
    <SiteHead title="Create a Sink"/>
    <SlideOver type="sink" title="New Sink"
               subtitle="Get started by filling in the information below to create your new Sink. This Sink will be attached to your currently selected Institution.">
        <!-- Alert -->
        <template #stickyTop>
            <div :class="{ 'p-4': form.hasErrors }">
                <Alert v-model="form.hasErrors" type="danger" message="Please, correct all the errors before saving."
                       :dismissable="false"/>
            </div>
        </template>

        <!-- Sink Information -->
        <div class="
        space-y-1
        px-4
        sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
      ">
            <PropertyDisclosure defaultOpen title="Information">
                <div class="my-4 flex">
                    <div class="w-full">
                        <SelectMenu :class="{'w-5/6': selectedTemplate.info}"
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
                            <InfoIcon class="font-medium text-sm w-5"/>
                        </button>
                    </div>
                    <div v-for="(error, key) in form.errors" :key="key">
                        <jet-input-error v-show="key.includes('template')" :message="error" class="mt-2"/>
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
                                v-model="form.location.lat"
                                @update:modelValue="updateMarker()"
                                :disabled="!form.custom"
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
                                v-model="form.location.lng"
                                @update:modelValue="updateMarker()"
                                :disabled="!form.custom"
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
                            v-model:checked="form.custom"
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

        <!-- Sink Properties -->
        <div v-if="properties.length" class="
        space-y-1
        px-4
        sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
      ">
            <PropertyDisclosure defaultOpen title="Properties">
                <div class="my-6" v-for="(property, propertyIdx) in properties" :key="propertyIdx">
                    <div v-if="property.property.inputType === 'text'">
                        <TextInput v-model="form.sink.data[property.property.symbolic_name]"
                                   :unit="property.unit.symbol" :description="property.property.description"
                                   :label="property.property.name" :placeholder="property.property.name"
                                   :required="property.required"/>
                        <excel-uploader @input="(value) =>{ form.sink.data[property.property.symbolic_name] = value }"
                                        v-if="showUploader(property.property.name)"></excel-uploader>
                    </div>
                    <div v-else-if="property.property.inputType === 'select'">
                        <SelectMenu v-model="form.sink.data[property.property.symbolic_name]"
                                    :options="property.property.data.options"
                                    :description="property.property.description"
                                    :disabled="selectedTemplate ? false : true" :required="property.required"
                                    :label="property.property.name"/>
                    </div>
                    <div v-for="(error, key) in form.errors" :key="key">
                        <jet-input-error v-show="key.includes(property.property.symbolic_name)" :message="error"
                                         class="mt-2"/>
                    </div>
                </div>

                <!--                <div class="my-6">-->
                <!--                    <div class="my-6">-->
                <!--                        <FileInput-->
                <!--                            v-model="form.sink.data['real_daily_capacity']"-->
                <!--                            label="File test"-->
                <!--                        />-->
                <!--                    </div>-->

                <!--                </div>-->
            </PropertyDisclosure>
        </div>

        <!-- Sink Advanced Properties -->
        <div v-if="advancedProperties.length" class="
        space-y-1
        px-4
        sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5">
            <PropertyDisclosure title="Advanced Properties">
                <div>
                    <fieldset class="space-y-5">
                        <legend class="sr-only">Advanced Properties Enable</legend>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="advancedProperties" aria-describedby="advancedProperties-description"
                                       name="advancedProperties" type="checkbox" class="rounded
                                    focus:ring-indigo-500 h-4 w-4 text-blue-600 border-gray-300"
                                       v-model="withAdvancedProperties"
                                />
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="advancedProperties" class="font-medium text-gray-700">
                                    Enable advanced properties
                                </label>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="my-6" v-for="(advancedProperty, advancedPropertyIdx) in advancedProperties"
                     :key="advancedPropertyIdx">
                    <div v-if="advancedProperty.property.inputType === 'text'">
                        <TextInput v-model="form.sink.data[advancedProperty.property.symbolic_name]"
                                   :unit="advancedProperty.unit.symbol"
                                   :description="advancedProperty.property.description"
                                   :label="advancedProperty.property.name" :placeholder="advancedProperty.property.name"
                                   :required="advancedProperty.required" :disabled="!withAdvancedProperties"/>
                    </div>
                    <div v-else-if="advancedProperty.property.inputType === 'select'">
                        <SelectMenu v-model="form.sink.data[advancedProperty.property.symbolic_name]"
                                    :options="advancedProperty.property.data.options"
                                    :description="advancedProperty.property.description"
                                    :required="advancedProperty.required"
                                    :label="advancedProperty.property.name" :disabled="!withAdvancedProperties"/>
                    </div>
                    <div v-if="form.hasErrors">
                        <div v-for="(error, key) in form.errors" :key="key">
                            <jet-input-error v-show="key.includes(advancedProperty.property.symbolic_name)"
                                             :message="error" class="mt-2"/>
                        </div>
                    </div>
                </div>
            </PropertyDisclosure>
        </div>

        <template #actions>
            <SecondaryOutlinedButton type="button" :disabled="form.processing" @click="onCancel">
                Cancel
            </SecondaryOutlinedButton>
            <PrimaryButton @click="submit" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </SlideOver>
    <InfoTemplateModal
        v-model="infoTemplateModalIsVisible"
        :info="selectedTemplate.info"
    />
    <ErrorTemplateModal
        v-model="errorTemplateModalIsVisible"
        :error="errorTemplateModal"
    />
</template>

<script>
import {ref, watch, computed} from "vue";
import {useStore} from "vuex";
import {useForm} from "@inertiajs/inertia-vue3";

import mapUtils from "@/Utils/map.js";
import JetCheckbox from "@/Jetstream/Checkbox";
import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOvers/SlideOver.vue";
import Alert from "@/Components/Alerts/Alert.vue";
import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import InfoIcon from "@/Components/Icons/InfoIcon.vue";
import InfoTemplateModal from "@/Components/Modals/InfoTemplateModal.vue";
import ErrorTemplateModal from "@/Components/Modals/ErrorTemplateModal.vue";

import {
    sortProperties,
    validateProperies,
    DEFAULT_TEMPLATE,
} from "@/Utils/helpers";
import FileInput from "../../../Components/Forms/FileInput";
import ExcelUploader from "../../../Components/ExcelUploader";

export default {
    components: {
        ExcelUploader,
        FileInput,
        JetCheckbox,
        AppLayout,
        SiteHead,
        SlideOver,
        Alert,
        PropertyDisclosure,
        SelectMenu,
        TextInput,
        JetInputError,
        PrimaryButton,
        SecondaryOutlinedButton,
        InfoIcon,
        InfoTemplateModal,
        ErrorTemplateModal,
    },

    props: {
        templates: {
            type: Array,
            default: [],
        },
        locations: {
            type: Array,
            default: [],
        },
    },

    setup (props) {
        const store = useStore();

        const withAdvancedProperties = ref(false);
        const infoTemplateModalIsVisible = ref(false);
        const errorTemplateModalIsVisible = ref(false);

        const errorTemplateModal = ref([]);

        const showUploader = (field) => {
            let fields = [
                'Real Hourly Capacity',
                'Real Daily Capacity',
                'Real Monthly Capacity',
                'Real Yearly Capacity',
            ]

            return fields.includes(field)
        }

        const form = useForm({
            sink: {
                data: {},
            },
            custom: false,
            template_id: null,
            location_id: null,
            location: {},
        });

        const templates = computed(() =>
            props.templates.map((t) => ({
                key: t.id,
                value: t.name,
                info: t.values.help,
                properties: t.template_properties,
            }))
        );
        const selectedTemplate = ref(templates.value[0] ?? DEFAULT_TEMPLATE);

        const locations = computed(() =>
            props.locations.map((l) => ({
                key: l.id,
                value: l.name,
            }))
        );
        const selectedLocation = ref(null);
        watch(
            () => store.getters["map/selectedMarker"],
            (val) => {
                if (!!val) {
                    const oldLocation = locations.value.find(
                        (l) => l.value === "Selected Marker"
                    );
                    if (oldLocation) {
                        oldLocation.key = val;
                    } else {
                        locations.value.unshift({
                            key: val,
                            value: "Selected Marker",
                        });
                    }
                } else {
                    const oldLocationIndex = locations.value.findIndex(
                        (l) => l.value === "Selected Marker"
                    );
                    if (oldLocationIndex !== -1) {
                        locations.value.splice(oldLocationIndex, 1);
                    }
                }
                selectedLocation.value = locations.value[0];
            },
            {immediate: true}
        );
        watch(
            selectedLocation,
            (location) => {
                form.location_id = null;

                selectedLocation.value = locations.value.find(
                    (l) => l.key === location.key
                );
                if (typeof selectedLocation.value.key === "object" && form.location_id == null) {
                    form.location = {
                        lat: location.key.lat,
                        lng: location.key.lng,
                    };

                    return;
                }

                form.location_id = location.key;
            },
            {immediate: true, deep: true}
        );

        const selectedMarkerLatlng = computed(
            () => store.getters["map/selectedMarkerPosition"]
        );

        watch(selectedMarkerLatlng, (position) => {
            const newPosition = window._.cloneDeep(position)
            form.location = newPosition.position;
        });

        watch(
            selectedTemplate,
            (template) => {
                withAdvancedProperties.value = false;
                form.sink.data = {};

                form.template_id = template.key;

                for (const property of template.properties) {
                    const prop = property.property;

                    if (prop) {
                        const placeholder = prop.inputType === "select" ? {} : "";

                        form.sink.data[prop.symbolic_name] = property.default_value
                            ? property.default_value
                            : placeholder;
                    }
                }
            },
            {immediate: true}
        );

        const properties = computed(() =>
            sortProperties(
                window._.cloneDeep(
                    selectedTemplate.value.properties.filter((p) => !p.advanced)
                )
            )
        );

        const advancedProperties = computed(() =>
            sortProperties(
                window._.cloneDeep(
                    selectedTemplate.value.properties.filter((p) => p.advanced)
                )
            )
        );

        const userSelectedProperties = computed(() =>
            selectedTemplate.value.properties.filter((p) => {
                return !(p.advanced && !withAdvancedProperties.value);
            })
        );

        const submit = () => {
            form.clearErrors();

            const errors = validateProperies(
                form.sink,
                userSelectedProperties.value,
                selectedTemplate.value
            );

            if (Object.keys(errors).length) {
                for (const errorGroup in errors) {
                    for (const error of errors[errorGroup]) {
                        form.setError(errorGroup, error);
                    }
                }

                return;
            }

            form
                .transform((data) => {
                    // We want to transform the "to-send" data, not the original data
                    const deepCopyOfData = window._.cloneDeep(data);

                    const sinkData = deepCopyOfData.sink.data;

                    for (const property of selectedTemplate.value.properties) {
                        const prop = property.property;
                        const key = prop.symbolic_name;

                        if (property.advanced && !withAdvancedProperties.value) {
                            delete sinkData[key];
                            continue;
                        }

                        if (prop.inputType === "select") {
                            // if the property has a value, get it and re-assign the property as a string
                            if (Object.keys(sinkData[key]).length) {
                                sinkData[key] = sinkData[key].key;

                                continue;
                            }

                            if (prop.dataType === "string") sinkData[key] = "";
                            else sinkData[key] = null;
                        }
                    }

                    for (const key of Object.keys(sinkData)) {
                        if (sinkData[key] === null || sinkData[key] === "")
                            delete sinkData[key]
                    }

                    return deepCopyOfData;
                })
                .post(route("objects.sinks.store"), {
                    onSuccess: () => {
                        store.dispatch("map/refreshMap");
                        store.commit("objects/setNotify", {
                            title: 'Sink',
                            text: 'Sink Created Successfully',
                            type: 'success'
                        });
                        store.dispatch("map/removeMarker", true);
                        store.dispatch("objects/showSlide", {route: "objects.list"});
                    },
                    onError: (error) => {
                        errorTemplateModal.value = {...error};
                        errorTemplateModalIsVisible.value = true;
                    },
                });
        };

        const updateMarker = () => {
            if (form.location.lat > 90) form.location.lat = 90;
            else if (form.location.lat < -90) form.location.lat = -90;

            if (form.location.lng > 180) form.location.lng = 180;
            else if (form.location.lng < -180) form.location.lng = -180;

            mapUtils.setPoint(form.location, 'instance')
        };

        const onCancel = () => {
            store.dispatch("map/removeMarker", true);
            store.dispatch("map/refreshMap");
            //store.dispatch("objects/showSlide", { route: "objects.list" });
        };

        return {
            form,
            templates,
            selectedTemplate,
            locations,
            selectedLocation,
            withAdvancedProperties,
            properties,
            advancedProperties,
            infoTemplateModalIsVisible,
            errorTemplateModalIsVisible,
            errorTemplateModal,
            updateMarker,
            submit,
            onCancel,
            showUploader
        };
    },
};
</script>
