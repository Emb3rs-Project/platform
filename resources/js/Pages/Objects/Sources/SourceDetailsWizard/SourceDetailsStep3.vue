<template>
  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 "
    v-for="process in processes"
    :key="process"
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
                  {{ process.name }}
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
                    v-for="property in process.properties"
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
                <div v-if="process.processElements.length" class="text-lg font-semibold"> Process Elements</div>
                <div
                  class="space-y-1 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:py-5 "
                  v-for="processElement in process.processElements"
                  :key="processElement"
                >
                  <div class="sm:col-span-3">
                    <div class="bg-white overflow-hidden shadow sm:rounded-lg w-full">
                      <div class="py-5 sm:p-6">
                        <Disclosure
                          as="div"
                          v-slot="{ open }"
                        >
                          <dt class="text-lg">
                            <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
                              <span class="font-medium text-gray-900">
                                {{ processElement.name }}
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
                                  v-for="propertyElement in processElement.properties"
                                  :key="propertyElement"
                                >
                                  <div class="sm:col-span-3 ">
                                    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                                      <div>
                                        <label class="block text-sm font-medium text-gray-500 sm:pt-1">
                                          {{ propertyElement.key }}
                                        </label>
                                      </div>
                                      <div class="sm:col-span-2">
                                        <div class="block text-sm font-medium text-gray-900 sm:pt-1">
                                          {{ propertyElement.value ?? 'Not defined.' }}
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
    processes: {
      type: Array,
      required: true,
    },
    equipment: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const processes = ref([]);

    const matchPropertiesToElements = (elements) => {
      const processElements = [];

      for (const element of elements) {
        const processElement = {
          name: element.value,
          properties: [],
        };
        for (const templateProperty of element.props) {
          for (const instanceProcessDatum in element.data) {
            // prettier-ignore
            if (instanceProcessDatum !== templateProperty.property.symbolic_name) continue;

            let value = element.data[instanceProcessDatum];

            if (templateProperty.property.inputType === "select") {
              const options = templateProperty.property.data.options;

              for (const option in options) {
                if (options[option].key === value) {
                  value = options[option].value;

                  break;
                }
              }
            }

            processElement.properties.push({
              key: templateProperty.property.name,
              value: value,
              symbolicName: templateProperty.property.symbolic_name,
              order: templateProperty.order,
            });
          }
        }
        
        if (processElement.properties.length) processElements.push(processElement);

      }
      for (const process of processElements) {
        sortProperties(process.properties);
      }

      return processElements;
    };

    const matchPropertiesToTemplateProperties = () => {
      const processes = [];

      const instanceProcesses = props.instance.values.processes;
      const instanceEquipments = props.instance.values.equipment;
      const processTemplates = props.processes;
      const equipmentTemplates = props.equipment;

      for (const instanceProcess of instanceProcesses) {
        const process = {
          name: '',
          properties: [],
          processElements: matchPropertiesToElements(instanceProcess.processElements),
        };

        for (const processTemplate of processTemplates) {
          if (instanceProcess.id !== processTemplate.id) continue;

          process.name = processTemplate.name;

          for (const instanceProcessDatum in instanceProcess.data) {
            for (const templateProperty of processTemplate.template_properties) {
              // prettier-ignore
              if (instanceProcessDatum !== templateProperty.property.symbolic_name) continue;

              let value = instanceProcess.data[instanceProcessDatum];

              if (templateProperty.property.inputType === "select") {
                if (templateProperty.property.symbolic_name !== "equipment_selected") {
                  const options = templateProperty.property.data.options;

                  for (const option in options) {
                    if (options[option].key === value) {
                      value = options[option].value;

                      break;
                    }
                  }
                } else {
                  for (const item of equipmentTemplates) {
                    const element = instanceEquipments.filter(e => e.identify && e.identify == value)[0];
                    value = element ? `${item.name} | ${element.data['name']}` : 'Not defined.';
                  }
                }
              }

              process.properties.push({
                key: templateProperty.property.name,
                value: value,
                symbolicName: templateProperty.property.symbolic_name,
                order: templateProperty.order,
              });

              break;
            }
          }

          if (process.properties.length) processes.push(process);

          break;
        }
      }

      for (const process of processes) {
        sortProperties(process.properties);
      }
      return processes;
    };

    processes.value = matchPropertiesToTemplateProperties();

    return {
      processes,
    };
  },
};
</script>
