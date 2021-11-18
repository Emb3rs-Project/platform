<template>
  <SiteHead title="Create a Sink" />

  <SlideOver
    title="New Sink"
    subtitle="Get started by filling in the information below to create your new Sink. This Sink will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Sink Template -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div class="sm:col-span-3">
        <SelectMenu
          v-model="selectedTemplate"
          :options="templates"
          label="Template"
          description="THIS IS A VERY GOOD DESCRIPTION IF I MAY SAY"
        />
      </div>
    </div>

    <!-- Sink Location -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div class="sm:col-span-3">
        <SelectMenu
          v-model="selectedLocation"
          :options="locations"
          label="Location"
          :disabled="selectedTemplate ? false : true"
          description="THIS IS A VERY GOOD DESCRIPTION IF I MAY SAY"
        />
      </div>
    </div>

    <!-- Sink Properties -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-for="prop in properties"
      :key="prop.id"
    >
      <div class="sm:col-span-3">
        <div v-if="prop.property.inputType === 'text' || prop.property.inputType === 'String'">
          <TextInput
            v-model="form.sink.data[prop.property.symbolic_name]"
            :unit="prop.unit.symbol"
            :description="prop.property.description"
            :label="prop.property.name"
            :placeholder="prop.property.name"
            :required="prop.required"
          />
        </div>
        <div v-else-if="prop.property.inputType === 'select'">
          <SelectMenu
            v-model="form.sink.data[prop.property.symbolic_name]"
            :options="prop.property.data.options"
            :description="prop.property.description"
            :disabled="selectedTemplate ? false : true"
            :required="prop.required"
            :label="prop.property.name"
          />
        </div>
        <div v-if="form.hasErrors">
          <pre>{{form.errors}}</pre>
          <div
            v-for="(error, key) in form.errors"
            :key="key"
          >
            <jet-input-error
              v-show="key.includes(prop.property.symbolic_name)"
              :message="error"
              class="mt-2"
            />
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

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

export default {
  components: {
    AppLayout,
    SiteHead,
    SlideOver,
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

    const properties = computed(() => {
      const properties = [];

      Object.assign(properties, templateInfo.value.properties);

      properties.sort((a, b) =>
        a.order < b.order ? -1 : a.order > b.order ? 1 : 0
      );

      return properties;
    });

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
      properties,
      submit,
      onCancel,
    };
  },
};
</script>
