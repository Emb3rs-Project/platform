<template>
  <div class="flex flex-1 flex-col md:flex-row">
    <div class="w-ful pl-0 md:pl-4">
      <label
        for="name"
        class="block text-sm text-indigo-400 mb-2"
      >
        <slot></slot>
      </label>
      <input
        name="name"
        type="text"
        class="border border-gray-300 outline-none focus:ring focus:ring-indigo-200 border-opacity-25 pl-3 text-sm w-full leading-6 rounded"
        :placeholder="placeholder"
        v-model="value"
        ref="input"
        :required="required"
        :disabled="disabled"
      />
    </div>
  </div>
</template>

<script>
  export default {
    props: {
      placeholder: String,
      modelValue: String,
      required: Boolean,
      disabled: Boolean,
    },
    emits: ["update:modelValue"],
    methods: {
      focus() {
        this.$refs.input.focus();
      },
    },
    computed: {
      value: {
        get() {
          return this.modelValue;
        },
        set(value) {
          this.$emit("update:modelValue", value);
        },
      },
    },
  };
</script>
