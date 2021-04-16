<template>
  <inertia-link
    as="button"
    :href="route(path, parameter)"
    class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none"
  >
    <slot></slot>
  </inertia-link>
</template>

<script>
export default {
  props: {
    path: {
      type: String,
    },
    parameter: {
      type: String,
      default: {},
    },
  },
};
</script>

<style scoped>
</style>
