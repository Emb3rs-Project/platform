<!-- This example requires Tailwind CSS v2.0+ -->
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
          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                  <IdentificationIcon
                    class="h-6 w-6 text-green-600"
                    aria-hidden="true"
                  />
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <DialogTitle
                    as="h3"
                    class="text-lg leading-6 font-medium text-gray-900"
                  >
                    Create a new Role
                  </DialogTitle>
                  <div class="mt-2">
                    <text-input
                      label="Name"
                      placeholder="Administrator"
                    ></text-input>
                    <div class="space-y-6 sm:space-y-5 divide-y divide-gray-200">
                      <div class="pt-6 sm:pt-5">
                        <div
                          role="group"
                          aria-labelledby="label-email"
                        >
                          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                            <div>
                              <div
                                class="text-base font-medium text-gray-900 sm:text-sm sm:text-gray-700"
                                id="label-email"
                              >
                                Permissions
                              </div>
                            </div>
                            <div class="mt-4 sm:mt-0 sm:col-span-2">
                              <div class="max-w-lg space-y-4">

                                <div>
                                  <div
                                    class="relative flex items-start"
                                    v-for="permission in permissions"
                                    :key="permission"
                                  >
                                    <div class="flex items-center h-5">
                                      <input
                                        :name="permission"
                                        type="checkbox"
                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                        v-model="checkedPermissions"
                                        :value="permission"
                                      />
                                    </div>
                                    <div class="ml-3 text-sm">
                                      <label
                                        for="candidates"
                                        class="font-medium text-gray-700"
                                      >
                                        {{permission}}
                                      </label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                @click="open = false"
              >
                Create
              </button>
              <button
                type="button"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                @click="open = false"
                ref="cancelButtonRef"
              >
                Cancel
              </button>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import { computed, ref, watch } from "vue";
import {
  Dialog,
  DialogOverlay,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { ExclamationIcon, IdentificationIcon } from "@heroicons/vue/outline";

import TextInput from "@/Components/NewLayout/Forms/TextInput.vue";

export default {
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    ExclamationIcon,
    IdentificationIcon,
    TextInput,
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    permissions: {
      type: Object,
      required: true,
    },
  },

  setup(props, { emit }) {
    const checkedPermissions = ref([]);

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    watch(
      checkedPermissions,
      (checkedPermissions) => {
        console.log(checkedPermissions);
      },
      { deep: true }
    );

    return {
      open,
      checkedPermissions,
    };
  },
};
</script>
