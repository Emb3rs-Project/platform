<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Institution
      </h2>
    </template>
    <div class="flex p-5">
      <div class="w-8/12">
        <leaflet-map></leaflet-map>
      </div>
      <div class="w-4/12 text-gray-700">
        <div
          class="h-80 mb-5 p-3 overflow-y-scroll border border-gray-200 shadow"
        >
          <h1 class="leading-5 font-bold mb-3">Users</h1>
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
                  Email
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="user in users" :key="user.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ user.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ user.username }}@somewhere.com
                </td>
              </tr>

              <!-- More items... -->
            </tbody>
          </table>
        </div>
        <div
          class="h-80 my-5 p-3 overflow-y-scroll border border-gray-200 shadow"
        >
          <h1 class="leading-5 font-bold mb-3">Accounts</h1>
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
                  Email
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="user in users" :key="user.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ user.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ user.username }}@somewhere.com
                </td>
              </tr>

              <!-- More items... -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="container mx-auto"></div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";

export default {
  components: {
    AppLayout,
    LeafletMap,
  },
  data() {
    return {
      users: [
        {
          id: "1",
          name: "George Mantellos",
          username: "geocfu",
        },
        {
          id: "2",
          name: "David Fernandes",
          username: "davidfs",
        },
        {
          id: "3",
          name: "Luis Miguel Campos",
          username: "luismc",
        },
        {
          id: "4",
          name: "Carlos Nuno Marques",
          username: "carlosnm",
        },
      ],
      teams: [
        {
          name: "George's team",
        },
      ],
    };
  },
};
</script>

<style scoped>
</style>
