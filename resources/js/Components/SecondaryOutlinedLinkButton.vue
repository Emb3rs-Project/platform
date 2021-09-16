<template>
  <Link
    as="button"
    :href="href"
    :class="[
      styleClasses,
      'inline-flex justify-center items-center px-3 py-2 leading-4 border border-gray-300 shadow-sm text-sm font-medium uppercase tracking-widest rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-default disabled:opacity-50',
    ]"
    :disabled="disabled"
  >
  <slot></slot>
  </Link>
</template>

<script>
import { computed } from "vue";

import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },

  props: {
    variant: {
      type: String,
      default: "default",
    },
    href: {
      type: String,
      default: "'#'",
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  setup(props) {
    const styleClasses = computed(
      () =>
        ({
          default:
            "text-gray-700 bg-white hover:bg-gray-50 focus:ring-blue-500 disabled:bg-blue-600",
        }[props.variant])
    );

    return {
      styleClasses,
    };
  },
};
</script>
