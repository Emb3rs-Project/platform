<template>
  <div>
    <div class="flex justify-between">
      <label
        :for="value"
        class="block text-sm font-medium text-gray-700"
      >
        {{ label }}
      </label>
      <span
        v-show="required"
        class="text-sm text-gray-500"
        id="input-required"
      >
        Required
      </span>
    </div>
    <div class="mt-1 relative rounded-md shadow-sm">
      <input
        v-model="value"
        type="text"
        :name="value"
        :placeholder="placeholder"
        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
        :class="{'disabled:opacity-70 cursor-not-allowed' : disabled}"
        :disabled="disabled"
        aria-describedby="input-required"
      />
      <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <span class="text-gray-500 sm:text-sm">
          {{ unit }}
        </span>
      </div>
    </div>
    <p class="mt-2 text-sm text-gray-500 text-justify">{{ description }}</p>
  </div>
</template>
70
<script>
import { computed, ref } from "vue";

export default {
  props: {
    modelValue: {
      type: String,
      default: "",
    },
    placeholder: {
      type: String,
      default: "Input...",
    },
    label: {
      type: String,
      default: "",
    },
    unit: {
      type: String,
      default: "",
    },
    description: {
      type: String,
      default: "",
    },
    required: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  emits: ["update:modelValue"],

  setup(props, { emit }) {
    const input = ref(null);

    const value = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const focus = () => input.value.focus();

    return {
      value,
      focus,
    };
  },
};
</script>
