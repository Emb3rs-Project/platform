<template>

<pre>{{instance.values}}</pre>


</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/NewLayout/SlideOver.vue";
import SelectMenu from "@/Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "@/Components/NewLayout/Forms/TextInput.vue";
import PrimaryButton from "@/Components/NewLayout/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/NewLayout/SecondaryOutlinedButton.vue";

export default {
  components: {
    AppLayout,
    SlideOver,
    SelectMenu,
    TextInput,
    PrimaryButton,
    SecondaryOutlinedButton,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
  },

  setup() {
    return {};
  },
};
</script>

<style>
</style>
