<template>
  <inertia-link
    as="button"
    :href="href"
    :class="[
      disabled
        ? 'cursor-default disabled:opacity-50 hover:bg-white'
        : 'hover:bg-gray-50',
      'inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium uppercase tracking-widest rounded-md text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
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
