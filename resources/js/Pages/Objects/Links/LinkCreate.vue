<template>
  <SlideOver
    :title="'New Link'"
    :subtitle="'Get started by selecting a marker to start your segments.'"
    :headerBackground="'bg-blue-700'"
    :closeOnEscape="false"
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
      v-for="(link, index) in linkList"
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
                      <div class="sm:grid sm:grid-cols-2">
                        <div>
                          <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                            From
                          </label>
                        </div>
                        <div class="sm:col-span-2">
                          <div>
                            <TextInput
                              v-model="link.from"
                              :disabled="true"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="sm:grid sm:grid-cols-2">
                        <div>
                          <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                            To
                          </label>
                        </div>
                        <div class="sm:col-span-2">
                          <div>
                            <TextInput
                              v-model="link.to"
                              :disabled="true"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="sm:grid sm:grid-cols-2">
                        <div>
                          <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                            Distance
                          </label>
                        </div>
                        <div class="sm:col-span-2">
                          <div>
                            <TextInput
                              v-model="link.distance"
                              :disabled="true"
                              unit="m"
                            />
                          </div>
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
                              <TextInput v-model="link.data[property.property.symbolic_name]"
                                  :unit="property.unit.symbol" :description="property.property.description"
                                  :label="property.property.name" :placeholder="property.property.name"
                                  :required="property.required" />
                          </div>
                          <div v-else-if="property.property.inputType === 'select'">
                              <SelectMenu v-model="link.data[property.property.symbolic_name]"
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
                              <TextInput v-model="link.data[advancedProperty.property.symbolic_name]"
                                  :unit="advancedProperty.unit.symbol" :description="advancedProperty.property.description"
                                  :label="advancedProperty.property.name" :placeholder="advancedProperty.property.name"
                                  :required="advancedProperty.required" :disabled="!withAdvancedProperties[index]" />
                          </div>
                          <div v-else-if="advancedProperty.property.inputType === 'select'">
                              <SelectMenu v-model="link.data[advancedProperty.property.symbolic_name]"
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
import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
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
    PropertyDisclosure,
    JetInputError,
    SecondaryOutlinedButton,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    DatabaseIcon,
    ChevronDownIcon,
  },

  props: {
      templates: {
          type: Array,
          default: [],
      },
  },

  setup(props) {
    const store = useStore();
    const withAdvancedProperties = ref([]);

    const form = useForm({
      name: "",
      description: "",
      segments: {data: []},
    });

    let templateKey = 0;

    const templates = computed(() =>
        props.templates.map((t) => ({
            key: t.id,
            value: t.name,
            properties: t.template_properties,
        }))
    );
    const selectedTemplate = ref([]);
    selectedTemplate.value.push(templates.value[0] ?? DEFAULT_TEMPLATE);

    const links = computed(() => store.getters["map/currentLinks"]);
    const linkList = computed(() => Object.values(links.value));

    const properties = ref([]);
    const advancedProperties = ref([]);
    const userSelectedProperties = ref([]);

    watch(
        selectedTemplate,
        (template) => {
          if (linkList.value[templateKey]) {
            withAdvancedProperties.value[templateKey] = false;
            form.segments = linkList.value[templateKey];

            for (const property of template[templateKey].properties) {
                const prop = property.property;

                if (prop) {
                    const placeholder = prop.inputType === "select" ? {} : "";

                      form.segments.data[prop.symbolic_name] = property.default_value
                        ? property.default_value
                        : placeholder;
                }
            }
            
            properties.value[templateKey] = sortProperties(
                window._.cloneDeep(
                  selectedTemplate.value[templateKey].properties.filter((p) => !p.advanced)
                )
            );

            advancedProperties.value[templateKey] = sortProperties(
                window._.cloneDeep(
                    selectedTemplate.value[templateKey].properties.filter((p) => p.advanced)
                )
            );
            

            userSelectedProperties.value[templateKey] = selectedTemplate.value[templateKey].properties.filter((p) => {
              return !(p.advanced && !withAdvancedProperties.value[templateKey]);
            })

            linkList.value[templateKey].data = form.segments.data;
          }
        },
        { immediate: true, deep: true }
    );

    watch(
        linkList,
        (segments) => {
          templateKey = segments.length -1;
          
          if (templateKey > 0) {
            selectedTemplate.value.push(templates.value[0] ?? DEFAULT_TEMPLATE);
          }
        },
        { immediate: true }
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

      if (!linkList.value.length) {
        store.commit("objects/setNotify", {
            title: `Link`,
            text: 'The link segment is required.',
            type: 'danger'
        });
        return;
      }
        
      linkList.value.forEach((value, key) => {
        const errors = validateProperies(
          value,
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

      form.segments = linkList.value;
      
      form
        .transform((data) => {
          const deepCopyOfData = window._.cloneDeepWith(data, (value) => {
            if (Array.isArray(value)){
              return value.map((v, i) => ({
                ...v,
                data: Object.assign({}, v.data),
                template_id: selectedTemplate.value[i].key
              }));
            }
          });

          const segmentData = deepCopyOfData.segments;

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

          return deepCopyOfData;
        })
        .post(route("objects.links.store"), {
          onSuccess: () => {
            store.dispatch("map/removeAllSegment", true);
            store.dispatch("map/saveLink", true);
            store.dispatch("map/refreshMap");
            store.commit("objects/setNotify", {
                title: 'Link',
                text: 'Link Created Successfully',
                type: 'success'
            });
            //store.commit("objects/closeSlide");
            store.dispatch("objects/showSlide", { route: "objects.list" });
          },
          onError: (e) => console.log(e),
        });
    };

    const updateTemplateKey = (key) => {templateKey = key};

    return {
      updateTemplateKey,
      form,
      templates,
      selectedTemplate,
      withAdvancedProperties,
      properties,
      advancedProperties,
      submit,
      onCancel: () => {
        store.dispatch("map/removeAllSegment", true);
        store.dispatch("map/refreshMap");
        store.dispatch("objects/showSlide", { route: "objects.list" })
      },
      linkList,
    };
  },
};
</script>
