<template>
  <div class="relative">
    <slot name="trigger"></slot>

    <!-- Full Screen Dropdown Overlay to achieve on-outside-click close -->
    <div v-show="open" class="fixed inset-0 z-50" @click="open = false"></div>

    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div v-if="open" @click="open = false">
        <slot name="content"></slot>
      </div>
    </transition>
  </div>
</template>

<script>
import { onMounted, onUnmounted, computed } from "vue";
export default {
  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
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
