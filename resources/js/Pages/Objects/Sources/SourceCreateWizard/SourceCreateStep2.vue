<template>
  <!-- Equipments -->
  <div class="flex justify-end justify-items-center p-5">
    <PrimaryButton
      type="button"
      @click="addEquipmentModalIsVisible = true"
    >
      <PlusIcon
        class="h-6 w-6 mr-2"
        aria-hidden="true"
      />
      New Equipment
    </PrimaryButton>
  </div>

  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5 "
    v-for="equipment in equipments"
    :key="equipment"
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
                  {{ equipment.value }}
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
                <div
                  class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                  v-for="property in equipment.props"
                  :key="property"
                >
                  <div class="col-span-3">
                    <div v-if="property.property.inputType === 'text'">
                      <TextInput
                        v-model="equipment.data[property.property.symbolic_name]"
                        :unit="property.unit.symbol"
                        :placeholder="property.property.name"
                        :label="property.property.name"
                        :description="property.property.description"
                        :required="property.required"
                      >
                      </TextInput>
                    </div>
                    <div v-else-if="property.property.inputType === 'select'">
                      <SelectMenu
                        v-model="equipment.data[property.property.symbolic_name]"
                        :options="property.property.data.options"
                        :label="property.property.name"
                        :description="property.property.description"
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
    </div>
    <pre>{{equipment.data}}</pre>
  </div>

  <AddEquipmentModal
    v-model="addEquipmentModalIsVisible"
    :equipmentsCategories="equipmentsCategories"
    :equipments="equipments"
    @confirmation="onAddEquipment"
  />
</template>

<script>
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/outline";
import { PlusIcon } from "@heroicons/vue/solid";

import SelectMenu from "@/Components/Forms/SelectMenu.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import AddEquipmentModal from "@/Components/Modals/AddEquipmentModal.vue";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    ChevronDownIcon,
    PlusIcon,
    PrimaryButton,
    AddEquipmentModal,
    SelectMenu,
    TextInput,
  },

  props: {
    equipmentsCategories: {
      type: Array,
      required: true,
    },
    equipments: {
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

    const addEquipmentModalIsVisible = ref(false);

    const propsEquipments = computed(() =>
      props.equipments.map((e) => ({
        key: e.id,
        value: e.name,
        parent: e.category_id,
        props: e.template_properties,
        data: {},
      }))
    );

    const storeEquipments = computed(() => store.getters["source/equipments"]);

    const equipments = ref(
      storeEquipments.value.length
        ? window._.cloneDeep(storeEquipments.value)
        : propsEquipments.value
    );

    watch(
      equipments,
      (equipments) => {
        for (const equipment of equipments) {
          if (!equipment.props.length) return; // edge case

          for (const property of equipment.props) {
            const inputType = property.property.inputType;

            if (property.property) {
              const placeholder = inputType === "select" ? {} : "";

              const key = property.property.symbolic_name;

              console.log(
                key,
                "=",
                property.property.default_value ?? placeholder
              );

              console.log(
                (equipment.data[key] =
                  property.property.default_value ?? placeholder)
              );

              // equipment.data[key] =
              //   property.property.default_value ?? placeholder;
            }
          }
        }
      },
      {
        deep: true,
        immediate: true,
      }
    );

    const commitEquipments = window._.debounce(
      () =>
        store.commit("source/setEquipments", {
          equipments: window._.cloneDeep(equipments),
        }),
      500
    );

    watch(equipments, () => commitEquipments(), {
      deep: true,
      immediate: true,
    });

    const onAddEquipment = (equipment) => {
      const newEquipment = window._.cloneDeep(equipment);

      if (!Object.keys(newEquipment.props).length) return;

      for (const property of newEquipment.props) {
        if (property.property) {
          const inputType = property.property.inputType;

          const placeholder = inputType === "select" ? {} : "";

          const key = property.property.symbolic_name;

          newEquipment.data[key] =
            property.property.default_value ?? placeholder;
        }
      }

      equipments.value.push(newEquipment);
    };

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        errors.value = {};

        for (const equipment of equipments.value) {
          for (const property of equipment.props) {
            const propertyErrors = [];

            const inputType = property.property.inputType;
            const dataType = property.property.dataType;
            const symbolicName = property.property.symbolic_name;
            const value = equipment.data[property.property.symbolic_name];
            const propertyName = property.property.name.toLowerCase();

            let propertyCopy = value;

            console.log(value);
            console.log(equipment);

            // if (inputType === "select") {
            //   // if the property has a value, get it and re-assign the property as a string
            //   if (Object.keys(value).length) {
            //     propertyCopy = value.value;
            //   } else {
            //     propertyCopy = "";
            //   }
            // }

            // if (property.required)
            //   if (!propertyCopy)
            //     propertyErrors.push(`The ${propertyName} field is required.`);
            //   else propertyErrors.push(`EDV EIMAI GAMIOLES`);

            // switch (dataType.toLowerCase()) {
            //   case "text":
            //     if (typeof propertyCopy !== "string")
            //       propertyErrors.push(
            //         `The ${propertyName} field must be a string.`
            //       );
            //     break;
            //   case "string":
            //     if (typeof propertyCopy !== "string")
            //       propertyErrors.push(
            //         `The ${propertyName} field must be a string.`
            //       );
            //     break;
            //   case "number":
            //     if (isNaN(propertyCopy))
            //       propertyErrors.push(
            //         `The ${propertyName} field must be numeric.`
            //       );
            //     break;
            //   case "float":
            //     if (Number.isInteger(propertyCopy))
            //       propertyErrors.push(
            //         `The ${propertyName} field must be float.`
            //       );
            //     break;
            //   case "datetime":
            //     break;

            //   default:
            //     break;
            // }

            if (propertyErrors.length)
              errors.value[symbolicName] = propertyErrors;
          }
        }

        // if (!Object.keys(errors.value).length) ctx.emit("completed");
        // else ctx.emit("incompleted");
        ctx.emit("incompleted");
      }
    );

    return {
      addEquipmentModalIsVisible,
      equipments,
      onAddEquipment,
    };
  },
};
</script>

