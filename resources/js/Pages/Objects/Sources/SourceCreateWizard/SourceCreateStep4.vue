<template>
    <!-- Source Information -->
    <div
        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
        <PrimaryButton
            type="button"
            @click="() => additionalStreams.push({data:{}})"
        >
            <PlusIcon
                class="h-6 w-6 mr-2"
                aria-hidden="true"
            />
            New Stream
        </PrimaryButton>
    </div>

    <!-- Stream Properties-->
    <div
        v-if="properties.length"
        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
        <PropertyDisclosure canDelete :title="`Stream ${streamIndex+1}`"
                            v-for="(stream, streamIndex) in additionalStreams"
                            @onDelete="onRemoveStream(index)">
            <div
                class="my-6"
                v-for="(property, propertyIdx) in properties"
                :key="propertyIdx"
            >
                <div v-if="property.property.inputType === 'text'">
                    <TextInput
                        v-model="stream.data[property.property.symbolic_name]"
                        :unit="property.unit.symbol"
                        :description="property.property.description"
                        :label="property.property.name"
                        :placeholder="property.property.name"
                        :required="property.required"
                    />
                </div>
                <div v-else-if="property.property.inputType === 'select'">
                    <SelectMenu
                        v-model="stream.data[property.property.symbolic_name]"
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


</template>

<script>
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";

import JetCheckbox from "@/Jetstream/Checkbox";
import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import InfoIcon from "@/Components/Icons/InfoIcon.vue";
import InfoTemplateModal from "@/Components/Modals/InfoTemplateModal.vue";
import { PlusIcon } from "@heroicons/vue/solid";

import {
    sortProperties,
    validateProperies,
    DEFAULT_TEMPLATE,
} from "@/Utils/helpers";
import PrimaryButton from "../../../../Components/PrimaryButton";

export default {
    components: {
        PrimaryButton,
        JetCheckbox,
        PropertyDisclosure,
        SelectMenu,
        TextInput,
        JetInputError,
        InfoIcon,
        InfoTemplateModal,
        PlusIcon,
    },

    props: {
        templates: {
            type: Array,
            required: true,
        },
        streams: {
            type: Array,
            required: false,
        },
        nextStepRequest: {
            type: Boolean,
            required: true,
        },
    },

    emits: ["completed", "incompleted"],

    setup(props, ctx) {
        const store = useStore();

        const storeTemplate = computed(() => store.getters["source/template"]);

        const storeAdditionalStreams = computed(() => store.getters["source/additionalStreams"]);

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

        const additionalStreams = ref(window._.cloneDeep(storeAdditionalStreams.value));

        const properties = computed(() => {
            if (!selectedTemplate.value.properties.length) return [];

            return sortProperties(
                window._.cloneDeep(
                    selectedTemplate.value.properties.filter((p) => !p.advanced)
                )
            );
        });

        const commitAdditionalStreams = window._.debounce(
            () =>
                store.commit("source/setAdditionalStreams", {

                    additionalStreams: window._.cloneDeep(additionalStreams),
                }),
            500
        );

        watch(additionalStreams, () => {
            commitAdditionalStreams()
            console.log(storeAdditionalStreams.value)
        }, {
            deep: true,
            immediate: true,
        });



        const onRemoveStream = (index) => additionalStreams.value.splice(index, 1);

        watch(
            () => props.nextStepRequest,
            (nextStepRequest) => {
                if (!nextStepRequest) return;

                if (selectedTemplate.value === DEFAULT_TEMPLATE) errors.value;

                if (!Object.keys(errors.value).length) ctx.emit("completed");
                else ctx.emit("incompleted");
            }
        );




        return {
            templates,
            selectedTemplate,
            storeAdditionalStreams,
            properties,
            errors,
            additionalStreams,
            onRemoveStream
        };
    },
};
</script>
