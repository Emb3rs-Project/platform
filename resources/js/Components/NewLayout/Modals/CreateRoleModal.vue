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
      @close="cancel"
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
                      v-model="role"
                      label="Name"
                      placeholder="Manager"
                      :required="true"
                    ></text-input>
                    <div class="space-y-6 sm:space-y-5 divide-y divide-gray-200">
                      <div class="pt-6 sm:pt-5">
                        <div
                          role="group"
                          aria-labelledby="label-email"
                        >
                          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
                            <h1 class="col-span-1">Permissions</h1>
                            <secondary-outlined-button
                              class="col-span-2 bg-green-200 hover:bg-green-300 text-sm"
                              @click="checkAll"
                            >
                              {{allChecked ? "Uncheck All" : "Check All"}}
                            </secondary-outlined-button>
                            <div
                              class="col-span-3"
                              v-for="(p, name) in grouped"
                              :key="p"
                            >
                              <Disclosure
                                as="div"
                                v-slot="{ open }"
                              >
                                <dt class="text-lg">
                                  <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
                                    <span class="font-medium text-gray-900">
                                      {{ name }}
                                    </span>
                                    <span class="ml-6 h-7 flex items-center">
                                      <ChevronDownIcon
                                        :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform',]"
                                        aria-hidden="true"
                                      />
                                    </span>
                                  </DisclosureButton>
                                </dt>
                                <transition
                                  enter-active-class="transition duration-100 ease-out"
                                  enter-from-class="transform scale-95 opacity-0"
                                  enter-to-class="transform scale-100 opacity-100"
                                  leave-active-class="transition duration-75 ease-out"
                                  leave-from-class="transform scale-100 opacity-100"
                                  leave-to-class="transform scale-95 opacity-0"
                                >
                                  <DisclosurePanel
                                    as="dd"
                                    class="mt-2 pr-12"
                                  >
                                    <div class="mt-4 col-span-3">
                                      <div class="max-w-lg space-y-4">
                                        <div>
                                          <div
                                            class="relative flex items-start h-10 border-gray-500 border-[1px] border-b-0 last:border-b-[1px]"
                                            v-bind:class="{'bg-green-100':checkedPermissions.includes(permission)}"
                                            v-for="(permission, i) in p"
                                            :key="permission"
                                          >
                                            <label
                                              :for="`candidates-${i}`"
                                              class="font-medium text-gray-700 leading-10 ml-3 text-sm inline-block w-full"
                                            >
                                              {{ permission.name }}
                                            </label>
                                            <div class="flex flex-grow"></div>
                                            <div class="flex items-center h-full pr-2">
                                              <input
                                                :id="`candidates-${i}`"
                                                :name="permission.name"
                                                type="checkbox"
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded leading-10"
                                                v-model="checkedPermissions"
                                                :value="permission.name"
                                              />
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </DisclosurePanel>
                                </transition>
                              </Disclosure>
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
              <primary-button
                type="button"
                @click="createRole"
              >
                Create
              </primary-button>

              <secondary-outlined-button
                class="mr-2"
                type="button"
                @click="cancel"
                ref="cancelButtonRef"
              >
                Cancel
              </secondary-outlined-button>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";

import {
  Dialog,
  DialogOverlay,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { IdentificationIcon } from "@heroicons/vue/outline";

import TextInput from "@/Components/NewLayout/Forms/TextInput.vue";
import PrimaryButton from "@/Components/NewLayout/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/NewLayout/SecondaryOutlinedButton.vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";

var groupBy = function (xs, key) {
  return xs.reduce(function (rv, x) {
    (rv[x[key]] = rv[x[key]] || []).push(x);
    return rv;
  }, {});
};

export default {
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    IdentificationIcon,
    TextInput,
    SecondaryOutlinedButton,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
    PrimaryButton,
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
    const store = useStore();
    const role = ref(null);
    const checkedPermissions = ref([]);

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const createRole = async () => {
      await store.dispatch("teamRoles/createRole", {
        role: role.value,
        permissions: checkedPermissions.value,
      });

      role.value = null;
      checkedPermissions.value = [];
      allChecked.value = false;

      open.value = false;
    };

    const cancel = () => {
      open.value = false;
      role.value = null;
      checkedPermissions.value = [];
      allChecked.value = false;
    };

    const grouped = ref(groupBy(props.permissions, "group"));
    const allChecked = ref(false);

    const checkAll = (event) => {
      if (!allChecked.value) {
        checkedPermissions.value = props.permissions.map((p) => p.name);
        allChecked.value = true;
      } else {
        checkedPermissions.value = [];
        allChecked.value = false;
      }
      event.preventDefault();
    };
    return {
      role,
      checkedPermissions,
      open,
      createRole,
      cancel,
      grouped,
      checkAll,
      allChecked,
    };
  },
};
</script>
