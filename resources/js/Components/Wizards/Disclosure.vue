<template>
  <div class="max-w-3xl mx-auto divide-y-2 divide-gray-200">
    <dl class="divide-y divide-gray-200">
      <Disclosure
        as="div"
        v-for="object in objects"
        :key="object.key"
        class="pt-6"
        v-slot="{ open }"
      >
        <dt class="text-lg">
          <DisclosureButton
            class="text-left w-full flex justify-between items-start text-gray-400"
          >
            <span class="font-medium text-gray-900">
              {{ object.value }}
            </span>
            <span class="ml-6 h-7 flex items-center">
              <ChevronDownIcon
                :class="[
                  open ? '-rotate-180' : 'rotate-0',
                  'h-6 w-6 transform',
                ]"
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
          <DisclosurePanel as="dd" class="mt-2 pr-12">
            <p
              v-for="property in object.props"
              :key="property"
              class="text-base text-gray-500"
            >
              Unit: {{ property.unit.name }} Value: {{ property.property.name }}
            </p>
            <pre>{{ object.props }}</pre>
          </DisclosurePanel>
        </transition>
      </Disclosure>
    </dl>
  </div>
</template>

<script>
import { computed, ref } from "vue";
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
    modelValue: {
      type: Array,
      required: true,
    },
    objects: {
      type: Array,
      required: true,
    },
  },

  emits: ["update:modelValue"],

  setup(props, { emit }) {
    const open = ref(false);

    // used for getting the checked equipments
    const checked = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    return {
      open,
      checked,
    };
  },
};
</script>
