<template>
  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
    <div class="block text-base font-medium text-gray-900 sm:pt-1">
      <p>Information</p>
    </div>
  </div>
  <!-- Sink ID -->
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

  <!-- Sink Name -->
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

  <!-- Sink Template -->
  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
    <div>
      <label class="block text-sm font-medium text-gray-500 sm:pt-1">
        Template
      </label>
    </div>
    <div class="sm:col-span-2">
      <div class="block text-sm font-medium text-gray-900 sm:pt-1">
        {{ instance.template.name }}
      </div>
    </div>
  </div>

  <!-- Sink Location Name -->
  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
    <div>
      <label class="block text-sm font-medium text-gray-500 sm:pt-1">
        Location
      </label>
    </div>
    <div class="sm:col-span-2">
      <div class="block text-sm font-medium text-gray-900 sm:pt-1">
        {{ instance.location.name }}
      </div>
    </div>
  </div>

  <!-- Sink Location Description -->
  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
    <div>
      <label class="block text-sm font-medium text-gray-500 sm:pt-1">
        Location Description
      </label>
    </div>
    <div class="sm:col-span-2">
      <div class="block text-sm font-medium text-gray-900 sm:pt-1">
        {{ instance.location.description ?? "Not available."}}
      </div>
    </div>
  </div>

  <!-- Source Properties -->
  <div
    v-if="Object.keys(instance.values).length"
    class="divide-y"
  >
    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
      <div class="block text-base font-medium text-gray-900 sm:pt-1">
        <p>Properties</p>
      </div>
    </div>
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
      v-for="property in properties"
      :key="property"
    >
      <div class="sm:col-span-1">
        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
          {{ property.key }}
        </label>
      </div>
      <div class="sm:col-span-2">
        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
          {{ property.value }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";

import { sortProperties } from "../helpers/sort-properties";

export default {
  props: {
    instance: {
      type: Object,
      required: true,
    },
  },

  setup(props) {
    const properties = ref([]);

    const matchPropertiesToTemplateProperties = () => {
      const properties = [];

      const instanceProperties = props.instance.values.properties;
      const templateProperties = props.instance.template.template_properties;

      for (const instanceProperty in instanceProperties) {
        for (const templateProperty of templateProperties) {
          if (templateProperty["property"].symbolic_name === instanceProperty) {
            let value = instanceProperties[instanceProperty];

            if (templateProperty["property"].inputType === "select") {
              const options = templateProperty["property"].data.options;

              for (const option in options) {
                if (options[option].key === value) {
                  value = options[option].value;

                  break;
                }
              }
            }

            properties.push({
              key: templateProperty["property"].name,
              value: value,
              order: templateProperty["order"],
            });

            break;
          }
        }
      }

      return sortProperties(properties);
    };

    properties.value = matchPropertiesToTemplateProperties();

    return {
      properties,
    };
  },
};
</script>
