<template>
  <button
    type="button"
    class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
    aria-pressed="false"
    @click="source.selected = !source.selected"
  >
    <!-- Enabled: "translate-x-5", Not Enabled: "translate-x-0" -->
    <span
      v-bind:class="{
        'translate-x-5': source.selected,
        'translate-x-0': source.selected == false,
        'translate-x-2': source.selected == null,
      }"
      class="pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200"
    >
      <!-- Enabled: "opacity-0 ease-out duration-100", Not Enabled: "opacity-100 ease-in duration-200" -->
      <!-- CROSS -->
      <span
        v-bind:class="{
          'opacity-0 ease-out duration-100':
            source.selected == true || source.selected == null,
          'opacity-100 ease-in duration-200': source.selected == false,
        }"
        class="absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
        aria-hidden="true"
      >
        <svg
          class="bg-white h-3 w-3 text-gray-400"
          fill="none"
          viewBox="0 0 12 12"
        >
          <path
            d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          />
        </svg>
      </span>
      <!-- MINUS -->
      <span
        v-bind:class="{
          'opacity-100 ease-in duration-200': source.selected == null,
          'opacity-0 ease-out duration-100':
            source.selected == false || source.selected,
        }"
        class="opacity-0 ease-out duration-100 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
        aria-hidden="true"
      >
        <svg
          className="w-6 h-6"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth="{2}"
            d="M20 12H4"
          />
        </svg>
      </span>
      <!-- CHECK -->
      <span
        v-bind:class="{
          'opacity-100 ease-in duration-200': source.selected,
          'opacity-0 ease-out duration-100': !source.selected,
        }"
        class="opacity-0 ease-out duration-100 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity"
        aria-hidden="true"
      >
        <svg
          class="bg-white h-3 w-3 text-indigo-600"
          fill="currentColor"
          viewBox="0 0 12 12"
        >
          <path
            d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
          />
        </svg>
      </span>
    </span>
  </button>
</template>

<script>
export default {
  // TODO : Convert this to generic component
};
</script>

<style>
</style>
