<template>
  <slide-over
    v-model="open"
    title="New Link"
    subtitle="Get started by selecting a marker to start your segments."
    headerBackground="bg-blue-700"
    dismissButtonTextColor="text-gray-200"
    subtitleTextColor="text-gray-200"
  >
    <div
      class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    >
      <label
        for="project_name"
        class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
      >
        Name
      </label>
      <div class="sm:col-span-2">
        <text-input v-model="form.name" placeholder="Link's Name"> </text-input>
      </div>
    </div>

    <template #actions>
      <secondary-outlined-button
        type="button"
        :disabled="form.processing"
        @click="open = false"
      >
        Cancel
      </secondary-outlined-button>
      <primary-button @click="submit" :disabled="form.processing">
        Save
      </primary-button>
    </template>
  </slide-over>
</template>

<script>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

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
    modelValue: {
      type: Boolean,
      required: true,
    },
  },
  emits: ["update:modelValue"],
  setup(props, { emit }) {
    const form = useForm({
      name: "",
    });

    const submit = () => form.post(route("objects.links.store"));

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    return {
      form,
      submit,
      open,
    };
  },
};
</script>

<style>
</style>
