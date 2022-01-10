<template>
  <div
    :class="[styleClasses.background, styleClasses.border, 'border-l-4 p-4']"
    v-if="open"
  >
    <div class="flex">
      <div class="shrink-0">
        <InformationCircleIcon
          v-if="type === 'info'"
          :class="[styleClasses.icon.text, 'h-5 w-5']"
          aria-hidden="true"
        />
        <ExclamationCircleIcon
          v-if="type === 'warning'"
          :class="[styleClasses.icon.text, 'h-5 w-5']"
          aria-hidden="true"
        />
        <XCircleIcon
          v-if="type === 'danger'"
          :class="[styleClasses.icon.text, 'h-5 w-5']"
          aria-hidden="true"
        />
        <CheckCircleIcon
          v-if="type === 'success'"
          :class="[styleClasses.icon.text, 'h-5 w-5']"
          aria-hidden="true"
        />
      </div>
      <div class="ml-3">
        <p :class="[styleClasses.message, 'text-sm']">
          {{ message }}
        </p>
      </div>
      <div
        v-if="dismissable"
        class="ml-auto pl-3"
      >
        <div class="-mx-1.5 -my-1.5">
          <button
            type="button"
            :class="[
              styleClasses.dismissIcon.background,
              styleClasses.dismissIcon.text,
              styleClasses.dismissIcon.ring,
              'inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2'
            ]"
            @click="open = false"
          >
            <span class="sr-only">Dismiss</span>
            <XIcon
              class="h-5 w-5"
              aria-hidden="true"
            />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { computed } from "vue";

import { XIcon } from "@heroicons/vue/solid";

import {
  InformationCircleIcon,
  ExclamationCircleIcon,
  XCircleIcon,
  CheckCircleIcon,
} from "@heroicons/vue/solid";

export default {
  components: {
    InformationCircleIcon,
    ExclamationCircleIcon,
    XCircleIcon,
    CheckCircleIcon,
    XIcon,
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    message: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      default: "info",
    },
    dismissable: {
      type: Boolean,
      default: true,
    },
  },

  emits: ["update:modelValue"],

  setup(props, ctx) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => ctx.emit("update:modelValue", value),
    });

    const styleClasses = computed(
      () =>
        ({
          info: {
            background: "bg-blue-50",
            border: "border-blue-400",
            icon: {
              text: "text-blue-400",
            },
            message: "text-blue-400",
            dismissIcon: {
              background: "bg-blue-50 hover:bg-blue-100",
              text: "text-blue-500",
              ring: "focus:ring-offset-blue-50 focus:ring-blue-600",
            },
          },
          warning: {
            background: "bg-yellow-50",
            border: "border-yellow-400",
            icon: {
              text: "text-yellow-400",
            },
            message: "text-yellow-700",
            dismissIcon: {
              background: "bg-yellow-50 hover:bg-yellow-100",
              text: "text-yellow-500",
              ring: "focus:ring-offset-yellow-50 focus:ring-yellow-600",
            },
          },
          danger: {
            background: "bg-red-50",
            border: "border-red-400",
            icon: {
              text: "text-red-400",
            },
            message: "text-red-700",
            dismissIcon: {
              background: "bg-red-50 hover:bg-red-100",
              text: "text-red-500",
              ring: "focus:ring-offset-red-50 focus:ring-red-600",
            },
          },
          success: {
            background: "bg-green-50",
            border: "border-green-400",
            icon: {
              text: "text-green-400",
            },
            message: "text-green-700",
            dismissIcon: {
              background: "bg-green-50 hover:bg-green-100",
              text: "text-green-500",
              ring: "focus:ring-offset-green-50 focus:ring-green-600",
            },
          },
        }[props.type.toLowerCase()])
    );

    return {
      open,
      styleClasses,
    };
  },
};
</script>
