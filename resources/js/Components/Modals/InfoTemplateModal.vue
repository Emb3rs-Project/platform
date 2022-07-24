<template>
  <TransitionRoot
    as="template"
    :show="open"
  >
    <Dialog
      as="div"
      static
      class="fixed z-10 inset-0 overflow-y-auto"
      @close="open = false"
      :open="open"
    >
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span
          class="hidden sm:inline-block sm:align-middle sm:h-screen"
          aria-hidden="true"
        >
          &#8203;
        </span>
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to="opacity-100 translate-y-0 sm:scale-100"
          leave="ease-in duration-200"
          leave-from="opacity-100 translate-y-0 sm:scale-100"
          leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
            <div>
              <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <InformationCircleIcon
                  class="h-6 w-6 text-blue-500"
                  aria-hidden="true"
                />
              </div>
              <div class="mt-3 sm:mt-5">
                <DialogTitle
                  as="h3"
                  class="text-lg leading-6 font-medium text-gray-900"
                >
                  {{info.title}}
                </DialogTitle>
                <div class="mt-3" v-html="info.description" />
              </div>
            </div>
            <div class="mt-5">
              Data:
              <div class="indent-16" v-for="content in info.content" :key="content">
                {{content}}
              </div>
            </div>
            <div class="mt-5 sm:mt-12 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
              <SecondaryOutlinedButton
                type="button"
                @click="open = false"
                ref="cancelButtonRef"
                class="sm:col-start-2"
              >
                Close
              </SecondaryOutlinedButton>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import { computed } from "vue";

import {
  Dialog,
  DialogOverlay,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { InformationCircleIcon } from "@heroicons/vue/outline";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

export default {
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    InformationCircleIcon,
    TransitionChild,
    TransitionRoot,
    SecondaryOutlinedButton,   
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    info: {
      type: Array,
      required: true,
    },
  },

  emits: ["update:modelValue"],

  setup(props, { emit }) {  
    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    return {
      open,
    };
  }, 
};
</script>
