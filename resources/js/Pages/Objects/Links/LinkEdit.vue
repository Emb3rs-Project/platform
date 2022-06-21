<template>
  <SlideOver
    title="Edit Link"
    subtitle="Get started by editing a marker to edit your segments."
    headerBackground="bg-blue-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 items-center">
      <div class="flex flex-col place-content-center">
        <label
          for="name"
          class="block text-sm font-medium text-gray-900"
        >
          Name
        </label>
      </div>
      <div class="flex flex-col place-content-center sm:col-span-2">
        <TextInput
          v-model="form.name"
          placeholder="Link's Name"
          required
        />
      </div>
    </div>

    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 items-center">
      <div class="flex flex-col place-content-center">
        <label
          for="description"
          class="block text-sm font-medium text-gray-900"
        >
          Description
        </label>
      </div>
      <div class="flex flex-col place-content-center sm:col-span-2">
        <TextInput
          v-model="form.description"
          placeholder="Link's Description"
        />
      </div>
    </div>

    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <h1>Segments</h1>
    </div>

    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-for="(link, index) in form.segments"
      :key="link"
    >
      <div class="sm:col-span-3">
        <Disclosure
          as="div"
          v-slot="{ open }"
        >
          <dt class="text-lg">
            <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
              <span class="font-medium text-gray-900">
                Segment #{{ index + 1 }}</span>
              <span class="ml-6 h-7 flex items-center">
                <ChevronDownIcon
                  :class="[
                    open ? '-rotate-180' : 'rotate-0',
                    'h-6 w-6 transform',
                  ]"
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
            <DisclosurePanel
              as="dd"
              class="mt-2 pr-12"
            >

              <!-- Link Information -->
              <div class="
                space-y-1
                px-4
                sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
              ">
                  <PropertyDisclosure defaultOpen title="Information">
                      <div class="py-1 sm:grid sm:grid-cols-2">
                        <div>
                          <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                            From
                          </label>
                        </div>
                        <div class="sm:col-span-2">
                          <div> {{ link.data.from }} </div>
                        </div>
                      </div>
                      <div class="py-1 sm:grid sm:grid-cols-2">
                        <div>
                          <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                            To
                          </label>
                        </div>
                        <div class="sm:col-span-2">
                          <div> {{ link.data.to }} </div>
                        </div>
                      </div>
                      <div class="py-1 sm:grid sm:grid-cols-2">
                        <div>
                          <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                            Distance
                          </label>
                        </div>
                        <div class="sm:col-span-2">
                          <div> {{ link.data.distance }} m </div>
                        </div>
                      </div>
                      <div class="my-4">
                          <SelectMenu v-model="selectedTemplate[index]" :options="templates" label="Template" 
                            @update:modelValue="updateTemplateKey(index)" />
                          <div v-for="(error, key) in form.errors" :key="key">
                              <jet-input-error v-show="key.includes('template')" :message="error" class="mt-2" />
                          </div>
                      </div>
                  </PropertyDisclosure>
              </div>

              <!-- Link Properties -->
              <div v-if="properties[index].length" class="
                space-y-1
                px-4
                sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
              ">
                  <PropertyDisclosure defaultOpen title="Properties">
                      <div class="my-6" v-for="(property, propertyIdx) in properties[index]" :key="propertyIdx">
                          <div v-if="property.property.inputType === 'text'">
                              <TextInput v-model="link.data.data[property.property.symbolic_name]"
                                  :unit="property.unit.symbol" :description="property.property.description"
                                  :label="property.property.name" :placeholder="property.property.name"
                                  :required="property.required" />
                          </div>
                          <div v-else-if="property.property.inputType === 'select'">
                              <SelectMenu v-model="link.data.data[property.property.symbolic_name]"
                                  :options="property.property.data.options" :description="property.property.description"
                                  :disabled="selectedTemplate ? false : true" :required="property.required"
                                  :label="property.property.name" />
                          </div>
                          <div v-for="(error, key) in form.errors" :key="key">
                              <jet-input-error v-show="key.includes(property.property.symbolic_name+index)" :message="error"
                                  class="mt-2" />
                          </div>
                      </div>
                  </PropertyDisclosure>
              </div>

              <!-- Link Advanced Properties -->
              <div v-if="advancedProperties[index].length" class="
                space-y-1
                px-4
                sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5
              ">
                  <PropertyDisclosure title="Advanced Properties">
                      <div>
                          <fieldset class="space-y-5">
                              <legend class="sr-only">Advanced Properties Enable</legend>
                              <div class="relative flex items-start">
                                  <div class="flex items-center h-5">
                                      <input id="advancedProperties" aria-describedby="advancedProperties-description"
                                          name="advancedProperties" type="checkbox" class="
                                          focus:ring-indigo-500
                                          h-4
                                          w-4
                                          text-blue-600
                                          border-gray-300
                                          rounded
                                        " v-model="withAdvancedProperties[index]" />
                                  </div>
                                  <div class="ml-3 text-sm">
                                      <label for="advancedProperties" class="font-medium text-gray-700">
                                          Enable advanced properties
                                      </label>
                                  </div>
                              </div>
                          </fieldset>
                      </div>
                      <div class="my-6" v-for="(advancedProperty, advancedPropertyIdx) in advancedProperties[index]"
                          :key="advancedPropertyIdx">
                          <div v-if="advancedProperty.property.inputType === 'text'">
                              <TextInput v-model="link.data.data[advancedProperty.property.symbolic_name]"
                                  :unit="advancedProperty.unit.symbol" :description="advancedProperty.property.description"
                                  :label="advancedProperty.property.name" :placeholder="advancedProperty.property.name"
                                  :required="advancedProperty.required" :disabled="!withAdvancedProperties[index]" />
                          </div>
                          <div v-else-if="advancedProperty.property.inputType === 'select'">
                              <SelectMenu v-model="link.data.data[advancedProperty.property.symbolic_name]"
                                  :options="advancedProperty.property.data.options"
                                  :description="advancedProperty.property.description" :required="advancedProperty.required"
                                  :label="advancedProperty.property.name" :disabled="!withAdvancedProperties[index]" />
                          </div>
                          <div v-if="form.hasErrors">
                              <div v-for="(error, key) in form.errors" :key="key">
                                  <jet-input-error v-show="key.includes(advancedProperty.property.symbolic_name)"
                                      :message="error" class="mt-2" />
                              </div>
                          </div>
                      </div>
                  </PropertyDisclosure>
              </div>
            </DisclosurePanel>
          </transition>
        </Disclosure>
      </div>
    </div>

    <!-- <pre>{{ form.errors }}</pre> -->

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
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import { useStore } from "vuex";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { DatabaseIcon, ChevronDownIcon } from "@heroicons/vue/outline";

import {
    sortProperties,
    validateProperies,
    DEFAULT_TEMPLATE,
} from "@/Utils/helpers";

export default {
  components: {
    AppLayout,
    SlideOver,
    SelectMenu,
    TextInput,
    PrimaryButton,
    JetInputError,
    PropertyDisclosure,
    SecondaryOutlinedButton,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    DatabaseIcon,
    ChevronDownIcon,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
    templates: {
        type: Array,
        default: [],
    },
  },

  setup(props) {
    const store = useStore();

    const form = useForm({
      name: props.instance.name,
      description: props.instance.description,
      segments: props.instance.geo_segments,
    });

    let templateKey = null;

    const templates = computed(() =>
        props.templates.map((t) => ({
            key: t.id,
            value: t.name,
            properties: t.template_properties,
        }))
    );

    const template = props.instance.geo_segments.map((segment) => {
      return templates.value.filter((p) => p.key == segment.data.template_id ) ?? DEFAULT_TEMPLATE;
    });

    const selectedTemplate = ref(template[0]);
    const properties = ref([]);
    const advancedProperties = ref([]);
    const withAdvancedProperties = ref([]);
    const userSelectedProperties = ref([]);

    template.map((value, i) => { 
      if (i > 0) {
        selectedTemplate.value.push(value[0])
        withAdvancedProperties.value[i] = true;

        properties.value[i] = sortProperties(
            window._.cloneDeep(
              selectedTemplate.value[i].properties.filter((p) => !p.advanced)
            )
        );

        advancedProperties.value[i] = sortProperties(
            window._.cloneDeep(
                selectedTemplate.value[i].properties.filter((p) => p.advanced)
            )
        );

        userSelectedProperties.value[i] = selectedTemplate.value[i].properties.filter((p) => {
          return !(p.advanced && !withAdvancedProperties.value[i]);
        })
      }

      for (const property of value[0].properties) {
        const prop = property.property;

        if (prop) {
            const placeholder = prop.inputType === "select" ? {} : "";

            if (prop.inputType === "select" 
                && form.segments[i].data.data[prop.symbolic_name] 
                && !Array.isArray(form.segments[i].data.data[prop.symbolic_name])) {

              form.segments[i].data.data[prop.symbolic_name] = prop.data.options.find(
                (o) => o.key == form.segments[i].data.data[prop.symbolic_name]
              );
            } else {
              form.segments[i].data.data[prop.symbolic_name] = 
                form.segments[i].data.data[prop.symbolic_name] ?? property.default_value ?? placeholder
            }
        }
      }
    });

    watch(
        selectedTemplate,
        (template) => {
            let templateId = templateKey ?? 0;
            withAdvancedProperties.value[templateId] = !templateKey ? true : false;

            if (templateKey != null) {
              form.segments[templateId].data.data = {};
              form.segments[templateId].data.template_id = selectedTemplate.value[templateKey].key;
              for (const property of template[templateId].properties) {
                const prop = property.property;

                if (prop) {
                    const placeholder = prop.inputType === "select" ? {} : "";

                      form.segments[templateId].data.data[prop.symbolic_name] = property.default_value
                        ? property.default_value
                        : placeholder;
                }
              }
            }
            
            properties.value[templateId] = sortProperties(
                window._.cloneDeep(
                  selectedTemplate.value[templateId].properties.filter((p) => !p.advanced)
                )
            );

            advancedProperties.value[templateId] = sortProperties(
                window._.cloneDeep(
                    selectedTemplate.value[templateId].properties.filter((p) => p.advanced)
                )
            );

            userSelectedProperties.value[templateId] = selectedTemplate.value[templateId].properties.filter((p) => {
              return !(p.advanced && !withAdvancedProperties.value[templateId]);
            })
        },
        { immediate: true, deep: true }
    );

    const submit = () => {
      let propertyError = false;
      form.clearErrors();

      if (form.name == "") {
        store.commit("objects/setNotify", {
            title: `Link`,
            text: 'The link name field is required.',
            type: 'danger'
        });
        return;
      }

      form.segments.forEach((value, key) => {
        const errors = validateProperies(
          value.data,
          userSelectedProperties.value[key],
          selectedTemplate.value[key]
        );

        if (Object.keys(errors).length) {
          propertyError = true;
          for (const errorGroup in errors) {
            for (const error of errors[errorGroup]) {
              form.setError(errorGroup+key, error);
              store.commit("objects/setNotify", {
                  title: `Segment #${key+1}`,
                  text: `${error}`,
                  type: 'danger'
              });
            }
          }
        }
      });
      if (propertyError)
        return;

      form
        .transform((data) => {
          console.log("Form data is:", data);

          // We want to transform the "to-send" data, not the original data
          const deepCopyOfData = JSON.parse(JSON.stringify(data));

          const segmentData = deepCopyOfData.segments.map((v) => v.data);

          segmentData.forEach((segment, i) => {
            for (const property of selectedTemplate.value[i].properties) {
                const prop = property.property;
                const key = prop.symbolic_name;

                if (property.advanced && !withAdvancedProperties.value[i]) {
                    delete segment.data[key];
                    continue;
                }

                if (prop.inputType === "select") {
                    // if the property has a value, get it and re-assign the property as a string
                    if (Object.keys(segment.data[key]).length) {
                        segment.data[key] = segment.data[key].key;

                        continue;
                    }

                    if (prop.dataType === "string") segment.data[key] = "";
                    else segment.data[key] = null;
                }
            }

            for (const key of Object.keys(segment.data)) {
              if (segment.data[key] === null || segment.data[key] === "")
                  delete segment.data[key]
            }

          });

          deepCopyOfData.segments = segmentData;
          
          return deepCopyOfData;
        })
        .patch(route("objects.links.update", props.instance.id), {
          onSuccess: () => {
            store.dispatch("map/refreshMap");
            store.commit("objects/setNotify", {
                title: 'Link',
                text: 'Link Updated Successfully',
                type: 'success'
            });
            store.dispatch("objects/showSlide", { route: "objects.list" });
          },
          onError: (e) => console.log(e),
        });
    };

    const updateTemplateKey = (key) => {templateKey = key};

    return {
      templates,
      selectedTemplate,
      withAdvancedProperties,
      properties,
      advancedProperties,
      form,
      updateTemplateKey,
      submit,
      onCancel: () =>
        store.dispatch("objects/showSlide", { route: "objects.list" }),
    };
  },
};
</script>
