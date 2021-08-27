<template>
  <Link
    as="button"
    :type="type"
    :class="[
      disabled ? 'cursor-not-allowed disabled:opacity-50' : 'hover:bg-blue-200 ',
      'inline-flex justify-center items-center px-3 py-2 leading-4 border border-transparent text-sm font-medium uppercase tracking-widest rounded-md shadow-sm text-blue-700 bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
    ]"
    :disabled="disabled"
  >
  <slot></slot>
  </Link>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },

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
