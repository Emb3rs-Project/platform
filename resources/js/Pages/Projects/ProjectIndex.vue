<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Projects
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">Projects</h1>
    </div>
    <div class="flex p-5 h-screen md:h-content gap-2">
      <div class="w-6/12 md:overflow-y-auto">
        <!-- <div class="flex flex-col"> -->
        <div class="overflow-x bg-red-500">
          <!-- <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"> -->
          <!-- <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"> -->
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  ID
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Name
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Description
                </th>
                <th
                  scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Location
                </th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="project of projects"
                :key="project.id"
                :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
              >
                <!-- bg-white is on odd rows -->
                <!-- bg-gray is on even rows -->
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                >
                  {{ project.id }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                >
                  {{ project.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ project.description }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{
                    project.location ? project.location?.name : "Not Assigned"
                  }}
                </td>
                <td
                  class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2"
                >
                  <a href="#">
                    <edit-icon
                      class="text-gray-600 font-medium text-sm w-5"
                    ></edit-icon>
                  </a>
                  <a href="#" @click="onDelete(project)">
                    <trash-icon
                      class="text-gray-600 font-medium text-sm w-5"
                    ></trash-icon>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- </div> -->
          <!-- </div> -->
        </div>
        <!-- </div> -->
      </div>
      <div class="w-6/12">
        <leaflet-map></leaflet-map>
      </div>
    </div>
    <div class="w-full my-5 px-16 flex justify-end">
      <jet-link-button path="projects.create">
        Create New Project
      </jet-link-button>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import JetLinkButton from "@/Jetstream/LinkButton";
import TrashIcon from "@/Icons/TrashIcon.vue";
import EditIcon from "@/Icons/EditIcon.vue";

export default {
  components: {
    AppLayout,
    LeafletMap,
    JetLinkButton,
    TrashIcon,
    EditIcon,
  },

  props: ["projects"],

  setup(props, context) {
    console.log(props.projects);
  },
  methods: {
    onDelete(project) {
      this.$inertia.delete(route("projects.destroy", project.id));
    },
  },
};
</script>

<style>
</style>
