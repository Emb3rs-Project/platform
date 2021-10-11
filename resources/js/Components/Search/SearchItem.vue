<template>
  <div class="flex space-x-3">
    <div v-if="resourceName === 'sources'">
      <FireIcon class="h-6 w-6 rounded-full text-red-600" />
    </div>
    <div v-else-if="resourceName === 'sinks'">
      <LightningBoltIcon class="h-6 w-6 rounded-full text-green-600" />
    </div>
    <div v-else-if="resourceName === 'links'">
      <LinkIcon class="h-6 w-6 rounded-full text-blue-600" />
    </div>
    <div v-else-if="resourceName === 'locations'">
      <LocationMarkerIcon class="h-6 w-6 rounded-full text-yellow-600" />
    </div>
    <div v-else-if="resourceName === 'projects'">
      <BriefcaseIcon class="h-6 w-6 rounded-full text-purple-600" />
    </div>
    <div v-else-if="resourceName === 'news'">
      <NewspaperIcon class="h-6 w-6 rounded-full text-teal-600" />
    </div>
    <div v-else-if="resourceName === 'faqs'">
      <SupportIcon class="h-6 w-6 rounded-full text-gray-600" />
    </div>

    <div class="flex-1 space-y-1">
      <div class="flex items-center justify-between">
        <span class="text-base font-bold">
          {{ beautifyResourceName(resourceName) }}
        </span>
        <p class="text-sm text-gray-500">
          {{ getFriendlyLifetime(entity.updated_at) }}
        </p>
      </div>
      <div class="text-sm font-medium">
        <div
          v-if="resourceName === 'sources' || resourceName === 'sinks' || resourceName === 'links' || resourceName === 'projects'"
          class="text-xs text-gray-400 -mt-1 mb-2"
        >
          <span class="font-normal">
            with
          </span>
          <FingerPrintIcon class="inline-block h-2.5 w-auto rounded-ful" />
          <span class="font-semibold">
            ID
          </span>
          <span>
            {{ entity.id }}
          </span>
        </div>

        <div v-if="resourceName === 'news'">
          {{ entity.title }}
        </div>
        <div v-else-if="resourceName === 'faqs'">
          {{ entity.question }}
        </div>
        <div v-else>
          {{ entity.name === 'Not Defined' ? 'Name has not been defined.' : entity.name }}
        </div>
      </div>
      <div class="text-sm text-gray-500">
        <div v-if="resourceName === 'links' || resourceName === 'locations' || resourceName === 'projects'">
          {{ entity.description ?? 'Description has not been provided.' }}
        </div>
        <div v-else-if="resourceName === 'news'">
          <p v-html="entity.content"></p>
        </div>
        <div v-else-if="resourceName === 'faqs'">
          <p v-html="entity.answer"></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import pluralize from "pluralize";

import {
  FireIcon,
  LightningBoltIcon,
  LinkIcon,
  LocationMarkerIcon,
  BriefcaseIcon,
  NewspaperIcon,
  SupportIcon,
  FingerPrintIcon,
} from "@heroicons/vue/solid";

import { getFriendlyLifetime } from "@/Helpers/helpers";

export default {
  components: {
    FireIcon,
    LightningBoltIcon,
    LinkIcon,
    LocationMarkerIcon,
    BriefcaseIcon,
    NewspaperIcon,
    SupportIcon,
    FingerPrintIcon,
  },

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
