<template>
  <Link
    :href="href"
    :class="classes"
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

  props: ["href", "active"],

  computed: {
    classes() {
      return this.active
        ? "flex flex-1 max-h-12 h-full pl-20 items-center pt-1 font-bold text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out"
        : "flex flex-1 max-h-12 h-full pl-20 items-center pt-1 text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out";
    },
  },
};
</script>
