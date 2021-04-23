<template>
  <div>
    <div class="flex justify-between">
      <label for="input" class="block text-sm font-medium text-gray-700">
        {{ label }}
      </label>
      <span v-if="required" class="text-sm text-gray-500" id="input-required">
        Required
      </span>
    </div>
    <div class="mt-1">
      <input
        v-model="value"
        type="text"
        name="input"
        :placeholder="placeholder"
        id="input"
        :class="[
          disabled ? 'disabled:opacity-70 cursor-not-allowed' : '',
          'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md',
        ]"
        :disabled="disabled"
        aria-describedby="input-required"
      />
    </div>
  </div>
</template>
70
<script>
import { computed, ref } from "vue";

export default {
  props: {
    modelValue: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      default: "Input...",
    },
    label: {
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

  emits: {
    "update:modelValue": (value) => {
      if (value !== "") return true;
      return false;
    },
  },

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
