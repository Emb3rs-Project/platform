<template>
  <SiteHead title="Create a Sink" />

  <SlideOver
    title="New Sink"
    subtitle="Get started by filling in the information below to create your new Sink. This Sink will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Sink Information -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5">
      <Disclosure
        as="div"
        v-slot="{ open }"
        defaultOpen
      >
        <dt class="text-lg">
          <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
            <span class="font-bold text-gray-900">
              Information
            </span>
            <span class="ml-6 h-7 flex items-center">
              <ChevronDownIcon
                :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform']"
                aria-hidden="true"
              />
            </span>
          </DisclosureButton>
        </dt>
        <transition
          enter-active-class="transition duration-100 ease-out"
          enter-from-class="transform scale-95 opacity-0"
          enter-to-class="transform scale-100 opacity-100"
          leave-active-class="transition duration-75 ease-out"
          leave-from-class="transform scale-100 opacity-100"
          leave-to-class="transform scale-95 opacity-0"
        >
          <DisclosurePanel as="dd">
            <div class="my-6">
              <SelectMenu
                v-model="selectedTemplate"
                :options="templates"
                label="Template"
                description="THIS IS A VERY GOOD DESCRIPTION IF I MAY SAY"
              />
            </div>
            <div class="my-6">
              <SelectMenu
                v-model="selectedLocation"
                :options="locations"
                label="Location"
                :disabled="selectedTemplate ? false : true"
                description="THIS IS A VERY GOOD DESCRIPTION IF I MAY SAY"
              />
            </div>
          </DisclosurePanel>
        </transition>
      </Disclosure>
    </div>

    <!-- Sink Properties -->
    <div
      v-if="properties.length"
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
      <Disclosure
        as="div"
        v-slot="{ open }"
        defaultOpen
      >
        <dt class="text-lg">
          <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
            <span class="font-bold text-gray-900">
              Properties
            </span>
            <span class="ml-6 h-7 flex items-center">
              <ChevronDownIcon
                :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform']"
                aria-hidden="true"
              />
            </span>
          </DisclosureButton>
        </dt>
        <transition
          enter-active-class="transition duration-100 ease-out"
          enter-from-class="transform scale-95 opacity-0"
          enter-to-class="transform scale-100 opacity-100"
          leave-active-class="transition duration-75 ease-out"
          leave-from-class="transform scale-100 opacity-100"
          leave-to-class="transform scale-95 opacity-0"
        >
          <DisclosurePanel as="dd">
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
              <div v-if="form.hasErrors">
                <div
                  v-for="(error, key) in form.errors"
                  :key="key"
                >
                  <jet-input-error
                    v-show="key.includes(property.property.symbolic_name)"
                    :message="error"
                    class="mt-2"
                  />
                </div>
              </div>
            </div>
          </DisclosurePanel>
        </transition>
      </Disclosure>
    </div>

    <!-- Sink Advanced Properties -->
    <div
      v-if="advancedProperties.length"
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
      <Disclosure
        as="div"
        v-slot="{ open }"
      >
        <dt class="text-lg">
          <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
            <span class="font-bold text-gray-900">
              Advanced Properties
            </span>
            <span class="ml-6 h-7 flex items-center">
              <ChevronDownIcon
                :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform']"
                aria-hidden="true"
              />
            </span>
          </DisclosureButton>
        </dt>
        <transition
          enter-active-class="transition duration-100 ease-out"
          enter-from-class="transform scale-95 opacity-0"
          enter-to-class="transform scale-100 opacity-100"
          leave-active-class="transition duration-75 ease-out"
          leave-from-class="transform scale-100 opacity-100"
          leave-to-class="transform scale-95 opacity-0"
        >
          <DisclosurePanel as="dd">
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
                <div
                  v-for="(error, key) in form.errors"
                  :key="key"
                >
                  <jet-input-error
                    v-show="key.includes(advancedProperty.property.symbolic_name)"
                    :message="error"
                    class="mt-2"
                  />
                </div>
              </div>
            </div>
          </DisclosurePanel>
        </transition>
      </Disclosure>
    </div>

    <template #actions>
      <SecondaryOutlinedButton
        type="button"
        :disabled="form.processing"
        @click="onCancel"
      >
        Cancel
      </SecondaryOutlinedButton>
      <PrimaryButton
        @click="submit"
        :disabled="form.processing"
      >
        Save
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { ref, watch, computed } from "vue";
import { useStore } from "vuex";
import { useForm } from "@inertiajs/inertia-vue3";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

import { sortProperties } from "@/Utils/helpers";

export default {
  components: {
    AppLayout,
    SiteHead,
    SlideOver,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
    SelectMenu,
    TextInput,
    JetInputError,
    PrimaryButton,
    SecondaryOutlinedButton,
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

  setup(props) {
    const store = useStore();

    const form = useForm({
      sink: {
        data: {},
      },
      template_id: null,
      location_id: null,
      location: null,
    });

    const templateInfo = ref(null);
    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
        properties: t.template_properties,
      }))
    );
    const selectedTemplate = ref(templates.value[0] ?? {});

    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
      }))
    );
    const selectedLocation = ref(null);
    const withAdvancedProperties = ref(false);
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
      { immediate: true }
    );
    watch(
      selectedLocation,
      (location) => {
        form.location_id = null;
        form.location = null;

        selectedLocation.value = locations.value.find(
          (l) => l.key === location.key
        );

        if (typeof selectedLocation.value.key === "object") {
          return (form.location = {
            lat: location.key.lat,
            lng: location.key.lng,
          });
        }

        return (form.location_id = location.key);
      },
      { immediate: true, deep: true }
    );

    watch(
      selectedTemplate,
      (template) => {
        withAdvancedProperties.value = false;
        form.sink.data = {};

        templateInfo.value = templates.value.find(
          (t) => t.key === template.key
        );
        form.template_id = template.key;

        if (templateInfo.value.properties.length) {
          for (const property of templateInfo.value.properties) {
            const prop = property.property;

            if (prop) {
              const placeholder = prop.inputType === "select" ? {} : "";

              form.sink.data[prop.symbolic_name] = property.default_value
                ? property.default_value
                : placeholder;
            }
          }
        }
      },
      { immediate: true }
    );

    const properties = computed(() =>
      sortProperties(
        window._.cloneDeep(
          templateInfo.value.properties.filter((p) => !p.advanced)
        )
      )
    );

    const advancedProperties = computed(() =>
      sortProperties(
        window._.cloneDeep(
          templateInfo.value.properties.filter((p) => p.advanced)
        )
      )
    );

    const submit = () => {
      form
        .transform((data) => {
          // We want to transform the "to-send" data, not the original data
          const deepCopyOfData = window._.cloneDeep(data);

          const sinkData = deepCopyOfData.sink.data;

          if (templateInfo.value.properties.length) {
            for (const property of templateInfo.value.properties) {
              const prop = property.property;
              const key = prop.symbolic_name;

              if (property.advanced && !withAdvancedProperties.value) {
                delete sinkData[key];
                continue;
              }

              if (prop.inputType === "select") {
                // if the property has a value, get it and re-assign the property as a string
                if (Object.keys(sinkData[key]).length) {
                  sinkData[key] = sinkData[key].value;
                } else {
                  if (prop.dataType === "text" || prop.dataType === "string") {
                    sinkData[key] = "";
                  } else {
                    sinkData[key] = null;
                  }
                }
              }
            }
          }

          return deepCopyOfData;
        })
        .post(route("objects.sinks.store"), {
          onSuccess: () => {
            store.dispatch("map/refreshMap");
            store.dispatch("objects/showSlide", { route: "objects.list" });
          },
        });
    };

    const onCancel = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      form,
      templateInfo,
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      withAdvancedProperties,
      properties,
      advancedProperties,
      submit,
      onCancel,
    };
  },
};
</script>
