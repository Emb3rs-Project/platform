<template>
  <nav class="flex items-center justify-center" aria-label="Progress">
    <p class="text-sm font-medium">
      Step {{ currentStepIndex + 1 }} of {{ steps.length }}
    </p>
    <ol class="ml-8 flex items-center space-x-5">
      <li v-for="step in steps" :key="step.name">
        <div
          v-if="step.status === 'complete'"
          class="block w-2.5 h-2.5 bg-indigo-600 rounded-full"
        >
          <span class="sr-only">{{ step.name }}</span>
        </div>
        <div
          v-else-if="step.status === 'current'"
          class="relative flex items-center justify-center"
          aria-current="step"
        >
          <span class="absolute w-5 h-5 p-px flex" aria-hidden="true">
            <span class="w-full h-full rounded-full bg-indigo-200" />
          </span>
          <span
            class="relative block w-2.5 h-2.5 bg-indigo-600 rounded-full"
            aria-hidden="true"
          />
          <span class="sr-only">{{ step.name }}</span>
        </div>
        <div v-else class="block w-2.5 h-2.5 bg-gray-200 rounded-full">
          <span class="sr-only">{{ step.name }}</span>
        </div>
      </li>
    </ol>
    <p class="ml-8 text-sm font-medium">{{ currentStep.name }}</p>
  </nav>
</template>

<script>
import { computed } from "vue";
export default {
  props: {
    steps: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const currentStepIndex = computed(() =>
      props.steps.findIndex((step) => step.status === "current")
    );

    const currentStep = computed(() => props.steps[currentStepIndex.value]);

    return {
      currentStepIndex,
      currentStep,
    };
  },
};
</script>
