<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="mt-2">
    <text-input
      v-model="role"
      label="Name"
      placeholder="Manager"
      :required="true"
    ></text-input>
    <div class="space-y-6 sm:space-y-5 divide-y divide-gray-200">
      <div class="pt-6 sm:pt-5">
        <div role="group" aria-labelledby="label-email">
          <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-baseline">
            <h1>Permissions</h1>
            <div class="mt-4 col-span-3">
              <div class="max-w-lg space-y-4">
                <div>
                  <div
                    class="
                      relative
                      flex
                      items-start
                      h-10
                      border-gray-500 border-[1px] border-b-0
                      last:border-b-[1px]
                    "
                    v-bind:class="{
                      'bg-green-100': checkedPermissions.includes(permission),
                    }"
                    v-for="(permission, i) in permissions"
                    :key="permission"
                  >
                    <label
                      :for="`candidates-${i}`"
                      class="
                        font-medium
                        text-gray-700
                        leading-10
                        ml-3
                        text-sm
                        inline-block
                        w-full
                      "
                    >
                      {{ permission }}
                    </label>
                    <div class="flex flex-grow"></div>
                    <div class="flex items-center h-full pr-2">
                      <input
                        :id="`candidates-${i}`"
                        :name="permission"
                        type="checkbox"
                        class="
                          focus:ring-indigo-500
                          h-4
                          w-4
                          text-indigo-600
                          border-gray-300
                          rounded
                          leading-10
                        "
                        v-model="checkedPermissions"
                        :value="permission"
                      />
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

      open.value = false;
    };

    const cancel = () => {
      open.value = false;
      role.value = null;
      checkedPermissions.value = [];
    };

    return {
      role,
      checkedPermissions,
      open,
      createRole,
      cancel,
    };
  },
};
</script>
