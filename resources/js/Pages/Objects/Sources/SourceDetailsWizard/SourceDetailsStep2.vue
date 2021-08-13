<template>
  <h1 class="my-4 ml-4">Equipment</h1>
  <Disclosure
    v-slot="{ open }"
    v-for="equip of instance.values.equipments"
    :key="equip.key"
  >
    <DisclosureButton class="flex justify-between w-full px-4 py-2 text-sm font-medium text-left">
      <span>{{ equip.value }}</span>
      <ChevronUpIcon
        :class="open ? 'transform rotate-180' : ''"
        class="w-5 h-5"
      />
    </DisclosureButton>
    <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
      <h1 class="px-4 py-3 font-bold">Properties :</h1>
      <div
        v-for="(datum, name) of equip.data"
        :key="datum"
        class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-2 border-b-[1px]"
      >
        >
        <div>
          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
            {{ getName(equip, name) }}
          </label>
        </div>
        <div class="sm:col-span-2">
          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
            {{ datum.value ? datum.value : datum }}
          </div>
        </div>
      </div>
      <h1 class="px-4 py-3 font-bold">Calculated :</h1>
      <!-- Source Template -->
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-2 border-b-[1px]">
        <div>
          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
            Excess Heat Fluid
          </label>
        </div>
        <div class="sm:col-span-2">
          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
            {{
              instance.values?.script?.excess_heat_fluid ?? "Not Yet Calculated"
            }}
          </div>
        </div>
      </div>

      <!-- Source Template -->
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-2 border-b-[1px]">
        <div>
          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
            Excess Heat Supply Temperature (ºc)
          </label>
        </div>
        <div class="sm:col-span-2">
          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
            {{
              instance.values?.script?.excess_heat_supply_temperature ??
              "Not Yet Calculated"
            }}
          </div>
        </div>
      </div>

      <!-- Source Template -->
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-2 border-b-[1px]">
        <div>
          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
            Excess Heat Flowrate (kg/h)
          </label>
        </div>
        <div class="sm:col-span-2">
          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
            {{
              instance.values?.script?.excess_heat_flowrate ??
              "Not Yet Calculated"
            }}
          </div>
        </div>
      </div>

      <!-- Source Template -->
      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-2 border-b-[1px]">
        <div>
          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
            Excess Heat Capacity (kW)
          </label>
        </div>
        <div class="sm:col-span-2">
          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
            {{
              instance.values?.script?.excess_heat_capacity ??
              "Not Yet Calculated"
            }}
          </div>
        </div>
      </div>
    </DisclosurePanel>
  </Disclosure>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";

import AppLayout from "@/Layouts/AppLayout.vue";
import SlideOver from "@/Components/SlideOver.vue";
import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronUpIcon } from "@heroicons/vue/solid";

export default {
  components: {
    AppLayout,
    SlideOver,
    SelectMenu,
    TextInput,
    PrimaryButton,
    SecondaryOutlinedButton,
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronUpIcon,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
    equipment: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    console.log(props.instance);

    const getName = (equip, name) => {
      const newName = equip.props.find(
        (p) => p.property.symbolic_name === name
      )?.property;
      return newName?.name;
    };

    return {
      getName,
    };
  },
};
</script>

<style>
</style>
