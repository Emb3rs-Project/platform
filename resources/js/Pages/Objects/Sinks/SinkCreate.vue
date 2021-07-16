<template>
  <SiteHead title="Create a Sink" />
  <SlideOver
    v-model="open"
    title="New Sink"
    subtitle="Get started by filling in the information below to create your new Sink. This Sink will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Sink Template -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
          Templates
        </label>
      </div>
      <div class="sm:col-span-2">
        <SelectMenu
          v-model="selectedTemplate"
          :options="templates"
        />
      </div>
    </div>

    <!-- Sink Location -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label
          for="project_name"
          class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
        >
          Locations
        </label>
      </div>
      <div class="sm:col-span-2">
        <SelectMenu
          v-model="selectedLocation"
          :options="locations"
          :disabled="selectedTemplate ? false : true"
        />
      </div>
    </div>

    <!-- Sink Properties -->
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-for="prop in properties"
      :key="prop.id"
    >
      <div>
        <label
          for="project_name"
          class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
        >
          {{ prop.property.description }}
        </label>
      </div>
      <div class="sm:col-span-2">
        <div v-if="prop.property.inputType === 'text'">
          <TextInput
            v-model="form.sink.data[prop.property.symbolic_name]"
            :unit="prop.unit.symbol"
            :label="prop.property.name"
            :placeholder="prop.property.name"
            :required="prop.required"
          />

        </div>
        <div v-else-if="prop.property.inputType === 'select'">
          <SelectMenu
            v-model="form.sink.data[prop.property.symbolic_name]"
            :options="prop.property.data.options"
            :disabled="selectedTemplate ? false : true"
            :required="prop.required"
            :label="prop.property.name"
          />
        </div>
        <div v-if="Object.keys(form.errors).length">
          <jet-input-error
            :message="form.errors"
            class="mt-2"
          />
        </div>
        <!-- <div
          v-for="error in form.errors"
          :key="error"
        >
          <div :v-if="error === form.sink.data[prop.property.symbolic_name]">
            <jet-input-error
              :message="form.errors"
              class="mt-2"
            />
          </div>
        </div> -->

      </div>
    </div>
    <pre>{{form}}</pre>

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
    modelValue: {
      type: Boolean,
      required: true,
    },
    templates: {
      type: Array,
      default: [],
    },
    locations: {
      type: Array,
      default: [],
    },
  },

  setup(props, { emit }) {
    console.log(props.templates);
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
    const selectedTemplate = ref(
      templates.value.length ? templates.value[0] : null
    );

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

        // console.log("SELECTED LOCATION", selectedLocation.value);

        if (typeof selectedLocation.value.key === "object") {
          return (form.location = {
            lat: location.key.lat,
            lng: location.key.lng,
          });
        }

        console.log("SELECTED LOCATION", selectedLocation.value);

        return (form.location_id = location.key);
      },
      { immediate: true, deep: true }
    );

    watch(
      selectedTemplate,
      (template) => {
        templateInfo.value = templates.value.find(
          (t) => t.key === template.key
        );
        form.template_id = template.key;

        if (templateInfo.value.properties.length) {
          for (const property of templateInfo.value.properties) {
            form.sink.data[property.property.symbolic_name] =
              property.default_value ? property.default_value : "";
          }
        }
      },
      { immediate: true }
    );

    const properties = computed(() =>
      Object.assign([], templateInfo.value.properties)
    );

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const submit = () => {
      // validation
      // if (templateInfo.value.properties.length) {
      //   for (const property of templateInfo.value.properties) {
      //     if (property.required) {
      //       form.sink.data[property.property.symbolic_name]
      //     }
      //     console.log(property.);

      //     // form.sink.data[property.property.symbolic_name] =
      //     //   property.default_value ? property.default_value : "";
      //   }
      // }
      console.log(form);
      form.post(route("objects.sinks.store"), {
        onSuccess: () => {
          store.dispatch("map/refreshMap");
          store.dispatch("objects/showSlide", { route: "objects.list" });
        },
      });
    };

    const onCancel = () => {
      store.dispatch("objects/showSlide", { route: "objects.list" });
    };

    return {
      form,
      templateInfo,
      templates,
      selectedTemplate,
      locations,
      selectedLocation,
      properties,
      open,
      submit,
      onCancel,
    };
  },
};
</script>
