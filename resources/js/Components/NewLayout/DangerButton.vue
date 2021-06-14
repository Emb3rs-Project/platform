<template>
  <button
    :type="type"
    :class="[
      disabled
        ? 'cursor-not-allowed disabled:opacity-50 hover:bg-red-600'
        : '',
      'inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium uppercase tracking-widest rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
    ]"
    :disabled="disabled"
  >
    <slot></slot>
  </button>
</template>

<script>
export default {
  props: {
    type: {
      type: String,
      default: "submit",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
