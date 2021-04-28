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
              class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
              v-for="property in equipment.props"
              :key="property"
            >
              <div>
                <label
                  class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3"
                >
                  {{ property.property.name }}
                </label>
              </div>
              <div class="sm:col-span-2">
                <div v-if="property.property.inputType === 'text'">
                  <text-input
                    v-model="
                      form.equipment.data[property.property.symbolic_name]
                    "
                    :unit="property.unit.symbol"
                    :placeholder="property.property.name"
                    :required="property.required"
                  >
                  </text-input>
                </div>
                <div v-else-if="property.property.inputType === 'select'">
                  <select-menu
                    v-model="
                      form.equipment.data[property.property.symbolic_name]
                    "
                    :options="property.property.data.options"
                    :required="property.required"
                  >
                  </select-menu>
                </div>
              </div>
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
import PrimaryButton from "../../../../Components/NewLayout/PrimaryButton.vue";
// import Disclosure from "../../../../Components/NewLayout/Wizards/Disclosure.vue";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,

    SelectMenu,
    TextInput,
    PrimaryButton,
    // Disclosure,
  },

  props: {
    objects: {
      type: Array,
      required: true,
    },
  },

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
    const equipments = props.objects.map((o) => ({
      key: o.id,
      value: o.name,
      props: o.template_properties,
    }));

    return {
      form,
      equipments,
    };
  },
};
</script>

