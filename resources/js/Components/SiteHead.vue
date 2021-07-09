<template>
  <InertiaHead>
    <title>{{title ? `${title} | ` : ''}}EMB3R's</title>
    <meta
      head-key="description"
      name="description"
      :content="description ? description : ''"
    />
  </InertiaHead>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
    },
    description: {
      type: String,
      default: "This is the description for the EMB3Rs app.",
    },
  },
};
</script>
