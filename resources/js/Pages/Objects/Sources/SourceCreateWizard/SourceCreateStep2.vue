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
                  {{ equip.value }}
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
                  v-for="property in equip.props"
                  :key="property"
                >
                  <div class="sm:col-span-3">
                    <div v-if="property.property.inputType === 'text'">
                      <TextInput
                        v-model="equip.data[property.property.symbolic_name]"
                        :label="property.property.name"
                        :unit="property.unit.symbol"
                        :description="property.property.description"
                        :required="property.required"
                      />
                    </div>
                    <div v-else-if="property.property.inputType === 'select'">
                      <SelectMenu
                        v-model="equip.data[property.property.symbolic_name]"
                        :options="property.property.data.options"
                        :label="property.property.name"
                        :description="property.property.description"
                        :required="property.required"
                      />
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
      </div>
    </div>
  </div>

  <AddEquipmentModal
    v-model="addEquipmentModalIsVisible"
    :equipmentCategories="equipmentCategories"
    :equipment="equipment"
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
import JetInputError from "@/Jetstream/InputError.vue";

import { sortProperties } from "../helpers/sort-properties";
import { transformPropsToData } from "../helpers/transform-props-to-data";
import { validateProperies } from "../helpers/validate-properties";

export default {
  components: {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    AddEquipmentModal,
    PlusIcon,
    PrimaryButton,
    ChevronDownIcon,
    TextInput,
    SelectMenu,
    JetInputError,
  },

  props: {
    equipmentCategories: {
      type: Array,
      required: true,
    },
    equipment: {
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

    const propsEquipment = props.equipment.map((e) => ({
      key: e.id,
      value: e.name,
      parent: e.category_id,
      props: sortProperties(e.template_properties),
      data: transformPropsToData(e.template_properties),
    }));

    const storeEquipment = computed(() => store.getters["source/equipment"]);

    const equipment = ref(
      storeEquipment.value.length
        ? window._.cloneDeep(storeEquipment.value)
        : propsEquipment
    );

    const commitEquipment = window._.debounce(
      () =>
        store.commit("source/setEquipment", {
          equipment: window._.cloneDeep(equipment),
        }),
      500
    );

    watch(equipment, () => commitEquipment(), {
      deep: true,
      immediate: true,
    });

    const onAddEquipment = (equip) => {
      const newEquipment = window._.cloneDeep(equip);

      if (!Object.keys(newEquipment.props).length) return;

      newEquipment.data = transformPropsToData(newEquipment.props);

      equipment.value.push(newEquipment);
    };

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        errors.value = {};

        for (const equip of equipment.value) {
          const properties = equip.props;

          validateProperies(equip, properties, errors.value);
        }

        if (!Object.keys(errors.value).length) ctx.emit("completed");
        else ctx.emit("incompleted");
      }
    );

    return {
      errors,
      addEquipmentModalIsVisible,
      equipment,
      onAddEquipment,
    };
  },
};
</script>

