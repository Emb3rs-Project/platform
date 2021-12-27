<template>
  <div
    class="bg-yellow-50 border-l-4 border-yellow-400 p-4"
    v-if="open"
  >
    <div class="flex">
      <div class="shrink-0">
        <ExclamationIcon
          class="h-5 w-5 text-yellow-400"
          aria-hidden="true"
        />
      </div>
      <div class="ml-3">
        <p class="text-sm text-yellow-700">
          {{ content }}
        </p>
      </div>
      <div class="ml-auto pl-3">
        <div class="-mx-1.5 -my-1.5">
          <button
            type="button"
            class="inline-flex bg-yellow-50 rounded-md p-1.5 text-yellow-500 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600"
            @click="open = false"
          >
            <span class="sr-only">Dismiss</span>
            <XIcon
              class="h-5 w-5"
              aria-hidden="true"
            />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { computed } from "vue";

import { ExclamationIcon, XIcon } from "@heroicons/vue/solid";

export default {
  components: {
    ExclamationIcon,
    XIcon,
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    content: {
      type: String,
      required: true,
    },
  },

  emits: ["update:modelValue"],

  setup(props, ctx) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => ctx.emit("update:modelValue", value),
    });

    return {
      open,
    };
  },
};
</script>
