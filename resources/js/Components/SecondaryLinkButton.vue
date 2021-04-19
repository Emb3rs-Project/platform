<template>
  <inertia-link
    as="button"
    :href="route(path, parameter)"
    class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200 focus:outline-none"
  >
    <slot></slot>
  </inertia-link>
</template>

<script>
export default {
  props: {
    path: {
      type: String,
      require: true,
    },
    parameter: {
      type: String,
      require: false,
    },
  },
};
</script>

<style scoped>
</style>
