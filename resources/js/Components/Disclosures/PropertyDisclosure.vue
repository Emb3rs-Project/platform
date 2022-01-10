<template>
  <Disclosure
    as="div"
    v-slot="{ open }"
    :defaultOpen="defaultOpen"
  >
    <dt class="text-lg">
      <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
        <span class="font-bold text-gray-900">
          {{ title }}
        </span>
        <span class="ml-6 h-7 flex items-center">
          <ChevronDownIcon
            :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform']"
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
      <DisclosurePanel as="dd">
        <slot></slot>
      </DisclosurePanel>
    </transition>
  </Disclosure>
</template>

<script>
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
  },

  props: {
    defaultOpen: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      required: true,
    },
  },
};
</script>

