<template>
  <!-- Source Information -->
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
      <div class="my-4">
        <TextInput
          v-model="instance.template.name"
          label="Template Name"
          description="The template that this Sink belongs to."
          read-only
        />
      </div>
    </PropertyDisclosure>
  </div>

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
</template>

<script>
import { computed } from "vue";

import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
import TextInput from "@/Components/Forms/TextInput.vue";

import { sortProperties } from "@/Utils/helpers";

export default {
  components: {
    PropertyDisclosure,
    TextInput,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
  },

  setup(props) {
    const templateProperties = props.instance.template.template_properties;

    const properties = computed(() => {
      const properties = sortProperties(
        window._.cloneDeep(templateProperties.filter((p) => !p.advanced))
      );

      return properties.map((p) => {
        const label = p.property.name;
        const description = p.property.description;

        if (props.instance.values.properties[p.property.symbolic_name] == null)
          return {
            label: label,
            description: description,
            value: "Not Defined",
          };

        if (p.property.inputType === "select")
          return {
            label: label,
            description: description,
            value: p.property.data.options.find(
              (o) =>
                o.key ===
                props.instance.values.properties[p.property.symbolic_name]
            ).value,
          };

        return {
          label: label,
          description: description,
          value: props.instance.values.properties[p.property.symbolic_name],
        };
      });
    });

    const advancedProperties = computed(() => {
      const advancedProperties = sortProperties(
        window._.cloneDeep(templateProperties.filter((p) => p.advanced))
      );

      return advancedProperties.map((p) => {
        const label = p.property.name;
        const description = p.property.description;

        if (props.instance.values.properties[p.property.symbolic_name] == null)
          return {
            label: label,
            description: description,
            value: "Not Defined",
          };

        if (p.property.inputType === "select")
          return {
            label: label,
            description: description,
            value: p.property.data.options.find(
              (o) =>
                o.key ===
                props.instance.values.properties[p.property.symbolic_name]
            ).value,
          };

        return {
          label: label,
          description: description,
          value: props.instance.values.properties[p.property.symbolic_name],
        };
      });
    });

    return {
      properties,
      advancedProperties,
    };
  },
};
</script>
