<template>
  <div class="flex flex-col min-h-screen bg-gray-100">
    <header class="flex-none bg-white shadow">
      <!-- before was: max-w-7xl -->
      <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex px-2 lg:px-0">
            <div class="flex-shrink-0 flex items-center">
              <inertia-link :href="route('new-layout.index')">
                <application-logo class="h-12" />
              </inertia-link>
            </div>
            <!-- <nav
              aria-label="Global"
              class="hidden lg:ml-6 lg:flex lg:items-center lg:space-x-4"
            >
              <a
                href="#"
                class="px-3 py-2 text-gray-900 text-sm font-medium"
              >
                Dashboard
              </a>

              <a
                href="#"
                class="px-3 py-2 text-gray-900 text-sm font-medium"
              >
                Jobs
              </a>

              <a
                href="#"
                class="px-3 py-2 text-gray-900 text-sm font-medium"
              >
                Applicants
              </a>

              <a
                href="#"
                class="px-3 py-2 text-gray-900 text-sm font-medium"
              >
                Company
              </a>
            </nav> -->
          </div>
          <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
            <div class="max-w-lg w-full lg:max-w-xs">
              <label
                for="search"
                class="sr-only"
              >
                Search
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <!-- Heroicon name: solid/search -->
                  <svg
                    class="h-5 w-5 text-gray-400"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"
                    />
                  </svg>
                </div>
                <input
                  id="search"
                  name="search"
                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white shadow-sm placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-600 focus:border-blue-600 sm:text-sm"
                  placeholder="Search"
                  type="search"
                >
              </div>
            </div>
          </div>
          <div class="flex items-center lg:hidden">
            <!-- Mobile menu button -->
            <button
              type="button"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
              aria-expanded="false"
            >
              <span class="sr-only">Open main menu</span>
              <!-- Heroicon name: outline/menu -->
              <svg
                class="block h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
            </button>
          </div>

          <!-- Mobile menu, show/hide based on mobile menu state. -->
          <div class="lg:hidden">
            <!--
            Mobile menu overlay, show/hide based on mobile menu state.

            Entering: "duration-150 ease-out"
              From: "opacity-0"
              To: "opacity-100"
            Leaving: "duration-150 ease-in"
              From: "opacity-100"
              To: "opacity-0"
          -->
            <div
              class="z-20 fixed inset-0 bg-black bg-opacity-25"
              aria-hidden="true"
            ></div>

            <!--
            Mobile menu, show/hide based on mobile menu state.

            Entering: "duration-150 ease-out"
              From: "opacity-0 scale-95"
              To: "opacity-100 scale-100"
            Leaving: "duration-150 ease-in"
              From: "opacity-100 scale-100"
              To: "opacity-0 scale-95"
          -->
            <div class="z-30 absolute top-0 right-0 max-w-none w-full p-2 transition transform origin-top">
              <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y divide-gray-200">
                <div class="pt-3 pb-2">
                  <div class="flex items-center justify-between px-4">
                    <div>
                      <img
                        class="h-8 w-auto"
                        src="https://tailwindui.com/img/logos/workflow-mark-blue-600.svg"
                        alt="Workflow"
                      >
                    </div>
                    <div class="-mr-2">
                      <button
                        type="button"
                        class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                      >
                        <span class="sr-only">Close menu</span>
                        <!-- Heroicon name: outline/x -->
                        <svg
                          class="h-6 w-6"
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                          aria-hidden="true"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                          />
                        </svg>
                      </button>
                    </div>
                  </div>
                  <div class="mt-3 px-2 space-y-1">
                    <a
                      href="#"
                      class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                    >Dashboard</a>

                    <a
                      href="#"
                      class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                    >Jobs</a>

                    <a
                      href="#"
                      class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                    >Applicants</a>

                    <a
                      href="#"
                      class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                    >Company</a>
                  </div>
                </div>
                <div class="pt-4 pb-2">
                  <div class="flex items-center px-5">
                    <div class="flex-shrink-0">
                      <img
                        class="h-10 w-10 rounded-full"
                        src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80"
                        alt=""
                      >
                    </div>
                    <div class="ml-3">
                      <div class="text-base font-medium text-gray-800">Whitney Francis</div>
                      <div class="text-sm font-medium text-gray-500">whitney@example.com</div>
                    </div>
                    <button class="ml-auto flex-shrink-0 bg-white p-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                      <span class="sr-only">View notifications</span>
                      <!-- Heroicon name: outline/bell -->
                      <svg
                        class="h-6 w-6"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        aria-hidden="true"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                        />
                      </svg>
                    </button>
                  </div>
                  <div class="mt-3 px-2 space-y-1">
                    <a
                      href="#"
                      class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                    >Your Profile</a>

                    <a
                      href="#"
                      class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                    >Settings</a>

                    <a
                      href="#"
                      class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800"
                    >Sign out</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="hidden lg:ml-4 lg:flex lg:items-center">
            <!-- Institution dropdown -->
            <div class="mr-4 relative flex-shrink-0">
              <dropdown>
                <template #trigger>
                  <button
                    type="button"
                    class="inline-flex justify-center w-full rounded-md shadow-sm px-4 py-2 bg-gray-100 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    id="options-menu"
                    aria-expanded="true"
                    aria-haspopup="true"
                  >
                    {{ user.current_team.name }}
                    <!-- Heroicon name: solid/chevron-down -->
                    <svg
                      class="-mr-1 ml-2 h-5 w-5"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </button>
                </template>
                <template #content>
                  <div
                    class="transform z-1000 origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="options-menu"
                  >
                    <div
                      class="px-4 py-3"
                      role="none"
                    >
                      <p
                        class="text-sm"
                        role="none"
                      >
                        Signed in as
                      </p>
                      <p
                        class="text-sm font-medium text-gray-900 truncate"
                        role="none"
                      >
                        tom@example.com
                      </p>
                    </div>
                    <div
                      class="py-1"
                      role="none"
                    >
                      <a
                        href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                      >
                        Account settings
                      </a>
                      <a
                        href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                      >
                        Support
                      </a>
                      <a
                        href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                      >
                        License
                      </a>
                    </div>
                    <div
                      class="py-1"
                      role="none"
                    >
                      <form
                        method="POST"
                        action="#"
                        role="none"
                      >
                        <button
                          type="submit"
                          class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                          role="menuitem"
                        >
                          Sign out
                        </button>
                      </form>
                    </div>
                  </div>
                </template>
              </dropdown>
            </div>

            <button
              type="button"
              class="flex-shrink-0 bg-white p-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              <span class="sr-only">View notifications</span>
              <!-- Heroicon name: outline/bell -->
              <svg
                class="h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                />
              </svg>
            </button>

            <!-- Profile dropdown -->
            <div class="ml-4 relative flex-shrink-0">
              <dropdown>
                <template #trigger>
                  <button
                    type="button"
                    class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    id="user-menu"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <span class="sr-only">Open user menu</span>
                    <img
                      class="h-8 w-8 rounded-full"
                      :src="user.profile_photo_url"
                      alt=""
                    >
                  </button>
                </template>
                <template #content>
                  <div
                    class="transition-all transform z-1000 origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="user-menu"
                  >
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                      role="menuitem"
                    >
                      Your Profile
                    </a>

                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                      role="menuitem"
                    >
                      Settings
                    </a>

                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                      role="menuitem"
                    >
                      Sign out
                    </a>
                  </div>

                </template>
              </dropdown>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main class="flex-grow">
      <slide-over />
    </main>
  </div>
</template>

<script>
  import { ref, onMounted, onUnmounted } from "vue";

  import ApplicationLogo from "@/Components/NewLayout/ApplicationLogo.vue";
  import LeafletMap from "@/Components/LeafletMap.vue";
  import Dropdown from "@/Components/NewLayout/Dropdown.vue";
  import SlideOver from '../../Components/NewLayout/SlideOver.vue';

  export default {
    components: { ApplicationLogo, Dropdown, LeafletMap, SlideOver, Dropdown },

    props: {
      jetstream: {
        type: Object,
        required: true,
      },
      user: {
        type: Object,
        required: true,
      },
      all_teams: {
        type: Object,
        required: true,
      },
      errorBags: {
        type: Array,
        required: true
      },
      errors: {
        type: Object,
        required: true
      }
    },

    setup(props, context) {
      const showingProfileDropdown = ref(false);
      const markers = ref([]);

      const closeOnEscape = (e) => {
        if (showingProfileDropdown.value && e.keyCode === 27) {
          showingProfileDropdown.value = false;
        }
      };

      onMounted(() => document.addEventListener("keydown", closeOnEscape));
      onUnmounted(() => document.removeEventListener("keydown", closeOnEscape));

      return {
        showingProfileDropdown,
        markers
      }
    },

    methods: {
      switchToTeam(team) {
        this.$inertia.put(
          route("current-team.update"),
          {
            team_id: team.id,
          },
          {
            preserveState: false,
          }
        );
      },

      logout() {
        this.$inertia.post(route("logout"));
      },
    },
  };
</script>
