<template>
  <button
    as="button"
    class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none"
  >
    <slot></slot>
  </button>
</template>

<script>
  export default {
    props: {}
  }
</script>
