<template>
  <!-- Equipment -->
  <div class="flex justify-end justify-items-center p-5">
    <PrimaryButton
      type="button"
      :disabled="disabled"
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
    v-for="(equip, equipIdx) in equipment"
    :key="equipIdx"
  >
    <div class="sm:col-span-3 flex">
      <div class="bg-white shadow sm:rounded-lg w-5/6">
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
                  v-for="templateProperty in equip.props"
                  :key="templateProperty"
                >
                  <div class="sm:col-span-3">
                    <div v-if="templateProperty.property.inputType === 'select'">
                      <SelectMenu
                        v-model="equip.data[templateProperty.property.symbolic_name]"
                        :options="templateProperty.property.data.options"
                        :label="templateProperty.property.name"
                        :description="templateProperty.property.description"
                        :required="templateProperty.required"
                      />
                    </div>
                    <div v-else>
                      <TextInput
                        v-model="equip.data[templateProperty.property.symbolic_name]"
                        :label="templateProperty.property.name"
                        :unit="templateProperty.unit.symbol"
                        :description="templateProperty.property.description"
                        :required="templateProperty.required"
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
                          v-if="key.includes(templateProperty.property.symbolic_name)"
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
      <div class="ml-5 py-5">
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
    :equipment="propsEquipment"
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
import { transformData } from "../helpers/transform-data";
import { validateProperies } from "../helpers/validate-properties";

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
    JetInputError,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
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
    templates: {
      type: Array,
      required: true,
    },
  },

  emits: ["completed", "incompleted"],

  setup(props, ctx) {
    const store = useStore();

    const errors = ref({});

    const storeTemplate = computed(() => store.getters["source/template"]);
    const disabled = computed(() => !props.templates.find((t) => t.id === storeTemplate.value.key).values.equipments);

    const addEquipmentModalIsVisible = ref(false);

    const propsEquipment = computed(() =>
      props.equipment.map((e) => ({
        key: e.id,
        value: e.name,
        parent: e.category_id,
        props: sortProperties(e.template_properties),
        data: transformPropsToData(e.template_properties),
      }))
    );

    const instanceEquipment = computed(() => {
      const equipments = [];

      for (const _instanceEquipment of props.instance.values.equipment) {
        const propsEquip = propsEquipment.value.find(
          (propsEquip) => propsEquip.key === _instanceEquipment.id
        );

        if (!propsEquip) continue;

        equipments.push({
          key: propsEquip.key,
          identify: _instanceEquipment.identify ?? Date.now(),
          value: propsEquip.value,
          parent: propsEquip.parent,
          props: propsEquip.props,
          data: transformData(_instanceEquipment.data, propsEquip.props),
        });
      }

      return equipments;
    });

    const storeEquipment = computed(() => store.getters["source/equipment"]);

    const auxEquipment = ref(
      storeEquipment.value.length
          ? storeEquipment.value
          : instanceEquipment.value
    );
    
    const equipment = ref([]);

    const commitSourceEquipment = window._.debounce(
      () =>
        store.commit("source/setEquipment", {
          equipment: window._.cloneDeep(equipment.value),
        }),
      500
    );

    watch(
      () => equipment,
      () => {
        commitSourceEquipment();
      },
      {
        deep: true,
        immediate: true,
      }
    );

    equipment.value = disabled.value ? [] : auxEquipment.value;

    const onAddEquipment = (newEquipment) => {
      const newEquip = window._.cloneDeep(newEquipment);

      if (!Object.keys(newEquip.props).length) return;

      for (const _templateProperty of newEquip.props) {
        newEquip.data[_templateProperty.property.symbolic_name] =
          _templateProperty.default_value;
      }

      newEquip.data = transformData(newEquip.data, newEquip.props);

      equipment.value.push(newEquip);
    };

    const onRemoveEquipment = (index) => equipment.value.splice(index, 1);

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
      propsEquipment,
      disabled,
      onAddEquipment,
      onRemoveEquipment,
    };
  },
};
</script>
