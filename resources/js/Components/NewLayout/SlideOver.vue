<template>
  <div class="absolute inset-y-0 right-0 max-w-full flex overflow-hidden">
    <transition
      enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
      enter-from-class="translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
      leave-from-class="translate-x-0"
      leave-to-class="translate-x-full"
    >
      <div v-if="open" class="w-screen max-w-2xl">
        <form
          class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl z-20"
        >
          <div class="flex-1 h-0 overflow-y-auto">
            <!-- Header -->
            <slot name="header"></slot>

            <slot name="content"></slot>
          </div>

          <!-- Action buttons -->
          <div class="flex-shrink-0 px-4 py-4 flex justify-end">
            <slot name="actions"></slot>
          </div>
        </form>
      </div>
    </transition>
  </div>
</template>

<script>
import { onMounted, onUnmounted, computed } from "vue";
export default {
  props: {
    modelValue: Boolean,
  },

  emits: ["update:modelValue"],

  setup(props, { emit }) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const closeOnEscape = (e) => {
      if (open.value && e.keyCode === 27) {
        open.value = false;
      }
    };

    onMounted(() => document.addEventListener("keydown", closeOnEscape));
    onUnmounted(() => document.removeEventListener("keydown", closeOnEscape));

    return {
      open,
    };
  },
};
</script>
