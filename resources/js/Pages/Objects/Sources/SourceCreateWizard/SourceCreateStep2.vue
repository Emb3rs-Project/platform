<template>
  <!-- Equipment -->
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

  <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-1 sm:gap-4 sm:px-6 sm:py-5">
    <div
      class="flex w-full justify-center py-2"
      v-for="(equip, equipIdx) in equipment"
      :key="equipIdx"
    >
      <div class="w-full">
        <PropertyDisclosure :title="equip.value">
          <div
            class="my-6"
            v-for="property in equip.props"
            :key="property"
          >
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
              <div v-if="property.property.symbolic_name === key.substr(key.indexOf('.') + 1) && +key.substr(0, key.indexOf('.')) === equipIdx">
                <div
                  v-for="(e, eIdx) in error"
                  :key="eIdx"
                >
                  <JetInputError
                    :message="e"
                    class="mt-2"
                  />
                </div>
              </div>
            </div>
          </div>
        </PropertyDisclosure>
      </div>
      <div class="ml-5">
        <button
          type="button"
          class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          @click="onRemoveEquipment(equipIdx)"
        >
          Delete
        </button>
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

import PropertyDisclosure from "@/Components/Disclosures/PropertyDisclosure.vue";
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
    PropertyDisclosure,
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

    const onRemoveEquipment = (index) => equipment.value.splice(index, 1);

    watch(
      () => props.nextStepRequest,
      (nextStepRequest) => {
        if (!nextStepRequest) return;

        // reset the errors so they are always up to date
        errors.value = {};

        for (const [index, equip] of equipment.value.entries()) {
          const properties = equip.props;

          validateProperies(equip, properties, errors.value, index);
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
      onRemoveEquipment,
    };
  },
};
</script>

