<template>
  <div class="flex space-x-3 mt-2 mb-2">
    <div v-if="entity.type === 'sources'">
      <FireIcon class="h-6 w-6 rounded-full text-red-600" />
    </div>
    <div v-else-if="entity.type === 'sinks'">
      <LightningBoltIcon class="h-6 w-6 rounded-full text-green-600" />
    </div>
    <div v-else-if="entity.type === 'links'">
      <LinkIcon class="h-6 w-6 rounded-full text-blue-600" />
    </div>
    <div v-else-if="entity.type === 'locations'">
      <LocationMarkerIcon class="h-6 w-6 rounded-full text-yellow-600" />
    </div>
    <div v-else-if="entity.type === 'projects'">
      <BriefcaseIcon class="h-6 w-6 rounded-full text-purple-600" />
    </div>
    <div v-else-if="entity.type === 'simulations'">
      <ChipIcon class="h-6 w-6 rounded-full text-orange-600" />
    </div>
    <div v-else-if="entity.type === 'news'">
      <NewspaperIcon class="h-6 w-6 rounded-full text-teal-600" />
    </div>
    <div v-else-if="entity.type === 'faqs'">
      <SupportIcon class="h-6 w-6 rounded-full text-gray-600" />
    </div>

    <div class="flex-1 space-y-1">
      <div class="flex items-center justify-between">
        <span class="text-base font-bold">
          {{ beautifyResourceName(entity.type) }}
        </span>
        <p class="text-sm text-gray-500">
          {{ getFriendlyLifetime(entity.updated_at) }}
        </p>
      </div>
      <div class="text-sm font-medium">
        <div></div>
        <div
          v-if="entity.type !== 'locations' || entity.type !== 'news'  || entity.type !== 'faqs'"
          class="text-xs text-gray-400 -mt-1 mb-1"
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

        <div v-if="entity.type === 'news'">
          {{ entity.title }}
        </div>
        <div v-else-if="entity.type === 'faqs'">
          {{ entity.question }}
        </div>
        <div v-else>
          {{ entity.name === 'Not Defined' ? 'Name has not been defined.' : entity.name }}
        </div>
      </div>
      <div class="text-sm text-gray-500">
        <div v-if="entity.type === 'links' || entity.type === 'locations' || entity.type === 'projects'">
          {{ entity.description ?? 'Description has not been provided.' }}
        </div>
        <div v-else-if="entity.type === 'news'">
          <p v-html="entity.content"></p>
        </div>
        <div v-else-if="entity.type === 'faqs'">
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
  ChipIcon,
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
    ChipIcon,
    NewspaperIcon,
    SupportIcon,
    FingerPrintIcon,
  },

  props: {
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
