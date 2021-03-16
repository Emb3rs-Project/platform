<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        Objects | Locations
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Location</h1>
    </div>
    <div class="flex p-5 h-content gap-2">
      <div class="w-6/12 md:overflow-y-auto md:pr-4">
        <input-row
          heading="Details | Location"
          desc="Enter a name for your location"
          v-model="form.locationName"
        >
          Location Name
        </input-row>

        <select-row
          class="mt-5"
          desc="Choose how you want to define your Location"
          :options="locationTypes"
          v-model="form.locationType"
        >
          What type of location?
        </select-row>

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
        <leaflet-map-create
          :markerType="form.locationType"
        ></leaflet-map-create>
      </div>
    </div>
    <div class="w-full my-5 px-16 flex justify-end">
      <jet-button :disabled="form.processing">Create Location</jet-button>
    </div>
  </app-layout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout";
import LeafletMapCreate from "@/Components/LeafletMapCreate";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";

export default {
  components: {
    AppLayout,
    LeafletMapCreate,
    InputRow,
    RadioRow,
    SelectRow,
    JetButton,
    JetInputError,
  },

  setup() {
    const form = useForm({
      locationName: "",
      locationType: "Point",
      locationSharing: "",
    });

    const locationTypes = [
      { key: "point", value: "Point" },
      { key: "circle", value: "Circle" },
      { key: "polygon", value: "Polygon" },
    ];

    const locationSharingOptions = [
      { key: "no", value: "I don't want to share my Location" },
      { key: "team", value: "I want to share my Location with my Institution" },
      { key: "public", value: "I want to share my Location with everyone" },
    ];

    return {
      form,
      locationTypes,
      locationSharingOptions,
    };
  },
  methods: {
    submit() {},
  },
};
</script>

<style>
</style>
