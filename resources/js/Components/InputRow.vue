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
      <label
        for="name"
        class="block text-sm text-indigo-400 mb-2"
      >
        <slot></slot>
      </label>
      <input
          v-if="type === 'text'"
        name="name"
        type="text"
        class="border border-gray-300 outline-none focus:ring focus:ring-indigo-200 border-opacity-25 pl-3 text-sm w-full leading-6 rounded"
        :placeholder="placeholder"
        v-model="value"
        ref="input"
        :required="required"
        :disabled="disabled"
      />
        <div v-else-if="type === 'textarea'" :data-replicated-value="value" class="grow-wrap">
            <textarea
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
  </div>
</template>

<script>
  export default {
    props: {
      heading: String,
      desc: String,
      placeholder: String,
      modelValue: String,
      required: Boolean,
      disabled: Boolean,
      type: {
          type: String,
          default: 'text'
      }
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
<style>
.grow-wrap {
    /* easy way to plop the elements on top of each other and have them both sized based on the tallest one's height */
    display: grid;
}
.grow-wrap::after {
    /* Note the weird space! Needed to preventy jumpy behavior */
    content: attr(data-replicated-value) " ";

    /* This is how textarea text behaves */
    white-space: pre-wrap;

    /* Hidden from view, clicks, and screen readers */
    visibility: hidden;
}
.grow-wrap > textarea {
    /* You could leave this, but after a user resizes, then it ruins the auto sizing */
    resize: none;

    /* Firefox shows scrollbar on growth, you can hide like this. */
    overflow: hidden;
}
.grow-wrap > textarea,
.grow-wrap::after {
    /* Identical styling required!! */
    padding: 0.5rem;

    /* Place on top of each other */
    grid-area: 1 / 1 / 2 / 2;
}


</style>
