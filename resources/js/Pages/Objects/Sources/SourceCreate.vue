<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sources
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Source</h1>
    </div>
    <form @submit.prevent="submit">
      <div class="flex p-5 h-screen md:h-content gap-2">
        <div class="w-6/12 md:overflow-y-auto md:pr-4">
          <select-row
            class="mt-5"
            desc="Source Templates"
            :options="templateNames"
            v-model="form.sourceTemplate"
          >
            Template
          </select-row>
          <div v-for="prop in properties" :key="prop.id">Hey</div>

          <input-row
            heading="Details | Sink"
            desc="Input a name for your Sink"
            v-model="form.sinkName"
          >
            {{ prop }}
          </input-row>
          <jet-input-error :message="form.errors.sinkName" class="mt-2" />
          {{ properties }}
        </div>

        <div class="w-6/12">
          <leaflet-map></leaflet-map>
        </div>
      </div>
      <div class="w-full my-5 px-16 flex justify-end">
        <jet-button :disabled="form.processing"> Create Source </jet-button>
      </div>
    </form>
  </app-layout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";

import { ref, watch } from "vue";

export default {
  components: {
    AppLayout,
    LeafletMap,
    InputRow,
    RadioRow,
    SelectRow,
    JetButton,
    JetInputError,
  },
  props: {
    templates: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const templateNames = props.templates.map((t) => t.name);
    const templateInfo = ref();
    const form = useForm({
      sourceTemplate: null,
    });

    watch(form.value, ({ sourceTemplate }) => {
      templateInfo.value = props.templates.find(
        (t) => t.name === sourceTemplate
      );
    });

    return {
      form,
      templateNames,
      templateInfo,
    };
  },
  computed: {
    properties() {
      return Object.assign([], this.templateInfo?.template_properties);
    },
  },
};

//  const form = useForm({
//       sourceName: "",
//       sourceType: "Metallurgy",
//       sourceSize: "Small",
//       sourceFluid: "Water",
//       sourceTemperature: "Cold",
//       sourcePressure: "Low",
//       sourceLocation: "Greece",
//     });

//     const sourceTypes = ["Metallurgy", "Timber"];
//     const sourceSizes = ["Small", "Medium", "Normal", "Big", "Very Big"];
//     const sourceFluids = ["Water", "Steam", "Petrolium"];
//     const sourceTemperatures = ["Cold", "Little Hot", "Very Hot"];
//     const sourcePressures = ["Low", "Normal", "High"];
//     const sourceLocations = ["Greece", "Portugal"];

//     const submit = () => {
//       console.log("submitting");
//       // TODO: Uncomment when backend is ready to accept data
//       // form.value.post(route('objects.locations.create'));
//     };
</script>

<style>
</style>


