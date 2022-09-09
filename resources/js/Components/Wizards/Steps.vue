<template>
  <nav aria-label="Progress">
    <ol class="space-y-4 md:flex md:space-y-0 md:space-x-8">
      <li
        @click="$emit('select', {currentStep: index})"
        v-for="(step, index) in steps"
        :key="step.name"
        class="md:flex-1 cursor-pointer"
      >
        <div
          v-if="step.status === 'complete'"
          class="group pl-4 py-2 flex flex-col border-l-4 border-blue-600 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4"
        >
          <span class="text-xs text-blue-600 font-semibold tracking-wide uppercase">{{ step.id }}</span>
          <span class="text-sm font-medium">{{ step.name }}</span>
        </div>
        <div
          v-else-if="step.status === 'current'"
          class="pl-4 py-2 flex flex-col border-l-4 border-blue-600 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4"
          aria-current="step"
        >
          <span class="text-xs text-blue-600 font-semibold tracking-wide uppercase">{{ step.id }}</span>
          <span class="text-sm font-medium">{{ step.name }}</span>
        </div>
        <div
          v-else
          class="group pl-4 py-2 flex flex-col border-l-4 border-gray-200 md:pl-0 md:pt-4 md:pb-0 md:border-l-0 md:border-t-4"
        >
          <span class="text-xs text-gray-500 font-semibold tracking-wide uppercase">{{ step.id }}</span>
          <span class="text-sm font-medium">{{ step.name }}</span>
        </div>
      </li>
    </ol>
  </nav>
</template>

<script>
// const steps = [
//   { id: "Step 1", name: "Job details", href: "#", status: "complete" },
//   { id: "Step 2", name: "Application form", href: "#", status: "current" },
//   { id: "Step 3", name: "Preview", href: "#", status: "upcoming" },
// ];

export default {
  props: {
    steps: {
      type: Array,
      required: true,
    },
  },

  setup() {},
};
</script>
