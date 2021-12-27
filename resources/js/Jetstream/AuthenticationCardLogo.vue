<template>
  <Link :href="'/'">
  <img
    class="mx-auto h-24 w-auto"
    src="../../images/logo.png"
    alt=""
  />
  </Link>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
  },
};
</script>
