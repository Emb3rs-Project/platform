<template>
  <Link
    as="button"
    :href="route(path, parameter)"
    :class="[
      disabled
        ? 'cursor-wait disabled:opacity-50 hover:bg-blue-600'
        : 'hover:bg-blue-700',
      'inline-flex justify-center items-center px-3 py-2 leading-4 uppercase border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
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
    path: {
      type: String,
      default: "'#'",
    },
    parameter: {
      type: String,
      default: {},
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
