<template>
  <button
    :type="type"
    :class="[
      styleClasses,
      'inline-flex justify-center items-center px-3 py-2 leading-4 border border-gray-300 shadow-sm text-sm font-medium uppercase tracking-widest rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-default disabled:opacity-50',
    ]"
    :disabled="disabled"
  >
    <slot></slot>
  </button>
</template>

<script>
import { computed } from "vue";

export default {
  props: {
    variant: {
      type: String,
      default: "default",
    },
    type: {
      type: String,
      default: "submit",
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
