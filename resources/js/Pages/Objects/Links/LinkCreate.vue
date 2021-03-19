<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        Objects | Links
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Link</h1>
    </div>
    <div class="flex p-5 h-content gap-2">
      <div class="w-6/12 md:overflow-y-auto md:pr-4">
        <input-row
          heading="Details | Link"
          desc="Enter a name for your link"
          v-model="form.name"
        >
          Link Name
        </input-row>

        <input-row
          class="mt-5"
          desc="Enter a description for your link"
          v-model="form.description"
        >
          Link Description
        </input-row>

        <select-row
          class="mt-5"
          desc="Sharing Options"
          :options="locationSharingOptions"
          v-model="form.locationSharing"
        >
          Share With
        </select-row>
      </div>

      <div class="w-6/12">
        <leaflet-map-create-link
          :radius="form.locationRadius"
          v-model="form.locationData"
        ></leaflet-map-create-link>
      </div>
    </div>
    <div class="w-full my-5 px-16 flex justify-end">
      <jet-button :disabled="form.processing" @click="submit()"
        >Create Location</jet-button
      >
    </div>
  </app-layout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout";
import LeafletMapCreateLink from "@/Components/LeafletMapCreateLink";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";

export default {
  components: {
    AppLayout,
    LeafletMapCreateLink,
    InputRow,
    RadioRow,
    SelectRow,
    JetButton,
    JetInputError,
  },

  setup() {
    const form = useForm({
      name: "",
      description: "",
      locationSharing: "",
      locationData: {},
    });

    const locationSharingOptions = [
      { key: "no", value: "I don't want to share my Location" },
      { key: "team", value: "I want to share my Location with my Institution" },
      { key: "public", value: "I want to share my Location with everyone" },
    ];

    return {
      form,
      locationSharingOptions,
    };
  },
  methods: {
    submit() {
      this.form.post(route("objects.links.store"));
    },
  },
};
</script>

<style>
</style>
