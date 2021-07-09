<template>
  <InertiaLink
    as="button"
    :href="route(path)"
    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
  >
    <slot></slot>
  </InertiaLink>
</template>

<script>
export default {
  props: ["path"],
};
</script>

<style>
</style>
