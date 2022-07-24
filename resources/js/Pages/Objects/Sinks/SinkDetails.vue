<template>
  <SiteHead title="Sink Details" />
  <SlideOver
    type="sink"
    title="Sink Details"
    subtitle="Below, you can see the details that are associated to the currently selected Sink."
  >
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5">
      <PropertyDisclosure
        title="Information"
        defaultOpen
      >
        <!-- Sink ID -->
        <div class="my-4">
          <TextInput
            v-model="instance.id"
            label="Unique Identification"
            description="The unique identifier that identifies the Sink."
            read-only
          />
        </div>

        <!-- Sink Name -->
        <div class="my-4">
          <TextInput
            v-model="instance.name"
            label="Name"
            description="The name of the Sink."
            read-only
          />
        </div>

        <!-- Sink Template -->
        <div class="my-4 flex">
          <div class="w-full">
            <TextInput
              v-model="instance.template.name"
              label="Template Name"
              description="The template that this Sink belongs to."
              read-only
            />
          </div>
          <div class="mt-6" v-if="instance.template.values.help">
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
      </PropertyDisclosure>
    </div>
    <!-- Sink Properties -->
    <div
      v-if="properties.length"
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
      <PropertyDisclosure title="Properties">
        <div
          class="my-6"
          v-for="(property, propertyIdx) in properties"
          :key="propertyIdx"
        >
          <TextInput
            v-model="property.value"
            :description="property.description"
            :label="property.label"
            read-only
          />
        </div>
      </PropertyDisclosure>
    </div>
    <div v-else>
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 place-content-center">
        <div class="col-span-3 text-center">
          <p class="block font-bold text-2xl text-gray-200 p-4">
            No assigned properties.
          </p>
        </div>
      </div>
    </div>

    <div
      v-if="advancedProperties.length"
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
    >
      <PropertyDisclosure title="Advanced Properties">
        <div
          class="my-6"
          v-for="(advancedProperty, advancedPropertyIdx) in advancedProperties"
          :key="advancedPropertyIdx"
        >
          <TextInput
            v-model="advancedProperty.value"
            :description="advancedProperty.description"
            :label="advancedProperty.label"
            read-only
          />
        </div>
      </PropertyDisclosure>
    </div>
    <div v-else>
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 place-content-center">
        <div class="col-span-3 text-center">
          <p class="block font-bold text-2xl text-gray-200 p-4">
            No advanced assigned properties.
          </p>
        </div>
      </div>
    </div>

    <template #actions>
      <SecondaryOutlinedButton
        type="button"
        @click="onClose"
      >
        Cancel
      </SecondaryOutlinedButton>
      <PrimaryButton 
        @click="onRouteRequest('objects.sinks.edit', instance.id)"
        :disabled="startLinks || startMarker"
      >
        Edit
      </PrimaryButton>
    </template>
  </SlideOver>
  <InfoTemplateModal
    v-model="infoTemplateModalIsVisible"
    :info="instance.template.values.help"
  />
</template>

<script>
import { computed, ref } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOvers/SlideOver.vue";
import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import InfoIcon from "@/Components/Icons/InfoIcon.vue";
import InfoTemplateModal from "@/Components/Modals/InfoTemplateModal.vue";

import { sortProperties } from "@/Utils/helpers";

export default {
  components: {
    AppLayout,
    SiteHead,
    SlideOver,
    PropertyDisclosure,
    SelectMenu,
    TextInput,
    PrimaryButton,
    SecondaryOutlinedButton,
    InfoIcon,
    InfoTemplateModal,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
    templateProperties: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();

    const startLinks = computed(
      () => store.getters["map/startLinks"]
    );

    const startMarker = computed(
      () => store.getters["map/selectedMarkerType"]
    );

    const infoTemplateModalIsVisible = ref(false);

    const properties = computed(() => {
      const properties = sortProperties(
        window._.cloneDeep(props.templateProperties.filter((p) => !p.advanced))
      );

      return properties.map((p) => {
        const label = p.property.name;
        const description = p.property.description;

        if (props.instance.values[p.property.symbolic_name] == null)
          return {
            label: label,
            description: description,
            value: "Not Defined",
          };

        if (p.property.inputType === "select")
          return {
            label: label,
            description: description,
            value: !Array.isArray(props.instance.values[p.property.symbolic_name])
              ? p.property.data.options.find(
                  (o) => o.key === props.instance.values[p.property.symbolic_name]
                ).value
              : 'Not Defined',
          };          

        return {
          label: label,
          description: description,
          value: props.instance.values[p.property.symbolic_name],
        };
      });
    });

    const advancedProperties = computed(() => {
      const advancedProperties = sortProperties(
        window._.cloneDeep(props.templateProperties.filter((p) => p.advanced))
      );

      return advancedProperties.map((p) => {
        const label = p.property.name;
        const description = p.property.description;

        if (props.instance.values[p.property.symbolic_name] == null)
          return {
            label: label,
            description: description,
            value: "Not Defined",
          };

        if (p.property.inputType === "select")
          return {
            label: label,
            description: description,
            value: !Array.isArray(props.instance.values[p.property.symbolic_name])
              ? p.property.data.options.find(
                  (o) => o.key === props.instance.values[p.property.symbolic_name]
                ).value
              : 'Not Defined',
          };

        return {
          label: label,
          description: description,
          value: props.instance.values[p.property.symbolic_name],
        };
      });
    });

    const onRouteRequest = (route, props) =>
      store.dispatch("objects/showSlide", { route, props });

    const onClose = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      properties,
      advancedProperties,
      startLinks,
      startMarker,
      infoTemplateModalIsVisible,
      onRouteRequest,
      onClose,
    };
  },
};
</script>
