<template>
  <div class="border border-gray-300 shadow rounded-md p-4">
    <div class="flex space-x-4">
      <div class="space-y-4 py-1 whitespace-normal w-full">
        <div class="flex justify-between">
          <p class="text-xl font-semibold text-gray-800">
            {{ beautifyResourceName(resourceName) }}
          </p>
          <p class="text-sm text-gray-500">
            Last update: {{ getFriendlyLifetime(entity.updated_at) }}
          </p>
        </div>

        <div class="space-y-2">
          <div v-if="resourceName === 'sources' || resourceName === 'sinks'">
            <p class="text-base">
              <span class="font-semibold">
                ID
              </span>
              : {{ entity.id }}
            </p>
            <p class="text-base">
              <span class="font-semibold">
                Name
              </span>
              : {{ entity.name }}
            </p>
          </div>
          <div v-else-if="resourceName === 'links'">
            <p class="text-base">
              <span class="font-semibold">
                ID
              </span>
              : {{ entity.id }}
            </p>
            <p class="text-base">
              <span class="font-semibold">
                Name
              </span>
              : {{ entity.name }}
            </p>
            <p class="text-base">
              <span class="font-semibold">
                Description
              </span>
              : {{ entity.description ?? 'Not provided.' }}
            </p>
          </div>
          <div v-else-if="resourceName === 'locations'">
            <p class="text-base">
              <span class="font-semibold">
                ID
              </span>
              : {{ entity.id }}
            </p>
            <p class="text-base">
              <span class="font-semibold">
                Name
              </span>
              : {{ entity.name }}
            </p>
            <p class="text-base">
              <span class="font-semibold">
                Description
              </span>
              : {{ entity.description ?? 'Not provided.' }}
            </p>
            <p class="text-base">
              <span class="font-semibold">
                Coordinates
              </span>
              : {{ entity.data.center[0] }} , {{ entity.data.center[1] }}
            </p>
          </div>
          <div v-else-if="resourceName === 'projects'">
            <p class="text-base">
              <span class="font-semibold">
                ID
              </span>
              : {{ entity.id }}
            </p>
            <p class="text-base">
              <span class="font-semibold">
                Name
              </span>
              : {{ entity.name }}
            </p>
            <p class="text-base">
              <span class="font-semibold">
                Description
              </span>
              : {{ entity.description ?? 'Not provided.' }}
            </p>
          </div>
          <div v-else-if="resourceName === 'news'">
            <p class="text-lg">{{ entity.title }}</p>
            <p
              class="text-base text-gray-600"
              v-html="entity.content"
            ></p>
          </div>
          <div v-else-if="resourceName === 'faqs'">
            <p class="text-lg">{{ entity.question }}</p>
            <p
              class="text-base text-gray-600"
              v-html="entity.answer"
            ></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import pluralize from "pluralize";

import { getFriendlyLifetime } from "@/Helpers/helpers";

export default {
  props: {
    resourceName: {
      type: String,
      required: true,
    },
    entity: {
      type: Object,
      required: true,
    },
  },

  setup() {
    const beautifyResourceName = (name) =>
      getSingular(name).charAt(0).toUpperCase() + getSingular(name).slice(1);

    const getSingular = (value) => pluralize.singular(value);

    return {
      getFriendlyLifetime,
      beautifyResourceName,
    };
  },
};
</script>
