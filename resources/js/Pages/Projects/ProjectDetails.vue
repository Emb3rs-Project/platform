<template>
  <app-layout>
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
                  v-model="project.name"
                  :disabled="true"
                >
                  Name
                </input-row>

                <input-row
                  desc="The description of the Project"
                  v-model="project.description"
                  :disabled="true"
                  class="mt-14"
                >
                  Description
                </input-row>

                <div class="grid grid-cols-2 items-center mt-10">
                  <div>
                    <p class="text-sm text-gray-500">
                      The creation and update dates
                    </p>
                  </div>
                  <div class="flex">
                    <date-input
                      desc="Creation Date"
                      v-model="project.created_at"
                      :disabled="true"
                    >
                      Created at
                    </date-input>

                    <date-input
                      desc="Update Date"
                      v-model="project.updated_at"
                      :disabled="true"
                    >
                      Updated At
                    </date-input>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div v-if="project.location">
        <div class="grid grid-cols-3">
          <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">Location</h3>
            <p class="mt-1 text-sm text-gray-600">Project Location Configuration</p>
          </div>
          <div class="col-span-2">
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
              <div class="px-4 py-5 sm:p-6">
                <input-row
                  heading="Project's Location"
                  desc="The name of the Location of this Project"
                  v-model="project.location.name"
                  :disabled="true"
                >
                  Name
                </input-row>

                <leaflet-map
                  class="mt-5"
                  :markers="marker"
                  ref="map"
                ></leaflet-map>
                <div class="flex justify-end mt-5">
                  <secondary-button @click="centerAtLocation">
                    Center at Location
                  </secondary-button>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div>
        <div class="grid grid-cols-3">
          <div>
            <h3 class="text-lg font-medium leading-6 text-gray-900">Simulation(s)</h3>
            <p class="mt-1 text-sm text-gray-600">Project Simulation Configuration(s)</p>
          </div>
          <div class="col-span-2">
            <div class="flex flex-col max-h-full">
              <div class="overflow-y-auto overflow-x-auto shadow sm:rounded-b-md">
                <div v-if="simulations.length">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th
                          scope="col"
                          class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          ID
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Status
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Target
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Simulation Type
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Simulation Type Description
                        </th>
                        <th
                          scope="col"
                          class="relative px-6 py-3"
                        >
                          <span class="sr-only">Edit</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(simulation, index) in simulations"
                        :key="index"
                        :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                      >
                        <td class="px-6 py-4 text-right whitespace-nowrap text-sm font-medium text-gray-900">
                          {{ simulation.id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ simulation.status }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ simulation.targetData }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ simulation.simulation_type.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ simulation.simulation_type.description ? simulation.simulation_type.description : "Not defined" }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                          <inertia-link :href="route('projects.simulations.show', [project.id, simulation.id])">
                            <detail-icon class="text-gray-500 font-medium text-sm w-5"></detail-icon>
                          </inertia-link>
                          <inertia-link :href="route('projects.simulations.edit', [project.id, simulation.id])">
                            <edit-icon class="text-gray-500 font-medium text-sm w-5"></edit-icon>
                          </inertia-link>
                          <button
                            class="focus:outline-none"
                            @click="onSimulationDelete(project, simulation)"
                          >
                            <trash-icon class="text-red-500 font-medium text-sm w-5"></trash-icon>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div
                  v-else
                  class="flex items-center place-content-center bg-gray-50 h-20 md:h-64"
                >
                  <h1 class="text-2xl font-extrabold text-gray-300 uppercase">
                    No Simulations Found
                  </h1>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="w-full my-5 px-16 flex justify-end gap-x-10">
      <secondary-link-button
        :path="'projects.simulations.create'"
        :parameter="project.id"
      >
        Create simulation
      </secondary-link-button>
      <primary-link-button
        :path="'projects.edit'"
        :parameter="project.id"
      >
        Edit Project
      </primary-link-button>
    </div>
  </app-layout>
</template>

<script>
  import { ref, onMounted } from "vue";
  import { Inertia } from '@inertiajs/inertia'

  import AppLayout from "@/Layouts/AppLayout";
  import LeafletMap from "@/Components/LeafletMap";
  import InputRow from "@/Components/InputRow";
  import DateInput from "@/Components/DateInput";
  import PrimaryLinkButton from "@/Components/PrimaryLinkButton";
  import SecondaryButton from "@/Components/SecondaryButton";
  import SecondaryLinkButton from "@/Components/SecondaryLinkButton";
  import TrashIcon from "@/Icons/TrashIcon.vue";
  import EditIcon from "@/Icons/EditIcon.vue";
  import DetailIcon from "@/Icons/DetailIcon.vue";


  export default {
    components: {
      AppLayout,
      LeafletMap,
      InputRow,
      DateInput,
      PrimaryLinkButton,
      SecondaryButton,
      SecondaryLinkButton,
      TrashIcon,
      EditIcon,
      DetailIcon,
    },

    props: {
      project: {
        type: Object,
        required: true,
      },
      simulations: {
        type: Array,
        required: true,
      },
    },

    setup(props) {
      const map = ref(null);
      const marker = ref([]);

      if (props.project.location.geo_object) {
        marker.value.push(props.project.location.geo_object);
      }

      onMounted(() => {
        centerAtLocation();
      })

      function centerAtLocation() {
        map.value.centerAtLocation(marker.value[0]);
      }

      function onSimulationDelete(project, simulation) {
        // we need to diplay a modal here
        Inertia.delete(route("projects.simulations.destroy", [project.id, simulation.id]));
      }

      return {
        map,
        marker,
        centerAtLocation,
        onSimulationDelete
      }
    }
  };
</script>
