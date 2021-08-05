<template>
  <!-- Processes -->
  <div class="flex justify-end justify-items-center p-5">
    <PrimaryButton
      type="button"
      @click="addProcessModalIsVisible = true"
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
          <DisclosurePanel
            as="dd"
            class="mt-2 pr-12"
          >
            <div
              class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
              v-for="property in process.props"
              :key="property"
            >
              <div class="sm:col-span-3">
                <div v-if="property.property.inputType === 'text'">
                  <TextInput
                    v-model="process.data[property.property.symbolic_name]"
                    :unit="property.unit.symbol"
                    :placeholder="property.property.name"
                    :label="property.property.name"
                    :description="property.property.description"
                    :required="property.required"
                  />
                </div>
                <div v-else-if="property.property.inputType === 'select'">
                  <SelectMenu
                    v-model="process.data[property.property.symbolic_name]"
                    :options="property.property.data.options"
                    :label="property.property.name"
                    :description="property.property.description"
                    :required="property.required"
                  >
                  </SelectMenu>
                </div>
                <div
                  v-for="(error, key) in errors"
                  :key="key"
                >
                  <div
                    v-for="subError in error"
                    :key="subError"
                  >
                    <JetInputError
                      v-if="key.includes(property.property.symbolic_name)"
                      :message="subError"
                      class="mt-2"
                    />
                  </div>
                </div>
              </div>
            </div>
          </DisclosurePanel>
        </transition>
      </Disclosure>
    </div>
    <pre>{{process.data}}</pre>
  </div>

  <add-process-modal
    v-model="addProcessModalIsVisible"
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

import JetInputError from "@/Jetstream/InputError.vue";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    AddProcessModal,
    PlusIcon,
    PrimaryButton,
    ChevronDownIcon,
    TextInput,
    SelectMenu,
    JetInputError,
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

    const errors = ref({});

    const addProcessModalIsVisible = ref(false);

    const transformPropsToData = (properties) => {
      const data = {};

      for (const property of properties) {
        const inputType = property.property.inputType;

        if (property.property) {
          const placeholder = inputType === "select" ? {} : "";

          const key = property.property.symbolic_name;

          data[key] = property.property.default_value ?? placeholder;
        }
      }

      return data;
    };

    const propProcesses = props.processes.map((p) => ({
      key: p.id,
      value: p.name,
      parent: p.category_id,
      props: p.template_properties,
      data: transformPropsToData(p.template_properties),
    }));

    const storeProcesses = computed(() => store.getters["source/processes"]);

    const processes = ref(
      storeProcesses.value.length
        ? window._.cloneDeep(storeProcesses.value)
        : propProcesses
    );

    const commitProcesses = window._.debounce(
      () =>
        store.commit("source/setProcesses", {
          equipments: window._.cloneDeep(processes),
        }),
      500
    );

    watch(processes, () => commitProcesses(), {
      deep: true,
      immediate: true,
    });

    const onAddProcess = (process) => {
      const newProcess = window._.cloneDeep(process);

      if (!Object.keys(newProcess.props).length === 0) return;

      newProcess.data = transformPropsToData(newProcess.props);

      processes.value.push(newProcess);
    };

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        errors.value = {};

        for (const process of processes.value) {
          for (const property of process.props) {
            const propertyErrors = [];

            const inputType = property.property.inputType;
            const dataType = property.property.dataType;
            const symbolicName = property.property.symbolic_name;
            const value = process.data[property.property.symbolic_name];
            const propertyName = property.property.name.toLowerCase();

            let propertyCopy = value;

            if (inputType === "select") {
              // if the property has a value, get it and re-assign the property as a string
              if (Object.keys(value).length) {
                propertyCopy = value.value;
              } else {
                propertyCopy = "";
              }
            }

            if (property.required) {
              if (!propertyCopy)
                propertyErrors.push(`The ${propertyName} field is required.`);
            }

            switch (dataType.toLowerCase()) {
              case "text":
                if (typeof propertyCopy !== "string")
                  propertyErrors.push(
                    `The ${propertyName} field must be a string.`
                  );
                break;
              case "string":
                if (typeof propertyCopy !== "string")
                  propertyErrors.push(
                    `The ${propertyName} field must be a string.`
                  );
                break;
              case "number":
                if (isNaN(propertyCopy))
                  propertyErrors.push(
                    `The ${propertyName} field must be numeric.`
                  );
                break;
              case "float":
                if (Number.isInteger(propertyCopy))
                  propertyErrors.push(
                    `The ${propertyName} field must be float.`
                  );
                break;
              case "datetime":
                break;

              default:
                break;
            }

            if (propertyErrors.length)
              errors.value[symbolicName] = propertyErrors;
          }
        }

        if (!Object.keys(errors.value).length) ctx.emit("completed");
        else ctx.emit("incompleted");
      }
    );

    return {
      errors,
      addProcessModalIsVisible,
      processes,
      onAddProcess,
    };
  },
};
</script>

