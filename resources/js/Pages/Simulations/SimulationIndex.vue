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
    <div class="flex p-5">
      <div class="w-6/12">
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
                Status
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only"></span>
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="i in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]" :key="i">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                Sink Name {{ i }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ i % 3 ? "Completed" : "Pending" }}
              </td>
              <td
                class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
              >
                <a
                  href="#"
                  class="text-indigo-600 hover:text-indigo-900 text-gray-700"
                >
                  <i class="fas fa-ellipsis-v"></i>
                </a>
              </td>
            </tr>

            <!-- More items... -->
          </tbody>
        </table>
      </div>

      <div class="w-6/12">
        <leaflet-map></leaflet-map>
      </div>
    </div>
    <div class="w-full my-5 px-16 flex justify-end">
      <jet-link-button path="objects.sources.create">
        Create New Project
      </jet-link-button>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import JetLinkButton from "@/Jetstream/LinkButton";

export default {
  components: {
    AppLayout,
    LeafletMap,
    JetLinkButton,
  },
};
</script>

<style>
</style>
