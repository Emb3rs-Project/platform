<template>
  <!-- TODO: maybe modularize it more, we'll see -->
  <!-- Resources -->
  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    v-for="equipment in equipments"
    :key="equipment"
  >
    <div class="sm:col-span-3">
      <Disclosure as="div" v-slot="{ open }">
        <dt class="text-lg">
          <DisclosureButton
            class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none"
          >
            <span class="font-medium text-gray-900">
              {{ equipment.value }}
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
            <div
              class="bg-gray-800 p-2 text-sm font-mono text-white overflow-x-scroll"
            >
              <pre>{{ equipment.script }}</pre>
            </div>
          </DisclosurePanel>
        </transition>
      </Disclosure>
    </div>
  </div>
</template>

<script>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";

import SelectMenu from "../../../../Components/NewLayout/Forms/SelectMenu.vue";
import TextInput from "../../../../Components/NewLayout/Forms/TextInput.vue";
// import Disclosure from "../../../../Components/NewLayout/Wizards/Disclosure.vue";

import ESCRIPT from "./info";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,

    SelectMenu,
    TextInput,
    // Disclosure,
  },

  props: {
    // modelValue: {
    //   type: Object,
    //   required: true,
    // },
    objects: {
      type: Array,
      required: true,
    },
  },

  emits: ["completed"],

  setup(props) {
    const form = useForm({
      resources: {
        data: {},
      },
    });

    watch(
      form,
      (form) => {
        console.log(form);
        // the component can return true for completed or false otherwise
      },
      { deep: true }
    );
    const checkedEquipments = ref([]);
    const equipments1 = props.objects.map((o) => ({
      key: o.id,
      value: o.name,
      props: o.template_properties,
      script: ESCRIPT,
    }));

    const equipments = [...equipments1];

    return {
      form,
      equipments,
      checkedEquipments,
    };
  },
};
</script>

