<template>

  <Head>
    <title>{{title}}</title>

    <meta
      head-key="description"
      name="description"
      :content="description ? description : ''"
    />
  </Head>

</template>

<script>
import { Head } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Head,
  },

  props: {
    title: {
      type: String,
      required: true,
    },
    description: {
      type: String,
      default: "This is the description for the EMB3Rs app.",
    },
  },
};
</script>
