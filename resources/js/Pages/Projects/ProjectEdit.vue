<template>
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Project
      </h2>
    </template>

    <div class="grid grid-cols-1 p-5 gap-y-10">
      <div>
        <div class="grid grid-cols-3">
          <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">Project</h3>
            <p class="mt-1 text-sm text-gray-600">Project Configuration</p>
          </div>
          <div class="col-span-2">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <input-row
                  heading="Project"
                  desc="The name of the Project"
                  v-model="form.name"
                >
                  Name
                </input-row>

                <input-row
                  desc="The description of the Project"
                  v-model="form.description"
                  class="mt-14"
                >
                  Description
                </input-row>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div>
        <div class="grid grid-cols-3">
          <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">Location</h3>
            <p class="mt-1 text-sm text-gray-600">Project Location Configuration</p>
          </div>
          <div class="col-span-2">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <select-row
                  heading="Project's Location"
                  desc="The location of this Project"
                  :options="locationSelects"
                  v-model="form.location_id"
                >
                  Location
                </select-row>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="w-full my-5 px-16 flex justify-end gap-x-10">
      <PrimaryButton @click="submit">
        Save
      </PrimaryButton>
    </div>
  </AppLayout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";
import SelectRow from "@/Components/SelectRow";
import PrimaryButton from "@/Components/PrimaryButton";
import SecondaryLinkButton from "@/Components/SecondaryLinkButton";

export default {
  components: {
    AppLayout,
    LeafletMap,
    InputRow,
    SelectRow,
    PrimaryButton,
    SecondaryLinkButton,
  },

  props: {
    project: {
      type: Object,
      required: true,
    },
    locations: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const form = useForm({
      name: null,
      description: null,
      location_id: null,
    });

    form.value.name = props.project.name;
    form.value.description = props.project.description;
    form.value.location_id = props.project.location_id;

    const locationSelects = props.locations.map((location) => ({
      key: location.id,
      value: location.name,
    }));

    function submit() {
      form.value.patch(route("projects.update", props.project.id));
    }

    return {
      form,
      locationSelects,
      submit,
    };
  },
};
</script>

<style scoped>
</style>


