<template>
  <div class="flex flex-1 flex-col md:flex-row">
    <div class="w-full md:w-6/12">
      <h3 class="font-bold">
        {{ heading }}
      </h3>
      <p class="text-sm text-gray-500 mt-3">
        {{ desc }}
      </p>
    </div>
    <div class="w-full md:w-6/12 pl-0 md:pl-4">
      <label for="name" class="block text-sm text-indigo-400 mb-2">
        <slot></slot>
      </label>
      <div
        class="flex items-center"
        v-for="option in options"
        :key="option.key"
      >
        <input
          :name="option.key"
          type="checkbox"
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
          v-model="option.key"
        />
        <label :for="option.key" class="ml-2 block text-sm text-gray-900">
          {{ option.value }}
        </label>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    heading: String,
    desc: String,
    options: Array,
    modelValue: Array | Boolean,
  },
  emits: ["update:modelValue"],
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

<style>
</style>
