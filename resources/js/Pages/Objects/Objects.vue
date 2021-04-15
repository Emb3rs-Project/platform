<template>
  <app-layout>
    <template #content>
      <div>
        <div
          class="fixed right-8 top-6 z-10 p-4 rounded-full cursor-pointer bg-yellow-500 hover:animate-none"
          :class="{ 'animate-pulse': !openMenu }"
          @click="openMenu = !openMenu"
        >
          <svg
            class="w-8 h-8 text-white hover:text-gray-100"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
            ></path>
          </svg>
        </div>
        <!-- Menu START -->
        <transition
          enter-active-class="transition ease-out duration-200"
          enter-class="opacity-0 translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
          leave-active-class="transition ease-in duration-150"
          leave-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0 translate-y-1"
        >
          <div
            v-show="openMenu"
            class="absolute z-10 right-10 top-16 transform mt-3 px-2 w-screen max-w-md sm:px-0"
          >
            <div
              class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden"
            >
              <div
                class="relative grid gap-6 bg-white px-5 py-6 sm:gap-8 sm:p-8"
              >
                <a
                  href="#"
                  class="-m-3 p-3 flex items-start rounded-lg hover:bg-gray-50 transition ease-in-out duration-150"
                  v-for="i in [0, 1, 2, 3]"
                  :key="i"
                  @click="onLoadComponent()"
                >
                  <!-- Heroicon name: outline/support -->
                  <svg
                    class="flex-shrink-0 h-6 w-6 text-yellow-700"
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
                      d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"
                    />
                  </svg>
                  <div class="ml-4">
                    <p class="text-base font-medium text-gray-900">Sources</p>
                    <p class="mt-1 text-sm text-gray-500">
                      Manage your Institution Sources
                    </p>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </transition>
        <!-- Menu END -->
        <amazing-map :centerValue="[38.7181959, -9.1975417]"></amazing-map>
        <Component
          class="z-20"
          v-bind="$props"
          :is="modalComponent"
          v-if="modalComponent"
        />
      </div>
    </template>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import AmazingMap from "../../Components/Map/AmazingMap";
import SlideOver from "../../Components/NewLayout/SlideOver";
import { ref } from "@vue/reactivity";
import SinkCreate from "../../Pages/Objects/Sinks/SinkCreate";

export default {
  components: { AppLayout, AmazingMap, SlideOver, SinkCreate },
  setup(props) {
    const openMenu = ref(false);
    const slideOver = ref(false);
    const modalComponent = ref();

    const onLoadComponent = () => (modalComponent.value = SinkCreate);

    console.log(props.slideOverComponent);

    return {
      openMenu,
      slideOver,
      onLoadComponent,
      modalComponent,
    };
  },
};
</script>

<style>
</style>


