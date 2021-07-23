<template>
  <Link :href="'/'">
  <img
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
