<template>
  <button
    :type="type"
    :class="[
      disabled
        ? 'cursor-not-allowed disabled:opacity-50 hover:bg-blue-600'
        : '',
      'inline-flex items-center px-3 py-2 leading-4 border border-transparent text-sm font-medium uppercase tracking-widest rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
     ''
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
