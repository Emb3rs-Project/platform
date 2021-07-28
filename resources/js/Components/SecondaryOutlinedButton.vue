<template>
  <button
    :type="type"
    :class="[
      disabled ? 'cursor-default disabled:opacity-50' : 'hover:bg-gray-50',
      'inline-flex justify-center items-center px-3 py-2 leading-4 border border-gray-300 shadow-sm text-sm font-medium uppercase tracking-widest rounded-md text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
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
