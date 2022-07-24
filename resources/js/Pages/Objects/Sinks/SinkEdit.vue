<template>
  <SiteHead title="Edit a Sink" />
  <SlideOver
    type="sink"
    title="Edit Sink"
    subtitle="Below, you can edit the details that are associated to the currently selected Sink."
  >
    <!-- Alert -->
    <template #stickyTop>
      <div :class="{ 'p-4': form.hasErrors }">
        <Alert
          v-model="form.hasErrors"
          type="danger"
          message="Please, correct all the errors before saving."
          :dismissable="false"
        />
      </div>
    </template>

    <!-- Sink Information -->
    <div
      class="
        space-y-1
        px-4
        sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
      "
    >
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
                  <InfoIcon class="font-medium text-sm w-5" />
              </button>
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
                  Custom Marker
                  </label>
              </div>
          </div>
      </PropertyDisclosure>
    </div>

    <!-- Sink Properties -->
    <div
      v-if="properties.length"
      class="
        space-y-1
        px-4
        sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
      "
    >
      <PropertyDisclosure defaultOpen title="Properties">
        <div
          class="my-6"
          v-for="(property, propertyIdx) in properties"
          :key="propertyIdx"
        >
          <div v-if="property.property.inputType === 'text'">
            <TextInput
              v-model="form.sink.data[property.property.symbolic_name]"
              :unit="property.unit.symbol"
              :description="property.property.description"
              :label="property.property.name"
              :placeholder="property.property.name"
              :required="property.required"
            />
          </div>
          <div v-else-if="property.property.inputType === 'select'">
            <SelectMenu
              v-model="form.sink.data[property.property.symbolic_name]"
              :options="property.property.data.options"
              :description="property.property.description"
              :disabled="selectedTemplate ? false : true"
              :required="property.required"
              :label="property.property.name"
            />
          </div>
          <div v-for="(error, key) in form.errors" :key="key">
            <jet-input-error
              v-show="key.includes(property.property.symbolic_name)"
              :message="error"
              class="mt-2"
            />
          </div>
        </div>
      </PropertyDisclosure>
    </div>

    <!-- Sink Advanced Properties -->
    <div
      v-if="advancedProperties.length"
      class="
        space-y-1
        px-4
        sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
      "
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
                  class="
                    focus:ring-indigo-500
                    h-4
                    w-4
                    text-blue-600
                    border-gray-300
                    rounded
                  "
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
          v-for="(advancedProperty, advancedPropertyIdx) in advancedProperties"
          :key="advancedPropertyIdx"
        >
          <div v-if="advancedProperty.property.inputType === 'text'">
            <TextInput
              v-model="form.sink.data[advancedProperty.property.symbolic_name]"
              :unit="advancedProperty.unit.symbol"
              :description="advancedProperty.property.description"
              :label="advancedProperty.property.name"
              :placeholder="advancedProperty.property.name"
              :required="advancedProperty.required"
              :disabled="!withAdvancedProperties"
            />
          </div>
          <div v-else-if="advancedProperty.property.inputType === 'select'">
            <SelectMenu
              v-model="form.sink.data[advancedProperty.property.symbolic_name]"
              :options="advancedProperty.property.data.options"
              :description="advancedProperty.property.description"
              :required="advancedProperty.required"
              :label="advancedProperty.property.name"
              :disabled="!withAdvancedProperties"
            />
          </div>
          <div v-if="form.hasErrors">
            <div v-for="(error, key) in form.errors" :key="key">
              <jet-input-error
                v-show="key.includes(advancedProperty.property.symbolic_name)"
                :message="error"
                class="mt-2"
              />
            </div>
          </div>
        </div>
      </PropertyDisclosure>
    </div>

    <template #actions>
      <SecondaryOutlinedButton
        type="button"
        :disabled="form.processing"
        @click="onCancel"
      >
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
</template>

<script>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import { useStore } from "vuex";

import mapUtils from "@/Utils/map.js";
import JetCheckbox from "@/Jetstream/Checkbox";
import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/SlideOvers/SlideOver.vue";
import Alert from "@/Components/Alerts/Alert.vue";
import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "../../../Jetstream/InputError";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import InfoIcon from "@/Components/Icons/InfoIcon.vue";
import InfoTemplateModal from "@/Components/Modals/InfoTemplateModal.vue";

import { sortProperties, validateProperies } from "@/Utils/helpers";

export default {
  components: {
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
    instance: {
      type: Object,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();

    // TODO: make this enabled by defualt if at least one adv prop is already in place
    const withAdvancedProperties = ref(false);
    const infoTemplateModalIsVisible = ref(false);

    const form = useForm({
      sink: {
        data: {},
      },
      custom: false,
      template_id: null,
      location_id: null,
      location: null,
    });

    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
        info: t.values.help,
        properties: t.template_properties,
      }))
    );
    const selectedTemplate = ref(
      templates.value.find((t) => t.key === props.instance.template.id)
    );

    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
        location: l.data.center
      }))
    );
    const selectedLocation = ref(
      locations.value.find((l) => l.key === props.instance.location.id)
    );

    watch(
      selectedLocation,
      (selectedLocation) => {
        form.location = {lat: selectedLocation.location[0], lng: selectedLocation.location[1]}
        form.location_id = selectedLocation.key;
      },
      { immediate: true, deep: true }
    );
    watch(
      selectedTemplate,
      (template) => {
        withAdvancedProperties.value = false;

        form.template_id = template.key;
        form.sink.data.name = props.instance.name;

        for (const property of template.properties) {
          const prop = property.property;

          const value = props.instance.values[prop.symbolic_name];

          if (value) {
            if (prop.inputType === "select") {
              form.sink.data[prop.symbolic_name] = prop.data.options.find(
                (o) => o.key === value
              );
            } else {
              form.sink.data[prop.symbolic_name] = value;
            }
          } else {
            const placeholder = prop.inputType === "select" ? {} : "";

            form.sink.data[prop.symbolic_name] = property.default_value
              ? property.default_value
              : placeholder;
          }
        }
      },
      { immediate: true, deep: true }
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
        if (p.advanced && !withAdvancedProperties.value) return false;

        return true;
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
          const deepCopyOfData = JSON.parse(JSON.stringify(data));

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
              if (Object.keys(sinkData[key]).length)
                sinkData[key] = sinkData[key].key;
            }
          }

          return deepCopyOfData;
        })
        .patch(route("objects.sinks.update", props.instance.id), {
          onSuccess: () => {
            store.dispatch("map/refreshMap");
            store.commit("objects/setNotify", {
                title: 'Sink',
                text: 'Sink Updated Successfully',
                type: 'success'
            });
            store.dispatch("objects/showSlide", { route: "objects.list" });
          },
        });
    };

    const updateMarker = () => {
        if (form.location.lat > 90) form.location.lat = 90;
        else if (form.location.lat < -90) form.location.lat = -90;

        if (form.location.lng > 180) form.location.lng = 180;
        else if (form.location.lng < -180) form.location.lng = -180;

        mapUtils.setPoint(form.location, props.instance.id)
    };

    const onCancel = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

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
      updateMarker,
      submit,
      onCancel,
    };
  },
};
</script>
