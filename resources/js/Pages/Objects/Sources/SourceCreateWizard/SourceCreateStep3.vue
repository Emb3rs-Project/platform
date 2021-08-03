<template>
  <!-- Processes -->
  <div class="flex justify-end justify-items-center p-5">
    <PrimaryButton
      type="button"
      @click="modalIsVisible = true"
    >
      <PlusIcon
        class="h-6 w-6 mr-2"
        aria-hidden="true"
      />
      New Process
    </PrimaryButton>
  </div>
  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    v-for="process in processes"
    :key="process"
  >
    <div class="sm:col-span-3">
      <Disclosure
        as="div"
        v-slot="{ open }"
      >
        <dt class="text-lg">
          <DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 focus:outline-none">
            <span class="font-medium text-gray-900">
              {{ process.value }}
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
          <DisclosurePanel
            as="dd"
            class="mt-2 pr-12"
          >
            <div
              class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
              v-for="property in process.props"
              :key="property"
            >
              <div>
                <label class="block text-sm font-medium text-gray-900 sm:mt-px sm:pt-3">
                  {{ property.property.name }}
                </label>
              </div>
              <div class="sm:col-span-2">
                <div v-if="property.property.inputType === 'text'">
                  <TextInput
                    v-model="process.data[property.property.symbolic_name]"
                    :unit="property.unit.symbol"
                    :placeholder="property.property.name"
                    :required="property.required"
                  >
                  </TextInput>
                </div>
                <div v-else-if="property.property.inputType === 'select'">
                  <SelectMenu
                    v-model="process.data[property.property.symbolic_name]"
                    :options="property.property.data.options"
                    :required="property.required"
                  >
                  </SelectMenu>
                </div>
              </div>
            </div>
          </DisclosurePanel>
        </transition>
      </Disclosure>
    </div>
  </div>

  <add-process-modal
    v-model="modalIsVisible"
    :processesCategories="processesCategories"
    :processes="processes"
    @confirmation="onAddProcess"
  >
  </add-process-modal>
</template>

<script>
import { ref, watch, computed } from "vue";
import { useStore } from "vuex";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";
import { PlusIcon } from "@heroicons/vue/solid";

import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "../../../../Components/PrimaryButton.vue";
import AddProcessModal from "@/Components/Modals/AddProcessModal.vue";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    PlusIcon,
    ChevronDownIcon,
    AddProcessModal,
    SelectMenu,
    TextInput,
    PrimaryButton,
  },

  props: {
    processesCategories: {
      type: Array,
      required: true,
    },
    processes: {
      type: Array,
      required: true,
    },
    nextStepRequest: {
      type: Boolean,
      required: true,
    },
  },

  emits: ["completed", "incompleted"],

  setup(props, ctx) {
    const store = useStore();
    const modalIsVisible = ref(false);
    const processes = ref([]);

    const errors = ref({});

    const storeProcesses = computed(() => store.getters["source/processes"]);

    const propProcesses = props.processes.map((p) => ({
      key: p.id,
      value: p.name,
      parent: p.category_id,
      props: p.template_properties,
      data: {},
    }));

    const init = () => {
      if (storeProcesses.value.length) {
        processes.value = JSON.parse(JSON.stringify(storeProcesses.value));
        return;
      }

      store.dispatch("source/setProcesses", {
        processes: JSON.parse(JSON.stringify(propProcesses)),
      });

      processes.value = propProcesses;
    };

    init();

    watch(
      processes,
      (processes) => {
        store.dispatch("source/setProcesses", {
          processes: JSON.parse(JSON.stringify(processes)),
        });
      },
      { deep: true }
    );

    const onAddProcess = (process) => {
      const newProcess = JSON.parse(JSON.stringify(process));

      if (!Object.keys(newProcess.props).length === 0) return;

      for (const property of newProcess.props) {
        newProcess.data[property.property.symbolic_name] =
          property.default_value;
      }

      processes.value = [...processes.value, newProcess];
    };

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        // errors.value = {};

        // const properties = selectedTemplate.value.properties;

        // for (const property of properties) {
        //   const propertyErrors = [];

        //   const inputType = property.property.inputType;
        //   const dataType = property.property.dataType;
        //   const symbolicName = property.property.symbolic_name;
        //   const value = source.value.data[property.property.symbolic_name];
        //   const propertyName = property.property.name.toLowerCase();

        //   let propertyCopy = value;

        //   if (inputType === "select") {
        //     // if the property has a value, get it and re-assign the property as a string
        //     if (Object.keys(value).length) {
        //       propertyCopy = value.value;
        //     } else {
        //       propertyCopy = "";
        //     }
        //   }

        //   if (property.required) {
        //     if (!propertyCopy)
        //       propertyErrors.push(`The ${propertyName} field is required.`);
        //   }

        //   switch (dataType.toLowerCase()) {
        //     case "text":
        //       if (typeof propertyCopy !== "string")
        //         propertyErrors.push(
        //           `The ${propertyName} field must be a string.`
        //         );
        //       break;
        //     case "string":
        //       if (typeof propertyCopy !== "string")
        //         propertyErrors.push(
        //           `The ${propertyName} field must be a string.`
        //         );
        //       break;
        //     case "number":
        //       if (isNaN(propertyCopy))
        //         propertyErrors.push(
        //           `The ${propertyName} field must be numeric.`
        //         );
        //       break;
        //     case "float":
        //       if (Number.isInteger(propertyCopy))
        //         propertyErrors.push(`The ${propertyName} field must be float.`);
        //       break;
        //     case "datetime":
        //       break;

        //     default:
        //       break;
        //   }

        //   if (propertyErrors.length)
        //     errors.value[`source.data.${symbolicName}`] = propertyErrors;
        // }

        if (!Object.keys(errors.value).length) ctx.emit("completed");
        else ctx.emit("incompleted");
      }
    );

    return {
      modalIsVisible,
      processes,
      onAddProcess,
    };
  },
};
</script>

