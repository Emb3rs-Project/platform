<template>
  <div class="h-screen flex overflow-hidden bg-white">
    <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state.-->
    <div
      class="fixed inset-0 flex z-40 lg:hidden"
      role="dialog"
      aria-modal="true"
    >
      <!-- Off-canvas menu overlay, show/hide based on off-canvas menu state. -->
      <transition
        enter-active-class="transition-opacity ease-linear duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity ease-linear duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          class="fixed inset-0 bg-gray-600 bg-opacity-75"
          aria-hidden="true"
        ></div>
      </transition>

      <!--
        Off-canvas menu, show/hide based on off-canvas menu state.
        -->
      <transition
        enter-active-class="transition ease-in-out duration-300 transform"
        enter-from-class="-translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transition ease-in-out duration-300 transform"
        leave-from-class="translate-x-0"
        leave-to-class="-translate-x-full"
      >
        <div
          class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-white"
        >
          <!-- Close button, show/hide based on off-canvas menu state. -->
          <transition
            enter-active-class="ease-in-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in-out duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
          >
            <div class="absolute top-0 right-0 -mr-12 pt-2">
              <button
                class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
              >
                <span class="sr-only">Close sidebar</span>
                <!-- Heroicon name: outline/x -->
                <svg
                  class="h-6 w-6 text-white"
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
          </transition>

          <div class="flex-shrink-0 flex items-center px-4">
            <img
              class="h-8 w-auto"
              src="https://tailwindui.com/img/logos/workflow-logo-purple-500-mark-gray-700-text.svg"
              alt="Workflow"
            />
          </div>
          <div class="mt-5 flex-1 h-0 overflow-y-auto">
            <nav class="px-2">
              <div class="space-y-1">
                <!-- Current: "bg-gray-100 text-gray-900", Default: "text-gray-600 hover:text-gray-900 hover:bg-gray-50" -->
                <a
                  href="#"
                  class="bg-gray-100 text-gray-900 group flex items-center px-2 py-2 text-base leading-5 font-medium rounded-md"
                  aria-current="page"
                >
                  <!--
                Heroicon name: outline/home

                Current: "text-gray-500", Default: "text-gray-400 group-hover:text-gray-500"
              -->
                  <svg
                    class="text-gray-500 mr-3 h-6 w-6"
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
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                    />
                  </svg>
                  Home
                </a>

                <a
                  href="#"
                  class="text-gray-600 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-2 py-2 text-base leading-5 font-medium rounded-md"
                >
                  <!-- Heroicon name: outline/view-list -->
                  <svg
                    class="text-gray-400 group-hover:text-gray-500 mr-3 h-6 w-6"
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
                      d="M4 6h16M4 10h16M4 14h16M4 18h16"
                    />
                  </svg>
                  My tasks
                </a>

                <a
                  href="#"
                  class="text-gray-600 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-2 py-2 text-base leading-5 font-medium rounded-md"
                >
                  <!-- Heroicon name: outline/clock -->
                  <svg
                    class="text-gray-400 group-hover:text-gray-500 mr-3 h-6 w-6"
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
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                    />
                  </svg>
                  Recent
                </a>
              </div>
              <div class="mt-8">
                <h3
                  class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                  id="teams-headline"
                >
                  Teams
                </h3>
                <div
                  class="mt-1 space-y-1"
                  role="group"
                  aria-labelledby="teams-headline"
                >
                  <a
                    href="#"
                    class="group flex items-center px-3 py-2 text-base leading-5 font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50"
                  >
                    <span
                      class="w-2.5 h-2.5 mr-4 bg-indigo-500 rounded-full"
                      aria-hidden="true"
                    ></span>
                    <span class="truncate"> Engineering </span>
                  </a>

                  <a
                    href="#"
                    class="group flex items-center px-3 py-2 text-base leading-5 font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50"
                  >
                    <span
                      class="w-2.5 h-2.5 mr-4 bg-green-500 rounded-full"
                      aria-hidden="true"
                    ></span>
                    <span class="truncate"> Human Resources </span>
                  </a>

                  <a
                    href="#"
                    class="group flex items-center px-3 py-2 text-base leading-5 font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50"
                  >
                    <span
                      class="w-2.5 h-2.5 mr-4 bg-yellow-500 rounded-full"
                      aria-hidden="true"
                    ></span>
                    <span class="truncate"> Customer Success </span>
                  </a>
                </div>
              </div>
            </nav>
          </div>
        </div>
      </transition>

      <div class="flex-shrink-0 w-14" aria-hidden="true">
        <!-- Dummy element to force sidebar to shrink to fit close icon -->
      </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:flex lg:flex-shrink-0">
      <div
        class="flex flex-col w-64 border-r border-gray-200 pt-5 pb-4 bg-gray-100"
      >
        <div class="flex items-center flex-shrink-0 px-6">
          <inertia-link :href="route('dashboard.index')">
            <application-logo class="h-auto w-auto" />
          </inertia-link>
        </div>
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="h-0 flex-1 flex flex-col overflow-y-auto">
          <!-- User account dropdown -->
          <div class="px-3 mt-6 relative inline-block text-left">
            <dropdown>
              <template #trigger>
                <button
                  type="button"
                  class="group w-full bg-gray-100 rounded-md px-3.5 py-2 text-sm text-left font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-purple-500"
                  id="options-menu"
                  aria-expanded="false"
                  aria-haspopup="true"
                >
                  <span class="flex w-full justify-between items-center">
                    <span
                      class="flex min-w-0 items-center justify-between space-x-3"
                    >
                      <span class="inline-block relative">
                        <img
                          class="h-10 w-10 rounded-full flex-shrink-0"
                          :src="user.profile_photo_url"
                          alt=""
                        />
                        <span
                          v-show="notification"
                          class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-red-400"
                        ></span>
                      </span>
                      <span class="flex-1 flex flex-col min-w-0">
                        <span
                          class="text-gray-900 text-sm font-medium truncate"
                        >
                          {{ user.name }}
                        </span>
                        <span class="text-gray-500 text-sm truncate">
                          {{ user.email }}
                        </span>
                      </span>
                    </span>
                    <!-- Heroicon name: solid/selector -->
                    <svg
                      class="flex-shrink-0 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                      fill="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </span>
                </button>
              </template>
              <template #content>
                <div
                  class="z-50 origin-top absolute right-0 left-0 mt-1 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none"
                  role="menu"
                  aria-orientation="vertical"
                  aria-labelledby="options-menu"
                >
                  <div class="py-1" role="none">
                    <inertia-link
                      :href="route('profile.show')"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                    >
                      Your Profile
                    </inertia-link>

                    <inertia-link
                      :href="'#'"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                    >
                      Settings
                    </inertia-link>
                    <inertia-link
                      :href="'#'"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                    >
                      Notifications
                      <span
                        v-show="notification"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                      >
                        New
                      </span>
                    </inertia-link>
                  </div>
                  <div class="py-1" role="none">
                    <form @submit.prevent="logout" role="none">
                      <button
                        type="submit"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                      >
                        Logout
                      </button>
                    </form>
                  </div>
                </div>
              </template>
            </dropdown>
          </div>
          <!-- Sidebar Search TODO: make it a component -->
          <div class="px-3 mt-5 z-0">
            <label for="search" class="sr-only"> Search </label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                aria-hidden="true"
              >
                <!-- Heroicon name: solid/search -->
                <svg
                  class="mr-3 h-4 w-4 text-gray-400"
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
                type="text"
                name="search"
                id="search"
                class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-9 sm:text-sm border-gray-300 rounded-md"
                placeholder="Search"
              />
            </div>
          </div>

          <!-- Navigation -->
          <nav class="px-3 mt-6">
            <div class="space-y-1">
              <inertia-link
                :href="route('dashboard.index')"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                :class="
                  route().current('dashboard.index')
                    ? 'bg-gray-200 text-gray-900'
                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'
                "
                aria-current="page"
              >
                <!-- Heroicon name: outline/home -->
                <svg
                  class="mr-3 h-6 w-6"
                  :class="
                    route().current('dashboard.index')
                      ? 'text-gray-500'
                      : 'text-gray-400 group-hover:text-gray-500'
                  "
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
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                  />
                </svg>
                Dashboard
              </inertia-link>

              <inertia-link
                :href="route('objects.index')"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                :class="
                  route().current('objects.index')
                    ? 'bg-gray-200 text-gray-900'
                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'
                "
              >
                <!-- Heroicon name: outline/view-list -->
                <svg
                  class="mr-3 h-6 w-6"
                  :class="
                    route().current('objects.index')
                      ? 'text-gray-500'
                      : 'text-gray-400 group-hover:text-gray-500'
                  "
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                  />
                </svg>
                Objects
              </inertia-link>

              <inertia-link
                :href="'#'"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                :class="
                  route().current('projects.index')
                    ? 'bg-gray-200 text-gray-900'
                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'
                "
              >
                <!-- Heroicon name: outline/view-list -->
                <svg
                  class="mr-3 h-6 w-6"
                  :class="
                    route().current('projects.index')
                      ? 'text-gray-500'
                      : 'text-gray-400 group-hover:text-gray-500'
                  "
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
                    d="M4 6h16M4 10h16M4 14h16M4 18h16"
                  />
                </svg>
                Projects
              </inertia-link>

              <inertia-link
                :href="route('challenge.index')"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                :class="
                  route().current('challenge.index')
                    ? 'bg-gray-200 text-gray-900'
                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'
                "
              >
                <!-- Heroicon name: outline/clock -->
                <svg
                  class="mr-3 h-6 w-6"
                  :class="
                    route().current('challenge.index')
                      ? 'text-gray-500'
                      : 'text-gray-400 group-hover:text-gray-500'
                  "
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
                Challenge
              </inertia-link>

              <inertia-link
                :href="route('help.index')"
                class="group flex items-center px-2 py-2 text-sm font-medium rounded-md"
                :class="
                  route().current('help.index')
                    ? 'bg-gray-200 text-gray-900'
                    : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'
                "
              >
                <svg
                  class="mr-3 h-6 w-6"
                  :class="
                    route().current('help.index')
                      ? 'text-gray-500'
                      : 'text-gray-400 group-hover:text-gray-500'
                  "
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
                  />
                </svg>
                Help
              </inertia-link>
            </div>

            <div class="mt-8">
              <!-- Secondary navigation -->
              <div class="relative">
                <div class="relative flex items-center justify-between">
                  <span
                    class="px-3 text-xs bg-gray-100 font-semibold text-gray-500 uppercase tracking-wider"
                  >
                    Institutions
                  </span>
                  <dropdown>
                    <template #trigger>
                      <button
                        type="button"
                        class="bg-gray-100 rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500"
                        id="options-menu"
                        aria-expanded="true"
                        aria-haspopup="true"
                      >
                        <span class="sr-only">Open options</span>
                        <!-- Heroicon name: solid/dots-vertical -->
                        <svg
                          class="h-5 w-5 mr-2"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                          aria-hidden="true"
                        >
                          <path
                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"
                          />
                        </svg>
                      </button>
                    </template>
                    <template #content>
                      <div
                        class="z-50 origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="options-menu"
                      >
                        <div class="py-1" role="none">
                          <inertia-link
                            :href="route('teams.create')"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                          >
                            Create Institution
                          </inertia-link>
                        </div>
                      </div>
                    </template>
                  </dropdown>
                </div>
              </div>
              <div
                class="mt-1 space-y-1"
                role="group"
                aria-labelledby="teams-headline"
              >
                <div
                  v-for="institution in user.all_teams"
                  :key="institution.id"
                >
                  <form @submit.prevent="switchToTeam(institution)">
                    <div class="">
                      <div>
                        <button
                          type="submit"
                          class="inline-flex justify-between content-center w-full px-3 py-2.5 text-sm leading-4 font-medium text-gray-700 rounded-md hover:text-gray-900 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 h-9 pr-5"
                          :class="
                            institution.id === user.current_team_id
                              ? 'text-yellow-500 bg-gray-200'
                              : 'text-gray-700'
                          "
                        >
                          {{ institution.name }}
                          <svg
                            class="-mr-2.5 h-4 w-4 text-gray-400 hover:text-gray-600"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
    <!-- Main column -->
    <div class="flex flex-col w-0 flex-1 overflow-hidden">
      <!-- Search header and sidebar on md and down -->
      <div
        class="relative z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200 lg:hidden"
      >
        <!-- Sidebar toggle, controls the 'sidebarOpen' sidebar state. -->
        <button
          class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500 lg:hidden"
        >
          <span class="sr-only">Open sidebar</span>
          <!-- Heroicon name: outline/menu-alt-1 -->
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
              d="M4 6h16M4 12h8m-8 6h16"
            />
          </svg>
        </button>

        <div class="flex-1 flex justify-between px-4 sm:px-6 lg:px-8">
          <!-- Search on MD and down -->
          <div class="flex-1 flex">
            <form class="w-full flex md:ml-0" action="#" method="GET">
              <label for="search_field" class="sr-only"> Search </label>
              <div
                class="relative w-full text-gray-400 focus-within:text-gray-600"
              >
                <div
                  class="absolute inset-y-0 left-0 flex items-center pointer-events-none"
                >
                  <!-- Heroicon name: solid/search -->
                  <svg
                    class="h-5 w-5"
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
                  id="search_field"
                  name="search_field"
                  class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-transparent focus:placeholder-gray-400 sm:text-sm"
                  placeholder="Search"
                  type="search"
                />
              </div>
            </form>
          </div>

          <div class="flex items-center">
            <!-- Profile dropdown -->
            <div class="ml-3 relative">
              <dropdown>
                <template #trigger>
                  <button
                    type="button"
                    class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    id="user-menu"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <span class="sr-only">Open user menu</span>
                    <img
                      class="h-8 w-8 rounded-full"
                      :src="user.profile_photo_url"
                      alt=""
                    />
                  </button>
                </template>
                <template #content>
                  <div
                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none"
                    role="menu"
                    aria-orientation="vertical"
                    aria-labelledby="user-menu"
                  >
                    <div class="py-1" role="none">
                      <a
                        href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                        >View profile</a
                      >
                      <a
                        href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                        >Settings</a
                      >
                      <a
                        href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                        >Notifications</a
                      >
                    </div>
                    <div class="py-1" role="none">
                      <a
                        href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                      >
                        Support
                      </a>
                    </div>
                    <div class="py-1" role="none">
                      <a
                        href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                        role="menuitem"
                      >
                        Logout
                      </a>
                    </div>
                  </div>
                </template>
              </dropdown>

              <div>
                <button
                  type="button"
                  class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                  id="user-menu"
                  aria-expanded="false"
                  aria-haspopup="true"
                >
                  <span class="sr-only">Open user menu</span>
                  <img
                    class="h-8 w-8 rounded-full"
                    src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-1.2.1&ixqx=NiG9rqZKjG&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                    alt=""
                  />
                </button>
              </div>

              <!--
              Dropdown menu, show/hide based on menu state.
            -->
              <transition
                enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
              >
                <div
                  class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none"
                  role="menu"
                  aria-orientation="vertical"
                  aria-labelledby="user-menu"
                >
                  <div class="py-1" role="none">
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >View profile</a
                    >
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Settings</a
                    >
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                      >Notifications</a
                    >
                  </div>
                  <div class="py-1" role="none">
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                    >
                      Support
                    </a>
                  </div>
                  <div class="py-1" role="none">
                    <a
                      href="#"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                      role="menuitem"
                    >
                      Logout
                    </a>
                  </div>
                </div>
              </transition>
            </div>
          </div>
        </div>
      </div>

      <main
        class="flex-1 relative z-0 overflow-y-auto focus:outline-none bg-white"
        tabindex="0"
      >
        <slot name="content"></slot>
      </main>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { Inertia } from "@inertiajs/inertia";

import ApplicationLogo from "@/Components/NewLayout/ApplicationLogo.vue";
import Dropdown from "@/Components/NewLayout/Dropdown.vue";

export default {
  components: { ApplicationLogo, Dropdown },

  props: {
    jetstream: {
      type: Object,
      required: true,
    },
    user: {
      type: Object,
      required: true,
    },
    errorBags: {
      type: Array,
      required: true,
    },
    errors: {
      type: Object,
      required: true,
    },
  },

  setup() {
    const notification = ref(true);
    function logout() {
      Inertia.post(route("logout"));
    }

    return {
      notification,
      logout,
    };
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
  },
};
</script>
