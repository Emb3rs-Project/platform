<template>
  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 "
    v-for="equip in equipment"
    :key="equip"
  >
    <div class="sm:col-span-3">
      <div class="bg-white overflow-hidden shadow sm:rounded-lg w-full">
        <div class="px-4 py-5 sm:p-6">
          <Disclosure
            as="div"
            v-slot="{ open }"
          >
            <dt class="text-lg">
              <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
                <span class="font-medium text-gray-900">
                  {{ equip.name }}
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
                <div class="divide-y">
                  <div
                    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                    v-for="property in equip.properties"
                    :key="property"
                  >
                    <div class="sm:col-span-3 ">
                      <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                        <div>
                          <label class="block text-sm font-medium text-gray-500 sm:pt-1">
                            {{ property.key }}
                          </label>
                        </div>
                        <div class="sm:col-span-2">
                          <div class="block text-sm font-medium text-gray-900 sm:pt-1">
                            {{ property.value ?? 'Not defined.' }}
                          </div>
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
</template>

<script>
import { ref } from "vue";

import { sortProperties } from "../helpers/sort-properties";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/solid";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
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
    const equipment = ref([]);

    const matchPropertiesToTemplateProperties = () => {
      const equipment = [];

      const instanceEquipment = props.instance.values.equipment;
      const equipmentTemplates = props.equipment;

      for (const instanceEquip of instanceEquipment) {
        for (const equipmentTemplate of equipmentTemplates) {
          if (instanceEquip.id !== equipmentTemplate.id) continue;

          const equip = {
            name: equipmentTemplate.name,
            properties: [],
          };

          for (const instanceEquipDatum in instanceEquip.data) {
            for (const templateProperty of equipmentTemplate.template_properties) {
              // prettier-ignore
              if (instanceEquipDatum !== templateProperty.property.symbolic_name) continue;

              const value = instanceEquip.data[instanceEquipDatum];

              if (templateProperty.property.inputType === "select") {
                const options = templateProperty.property.data.options;

                for (const option in options) {
                  if (options[option].key === value) {
                    value = options[option].value;

                    break;
                  }
                }
              }

              equip.properties.push({
                key: templateProperty.property.name,
                value: value,
                symbolicName: templateProperty.property.symbolic_name,
                order: templateProperty.order,
              });

              break;
            }
          }

          if (equip.properties.length) equipment.push(equip);

          break;
        }
      }

      for (const equip of equipment) {
        sortProperties(equip.properties);
      }
      return equipment;
    };

    equipment.value = matchPropertiesToTemplateProperties();

    return {
      equipment,
    };
  },
};
</script>
