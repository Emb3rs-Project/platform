<template>
  <Listbox as="div" v-model="selected">
    <ListboxLabel class="sr-only"> Change published status </ListboxLabel>
    <div class="relative">
      <div class="inline-flex shadow-sm rounded-md divide-x divide-blue-600">
        <div
          class="relative z-0 inline-flex shadow-sm rounded-md divide-x divide-blue-600"
        >
          <div
            class="relative inline-flex items-center bg-blue-500 py-2 pl-3 pr-4 border border-transparent rounded-l-md shadow-sm text-white"
          >
            <p class="ml-2.5 text-sm font-medium">
              {{ selected.title }}
            </p>
          </div>
          <ListboxButton
            class="relative inline-flex items-center bg-blue-500 p-2 rounded-l-none rounded-r-md text-sm font-medium text-white hover:bg-blue-600 focus:outline-none focus:z-10 focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-blue-500"
          >
            <span class="sr-only">Change published status</span>
            <FilterIcon class="h-5 w-5" aria-hidden="true" />
            <ChevronDownIcon class="h-5 w-5 text-white" aria-hidden="true" />
          </ListboxButton>
        </div>
      </div>

      <transition
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <ListboxOptions
          class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg overflow-hidden bg-white divide-y divide-gray-200 ring-1 ring-black ring-opacity-5 focus:outline-none"
        >
          <ListboxOption
            as="template"
            v-for="option in options"
            :key="option.title"
            :value="option"
            v-slot="{ active, selected }"
          >
            <li
              :class="[
                active ? 'text-white bg-blue-500' : 'text-gray-900',
                'cursor-default select-none relative p-4 text-sm',
              ]"
            >
              <div class="flex flex-col">
                <div class="flex justify-between">
                  <p :class="selected ? 'font-semibold' : 'font-normal'">
                    {{ option.title }}
                  </p>
                  <span
                    v-if="selected"
                    :class="active ? 'text-white' : 'text-blue-500'"
                  >
                    <CheckIcon class="h-5 w-5" aria-hidden="true" />
                  </span>
                </div>
                <p
                  :class="[active ? 'text-blue-200' : 'text-gray-500', 'mt-2']"
                >
                  {{ option.description }}
                </p>
              </div>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
</template>

<script>
import { computed, ref } from "vue";

import {
  Listbox,
  ListboxButton,
  ListboxLabel,
  ListboxOption,
  ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, ChevronDownIcon } from "@heroicons/vue/solid";
import { FilterIcon } from "@heroicons/vue/outline";

export default {
  components: {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
    FilterIcon,
    CheckIcon,
    ChevronDownIcon,
  },

  emits: ["update:modelValue"],

  props: {
    modelValue: {
      type: Object,
      required: true,
    },
    options: {
      type: Array,
      required: true,
    },
  },

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
