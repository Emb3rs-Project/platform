<template>
  <app-layout>
    <div class="bg-white">
      <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
          <div class="mt-12 lg:mt-0 lg:col-span-3">
            <div class="flex flex-col">
              <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                  <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
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
                          v-for="(project, projectIdx) in projects"
                          :key="project.id"
                          :class="
                            projectIdx % 2 === 0 ? 'bg-white' : 'bg-gray-50'
                          "
                        >
                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ project.name }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ project.description }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ project.location.name }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a
                              :href="
                                route('projects.simulations.create', project.id)
                              "
                              class="text-indigo-600 hover:text-indigo-900"
                            >
                              Create Simulation
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <primary-link-button
            class="w-48"
            :path="'projects.create'"
          >
            Create a Project
          </primary-link-button>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import PrimaryLinkButton from "../../Components/PrimaryLinkButton.vue";

export default {
  components: {
    AppLayout,
    PrimaryLinkButton,
  },
  props: {
    projects: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    console.log(props.projects);
    const people = [
      {
        name: "Jane Cooper",
        title: "Regional Paradigm Technician",
        role: "Admin",
        email: "jane.cooper@example.com",
      },
      {
        name: "Cody Fisher",
        title: "Product Directives Officer",
        role: "Owner",
        email: "cody.fisher@example.com",
      },
      // More people...
    ];

    return {
      people,
    };
  },
};
</script>

<style>
</style>
