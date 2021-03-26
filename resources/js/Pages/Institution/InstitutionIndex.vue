<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Institution
      </h2>
    </template>

    <div class="flex flex-col p-5 min-h-full gap-5">
      <div class="w-full max-h-96">
        <div class="flex flex-col max-h-full">
          <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
            <h3 class="text-lg leading-6 font-bold text-gray-900">
              Users
            </h3>
            <p class="text-sm text-gray-500">
              A list of all the Users that belong in this Institution
            </p>
          </div>
          <div class="max-h-64 overflow-y-auto overflow-x-auto shadow sm:rounded-b-md">
            <div v-if="users.length">
              <ul class="divide-y divide-gray-200 bg-white">
                <li
                  v-for="(user, index) in users"
                  :key="index"
                >
                  <inertia-link
                    href="#"
                    class="block hover:bg-gray-50"
                  >
                    <div class="flex items-center px-4 py-4 sm:px-6">
                      <div class="min-w-0 flex-1 flex items-center">
                        <div class="flex-shrink-0">
                          <img
                            class="h-12 w-12 rounded-full"
                            :src="user.profile_photo_url"
                            alt=""
                          >
                        </div>
                        <div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
                          <div>
                            <p class="text-sm font-medium text-indigo-600 truncate">
                              {{ user.name }}
                            </p>
                            <p class="mt-2 flex items-center text-sm text-gray-500">
                              <!-- Heroicon name: solid/mail -->
                              <svg
                                class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                                aria-hidden="true"
                              >
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                              </svg>
                              <span class="truncate">
                                {{ user.email }}
                              </span>
                            </p>
                          </div>
                          <div class="hidden md:block">
                            <div>
                              <p class="text-sm text-gray-900">
                                Member since
                                <time datetime="2020-01-07">
                                  {{ user.created_at }}
                                </time>
                              </p>
                              <pre>{{user.email_verified_at}}</pre>
                              <div v-if="user.email_verified_at != 'null'">
                                <p class="mt-2 flex items-center text-sm text-gray-500">
                                  <svg
                                    class="flex-shrink-0 mr-1.5 h-5 w-5 text-red-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                  >
                                    <path
                                      fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                      clip-rule="evenodd"
                                    />
                                  </svg>
                                  Email has not been verified
                                </p>
                              </div>
                              <div v-else>
                                <p class="mt-2 flex items-center text-sm text-gray-500">
                                  <!-- Heroicon name: solid/check-circle -->
                                  <svg
                                    class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                  >
                                    <path
                                      fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"
                                    />
                                  </svg>
                                  Email verified at {{ user.email_verified_at }}
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div>
                        <!-- Heroicon name: solid/chevron-right -->
                        <svg
                          class="h-5 w-5 text-gray-400"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                          aria-hidden="true"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </div>
                    </div>
                  </inertia-link>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="flex flex-col h-full gap-5">
        <div class="flex gap-5">
          <div class="flex flex-col gap-5 sm:w-full md:w-6/12">
            <div>
              <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
                <h3 class="text-lg leading-6 font-bold text-gray-900">
                  Sources
                </h3>
                <p class="text-sm text-gray-500">
                  A list of all the Sources that belong to this Institution
                </p>
              </div>
              <div class="min-w-full max-h-64 overflow-y-auto overflow-x-auto shadow sm:rounded-b-md">
                <div v-if="sources.length">
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
                          Template
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Category
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Location
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(source, index) in sources"
                        :key="index"
                        :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                      >
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          {{ source.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ source.template.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ source.template.category.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          <a
                            :class="{
                                'font-bold text-green-700 cursor-pointer hover:text-green-500':
                                    source.location,
                            }"
                            @click="centerAtLocation(source.location)"
                          >
                            {{ source.location ? source.location.name : "Not Assigned" }}
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div
                  v-else
                  class="flex items-center place-content-center bg-gray-50 h-64"
                >
                  <h1 class="text-2xl font-extrabold text-gray-300 uppercase">No Sources Found</h1>
                </div>

              </div>
            </div>

            <div>
              <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
                <h3 class="text-lg leading-6 font-bold text-gray-900">
                  Sinks
                </h3>
                <p class="text-sm text-gray-500">
                  A list of all the Sinks that belong to this Institution
                </p>
              </div>
              <div class="min-w-full max-h-64 overflow-y-auto overflow-x-auto shadow sm:rounded-b-md">
                <div v-if="sinks.length">
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
                          Template
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Category
                        </th>
                        <th
                          scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        >
                          Location
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(sink, index) in sinks"
                        :key="index"
                        :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
                        class="hover:bg-gray-300 hover:text-white pt-3 pb-3 rounded"
                      >
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                          {{ sink.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ sink.template.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          {{ sink.template.category.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                          <a
                            :class="{
                            'font-bold text-green-700 cursor-pointer hover:text-green-500':
                                sink.location,
                            }"
                            @click="centerAtLocation(sink.location)"
                          >

                            {{ sink.location ? sink.location.name : "Not Assigned" }}
                          </a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div
                  v-else
                  class="flex items-center place-content-center bg-gray-50 h-64"
                >
                  <h1 class="text-2xl font-extrabold text-gray-300 uppercase">No Sinks Found</h1>
                </div>
              </div>
            </div>
          </div>

          <div class="sm:w-full md:w-6/12">
            <leaflet-map
              :markers="markers"
              ref="map"
            ></leaflet-map>
          </div>
        </div>
      </div>

    </div>
  </app-layout>
</template>

<script>
  import { ref } from 'vue';

  import useUniqueLocations from "@/Composables/useUniqueLocations";

  import AppLayout from "@/Layouts/AppLayout";
  import LeafletMap from "@/Components/LeafletMap";
  import DetailIcon from "@/Icons/DetailIcon.vue";

  export default {
    components: {
      AppLayout,
      LeafletMap,
      DetailIcon
    },

    props: {
      users: {
        type: Array,
        required: true
      },
      sources: {
        type: Array,
        required: true
      },
      sinks: {
        type: Array,
        required: true
      },
    },

    setup(props) {
      const map = ref(null);
      const markers = ref([]);
      const locations = props.sources.concat(props.sinks);

      console.log(props.users);
      const uniqueLocations = useUniqueLocations(locations);

      for (const source of uniqueLocations.value) {
        markers.value.push(source.data)
      }

      function centerAtLocation(location) {
        const locationMarker = markers.value.find((m) => m.id === location.geo_object.id);
        map.value.centerAtLocation(locationMarker);
      }

      return {
        map,
        markers,
        centerAtLocation
      };
    }
  };
</script>
