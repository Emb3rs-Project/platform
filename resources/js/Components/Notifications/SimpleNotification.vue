<template>
  <div class="mr-4 mt-2">
    <div :class="[styleClasses.background, 'max-w-sm w-full shadow-sm rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden']">
      <div class="p-2">
        <div class="flex items-start">
          <div class="shrink-0">
            <span
              class="flex p-2 rounded-lg"
              :class="styleClasses.icon.background"
            >
              <InformationCircleIcon
                v-if="type === 'info' || type === 'generic'"
                :class="[styleClasses.icon.text, 'h-6 w-6']"
                aria-hidden="true"
              />
              <ExclamationCircleIcon
                v-if="type === 'warning'"
                :class="[styleClasses.icon.text, 'h-6 w-6']"
                aria-hidden="true"
              />
              <XCircleIcon
                v-if="type === 'danger'"
                :class="[styleClasses.icon.text, 'h-6 w-6']"
                aria-hidden="true"
              />
              <CheckCircleIcon
                v-if="type === 'success'"
                :class="[styleClasses.icon.text, 'h-6 w-6']"
                aria-hidden="true"
              />
            </span>
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p :class="[styleClasses.title, 'text-sm font-medium']">
              {{ title }}
            </p>
            <p v-html="message" :class="[styleClasses.message, 'mt-1 text-sm']">
            </p>
          </div>
          <div class="ml-4 shrink-0 flex">
            <button
              @click="close"
              :class="[styleClasses.dismissIcon.text, 'rounded-md inline-flex focus:outline-none focus:ring-2 focus:ring-offset-2']"
            >
              <span class="sr-only">Close</span>
              <XIcon
                class="h-5 w-5"
                aria-hidden="true"
              />
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { computed } from "vue";

import {
  InformationCircleIcon,
  ExclamationCircleIcon,
  XCircleIcon,
  CheckCircleIcon,
} from "@heroicons/vue/outline";
import { XIcon } from "@heroicons/vue/solid";

export default {
  components: {
    InformationCircleIcon,
    ExclamationCircleIcon,
    XCircleIcon,
    CheckCircleIcon,
    XIcon,
  },

  props: {
    type: {
      type: String,
      default: "general",
    },
    title: {
      type: String,
      required: true,
    },
    message: {
      type: String,
      required: true,
    },
    close: {
      type: Function,
      required: true,
    },
  },

  setup(props) {
    const styleClasses = computed(
      () =>
        ({
          generic: {
            background: "bg-white",
            icon: {
              background: "bg-white",
              text: "text-blue-500",
            },
            title: "text-gray-900",
            message: "text-gray-500",
            dismissIcon: {
              background: "bg-white",
              text: "text-gray-400 hover:text-gray-500",
            },
          },
          info: {
            background: "bg-blue-500",
            icon: {
              background: "bg-blue-700",
              text: "text-white",
            },
            title: "text-white",
            message: "text-white",
            dismissIcon: {
              background: "hover:bg-blue-400",
              text: "text-white",
            },
          },
          warning: {
            background: "bg-yellow-500",
            icon: {
              background: "bg-yellow-700",
              text: "text-white",
            },
            title: "text-white",
            message: "text-white",
            dismissIcon: {
              background: "hover:bg-yellow-400",
              text: "text-white",
            },
          },
          danger: {
            background: "bg-red-500",
            icon: {
              background: "bg-red-700",
              text: "text-white",
            },
            title: "text-white",
            message: "text-white",
            dismissIcon: {
              background: "hover:bg-red-400",
              text: "text-white",
            },
          },
          success: {
            background: "bg-green-500",
            icon: {
              background: "bg-green-700",
              text: "text-white",
            },
            title: "text-white",
            message: "text-white",
            dismissIcon: {
              background: "hover:bg-green-400",
              text: "text-white",
            },
          },
        }[props.type])
    );

    return {
      styleClasses,
    };
  },
};
</script>
