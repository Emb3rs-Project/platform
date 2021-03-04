<template>
  <div class="flex flex-1">
    <div class="w-6/12">
      <h3 class="font-bold">{{ heading }}</h3>
      <p class="text-xs text-gray-400 mt-3">{{ desc }}</p>
    </div>
    <div class="w-6/12">
      <label for="name" class="block text-xs text-gray-400"
        ><slot></slot
      ></label>
      <input
        name="name"
        class="border bg-gray-200 border-gray-200 outline-none pl-5 text-sm w-full leading-6 mt-4"
        :placeholder="placeholder"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: ["heading", "desc", "placeholder", "modelValue"],
  emits: ["update:modelValue"],
  methods: {
    focus() {
      this.$refs.input.focus();
    },
  },
};
</script>

<style>
</style>
