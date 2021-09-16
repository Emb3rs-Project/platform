<template>
  <button
    :type="type"
    :class="[
      styleClasses,
      'inline-flex justify-center items-center px-3 py-2 leading-4 border border-transparent text-sm font-medium uppercase tracking-widest rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
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
            "text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 disabled:bg-blue-600",
        }[props.variant])
    );

    return {
      styleClasses,
    };
  },
};
</script>
