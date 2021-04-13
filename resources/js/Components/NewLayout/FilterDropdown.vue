<template>
  <div class="relative">
    <div @click="open = !open">
      <slot name="trigger"></slot>
    </div>

    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <div
        v-show="open"
        class="z-10"
        @click="open = false"
      >
        <slot name="content"></slot>
      </div>
    </transition>
  </div>
</template>

<script>
  import { ref, onMounted, onUnmounted } from "vue";
  export default {
    setup() {
      let open = ref(false);

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
  }
</script>
