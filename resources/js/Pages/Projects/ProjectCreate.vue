<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Projects
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Project</h1>
    </div>
    <div class="flex p-5 h-screen md:h-content gap-2">
      <div class="w-6/12 md:overflow-y-auto md:pr-4">
        <input-row
          desc="Enter a name for the Project"
          v-model="form.name"
        >
          Name
        </input-row>
        <input-row
          desc=""
          v-model="form.description"
          class="mt-5"
        >
          Description
        </input-row>
        <select-row
          class="my-5"
          desc="Select a Location for your Project"
          :options="locationSelects"
          v-model="form.location_id"
        >
          Location
        </select-row>
        <hr class="my-5" />
      </div>

      <div class="w-6/12">
        <leaflet-map></leaflet-map>
      </div>
    </div>
    <div class="w-full my-5 px-16 py-10 flex justify-end">
      <primary-button
        :disabled="form.processing"
        @click="submit()"
      >
        Create Project
      </primary-button>
    </div>
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
  import PrimaryButton from "@/Components/PrimaryButton";
  import JetInputError from "@/Jetstream/InputError";

  export default {
    components: {
      AppLayout,
      LeafletMap,
      InputRow,
      RadioRow,
      SelectRow,
      JetButton,
      PrimaryButton,
      JetInputError,
    },

    props: {
      locations: {
        type: Array,
        required: true,
      },
      projects: {
        type: Array,
        required: true,
      },
    },

    setup(props) {
      const form = useForm({
        name: "",
        description: "",
        location_id: "",
      });

      const locationSelects = props.locations.map((t) => ({
        key: t.id,
        value: t.name,
      }));

      const submit = () => {
        form.value.post(route("projects.store"));
      };

      return {
        form,
        submit,
        locationSelects,
      };
    },
  };
</script>
