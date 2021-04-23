<template>
  <inertia-link
    as="button"
    :href="href"
    :class="[
      disabled
        ? 'cursor-wait disabled:opacity-50 hover:bg-blue-600'
        : 'hover:bg-blue-700',
      'inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
    ]"
    :disabled="disabled"
  >
    <slot></slot>
  </inertia-link>
</template>

<script>
export default {
  props: {
    href: {
      type: String,
      default: "'#'",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
