<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <Listbox
    as="div"
    v-model="selected"
  >
    <div class="flex justify-between">
      <ListboxLabel class="block text-sm font-medium text-gray-700">
        {{ label }}
      </ListboxLabel>
      <span
        class="block text-sm font-medium text-gray-500"
        v-show="required"
      >
        Required
      </span>
    </div>
    <div class="mt-1 relative">
      <ListboxButton
        :class="[
          disabled ? 'disabled:opacity-70 cursor-not-allowed' : '',
          'h-10 bg-white relative w-full border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-base',
        ]"
        :disabled="disabled"
      >
        <span class="block truncate">
          {{ selected.value ?? "Select an option..." }}
        </span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
          <SelectorIcon
            class="h-5 w-5 text-gray-400"
            aria-hidden="true"
          />
        </span>
      </ListboxButton>

      <transition
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions class="z-20 absolute mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-base">
          <ListboxOption
            as="template"
            v-for="option in options"
            :key="option.id"
            :value="option"
            v-slot="{ active, selected }"
          >
            <li :class="[
                active ? 'text-white bg-blue-600' : 'text-gray-900',
                'cursor-default select-none relative py-2 pl-3 pr-9',
              ]">
              <span :class="[
                  selected ? 'font-semibold' : 'font-normal',
                  'block truncate',
                ]">
                {{ option.value }}
              </span>

              <span
                v-if="selected"
                :class="[
                  active ? 'text-white' : 'text-blue-600',
                  'absolute inset-y-0 right-0 flex items-center pr-4',
                ]"
              >
                <CheckIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
  <p class="mt-2 text-sm text-gray-500 text-justify">{{ description }}</p>
</template>

<script>
import { computed } from "vue";

import {
  Listbox,
  ListboxButton,
  ListboxLabel,
  ListboxOption,
  ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/solid";

export default {
  components: {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
    CheckIcon,
    SelectorIcon,
  },

  props: {
    modelValue: {
      type: Object,
      default: {},
    },
    options: {
      type: Array,
      required: true,
    },
    label: {
      type: String,
      default: "",
    },
    description: {
      type: String,
      default: "",
    },
    required: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  emits: ["update:modelValue"],

  setup(props, { emit }) {
    const selected = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    return {
      selected,
    };
  },
};
</script>
