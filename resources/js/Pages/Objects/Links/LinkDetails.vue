<template>
  <SiteHead title="Link Details" />

  <SlideOver
    title="Link Details"
    subtitle="Below, you can see the details that are associated to the currently selected Sink."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <!-- Link ID -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          ID
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ instance.id }}
        </div>
      </div>
    </div>

    <!-- Link Name -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          Name
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ instance.name }}
        </div>
      </div>
    </div>

    <!-- Link Description -->
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div>
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          Description
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ instance.description ?? "Not defined." }}
        </div>
      </div>
    </div>

    <!-- Link Segments -->
    <div
      v-if="instance.geo_segments != null && Object.keys(instance.geo_segments).length"
      class="divide-y"
    >
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
        <div class="block text-base font-medium text-gray-900 sm:pt-1">
          <p>Segments</p>
        </div>
      </div>
      <div
        class="space-y-1 px-4 sm:space-y-0 sm:gap-4 sm:px-6 sm:py-5"
        v-for="(segment, segmentIdx) in instance.geo_segments"
        :key="segmentIdx"
      >
        <div class="sm:grid sm:grid-cols-4">
          <div>
            <label class="block text-sm font-medium text-gray-500 sm:pt-1">
              From
            </label>
            <label class="block text-sm font-medium text-gray-500 sm:pt-1">
              To
            </label>
          </div>
          <div class="col-span-2">
            <div class="block text-sm font-medium text-gray-900 sm:pt-1">
              {{ segment.data.from }}
            </div>
            <div class="block text-sm font-medium text-gray-900 sm:pt-1">
              {{ segment.data.to }}
            </div>
          </div>
          <div class="flex place-content-center">
            <SecondaryButton
              variant="location"
              type="button"
              @click="onGoToLocation([segment.data.from, segment.data.to])"
            >
              View Location
            </SecondaryButton>
          </div>
        </div>
        

        <!-- Link Properties --> 
        <div
          v-if="properties.length && segment.properties.length"
          class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
        >
          <PropertyDisclosure title="Properties">
            <div
              class="my-6"
              v-for="(property, propertyIdx) in segment.properties"
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
          v-if="advancedProperties.length && segment.advancedProperties.length"
          class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5"
        >
          <PropertyDisclosure title="Advanced Properties">
            <div
              class="my-6"
              v-for="(advancedProperty, advancedPropertyIdx) in segment.advancedProperties"
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
      </div>
    </div>
    <div v-else>
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 place-content-center justify-items-center">
        <div class="col-span-3">
          <p class="block font-bold text-2xl text-gray-200 p-4">
            No assigned segments.
          </p>
        </div>
      </div>
    </div>

    <template #actions>
      <SecondaryOutlinedButton
        type="button"
        @click="onClose()"
      >
        Cancel
      </SecondaryOutlinedButton>
      <PrimaryButton 
        @click="onRouteRequest('objects.links.edit', instance.id)"
        :disabled="startLinks || startMarker"
      >
        Edit
      </PrimaryButton>
    </template>
  </SlideOver>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SlideOver from "@/Components/SlideOver.vue";
import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

import { sortProperties } from "@/Utils/helpers";

export default {
  components: {
    AppLayout,
    SiteHead,
    SlideOver,
    PropertyDisclosure,
    SelectMenu,
    TextInput,
    SecondaryButton,
    PrimaryButton,
    SecondaryOutlinedButton,
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

    const properties = computed(() => {
      const propertiesItem = [];
      props.instance.geo_segments.map((segment, i) => {
        let properties = sortProperties(
          window._.cloneDeep(props.templateProperties[i].filter((p) => !p.advanced))
        );
        props.instance.geo_segments[i].properties = properties.map((p) => {
          const label = p.property.name;
          const description = p.property.description;

          if (segment.data.data[p.property.symbolic_name] == null)
            return {
              label: label,
              description: description,
              value: "Not Defined",
            };

          if (p.property.inputType === "select")
            return {
              label: label,
              description: description,
              value: !Array.isArray(segment.data.data[p.property.symbolic_name])
                ? p.property.data.options.find(
                    (o) => o.key === segment.data.data[p.property.symbolic_name]
                  ).value
                : 'Not Defined',
            };

          return {
            label: label,
            description: description,
            value: segment.data.data[p.property.symbolic_name],
          };
        });

        propertiesItem.push(props.instance.geo_segments[i].properties)
      });
      return propertiesItem;
    });

    const advancedProperties = computed(() => {
      const advancedProperty = [];
      props.instance.geo_segments.map((segment, i) => {
        let advancedProperties = sortProperties(
          window._.cloneDeep(props.templateProperties[i].filter((p) => p.advanced))
        );
        props.instance.geo_segments[i].advancedProperties = advancedProperties.map((p) => {
          const label = p.property.name;
          const description = p.property.description;

          if (segment.data.data[p.property.symbolic_name] == null)
            return {
              label: label,
              description: description,
              value: "Not Defined",
            };

          if (p.property.inputType === "select")
            return {
              label: label,
              description: description,
              value: !Array.isArray(segment.data.data[p.property.symbolic_name])
                ? p.property.data.options.find(
                    (o) => o.key === segment.data.data[p.property.symbolic_name]
                  ).value
                : 'Not Defined',
            };

          return {
            label: label,
            description: description,
            value: segment.data.data[p.property.symbolic_name],
          };
        });

        advancedProperty.push(props.instance.geo_segments[i].advancedProperties)
      });
      return advancedProperty;
    });

    const onGoToLocation = (loc) => { 
        let lat = (loc[0].lat + loc[1].lat)/2;
        let lng = (loc[0].lng + loc[1].lng)/2;
        
        store.dispatch("map/centerAt", {
        marker: {
          type: "point",
          data: {
            center: {lat, lng},
          },
        },
      });
    };

    const onRouteRequest = (route, props) => {
      store.dispatch("objects/showSlide", { route, props });
    };

    const onClose = () =>
      store.dispatch("objects/showSlide", { route: "objects.list" });

    return {
      properties,
      advancedProperties,
      startLinks,
      startMarker,
      onGoToLocation,
      onRouteRequest,
      onClose,
    };
  },
};
</script>
