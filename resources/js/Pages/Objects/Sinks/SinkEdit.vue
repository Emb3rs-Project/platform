<template>

  <SiteHead title="Edit a Sink" />

  <SlideOver
    title="Edit Sink"
    subtitle="Below, you can edit the details that are associated to the currently selected Sink."
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
            :label="prop.property.name"
            :description="prop.property.description"
            :unit="prop.unit.symbol"
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
          <div
            v-for="(error, key) in form.errors"
            :key="key"
          >
            <JetInputError
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
import { useForm } from "@inertiajs/inertia-vue3";
import { useStore } from "vuex";

import SiteHead from "@/Components/SiteHead.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import JetInputError from "../../../Jetstream/InputError";
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
    instance: {
      type: Object,
      required: true,
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
    });

    const templateInfo = ref(null);

    const templates = computed(() =>
      props.templates.map((t) => ({
        key: t.id,
        value: t.name,
        properties: t.template_properties,
      }))
    );
    const selectedTemplate = ref(
      templates.value.find((t) => t.key === props.instance.template.id)
    );
    watch(
      selectedTemplate,
      (template) => {
        templateInfo.value = templates.value.find(
          (t) => t.key === template.key
        );
        form.template_id = template.key;
        form.sink.data.name = props.instance.name;

        if (templateInfo.value.properties.length) {
          for (const property of templateInfo.value.properties) {
            const prop = property.property;

            const value = props.instance.values[prop.symbolic_name];

            if (value) {
              if (prop.inputType === "select") {
                form.sink.data[prop.symbolic_name] = prop.data.options.find(
                  (o) => o.value === value
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
        }
      },
      { immediate: true, deep: true }
    );

    const locations = computed(() =>
      props.locations.map((l) => ({
        key: l.id,
        value: l.name,
      }))
    );
    const selectedLocation = ref(
      locations.value.find((l) => l.key === props.instance.location.id)
    );
    watch(
      selectedLocation,
      (selectedLocation) => {
        form.location_id = selectedLocation.key;
      },
      { immediate: true, deep: true }
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
          const deepCopyOfData = JSON.parse(JSON.stringify(data));

          const sinkData = deepCopyOfData.sink.data;

          if (templateInfo.value.properties.length) {
            for (const property of templateInfo.value.properties) {
              const prop = property.property;
              const key = prop.symbolic_name;

              if (prop.inputType === "select") {
                // if the property has a value, get it and re-assign the property as a string
                if (Object.keys(sinkData[key]).length) {
                  sinkData[key] = sinkData[key].value;
                }
              }
            }
          }
          return deepCopyOfData;
        })
        .patch(route("objects.sinks.update", props.instance.id), {
          onSuccess: () => {
            store.dispatch("map/refreshMap");
            store.dispatch("objects/showSlide", { route: "objects.list" });
          },
        });
    };

    const onCancel = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    const onLocationSelect = () => {};

    return {
      templateInfo,
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      form,
      properties,
      submit,
      onLocationSelect,
      onCancel,
    };
  },
};
</script>

<style>
</style>
