<template>
  <!-- Equipments -->
  <div class="flex justify-end justify-items-center p-5">
    <PrimaryButton
      type="button"
      @click="modalIsVisible = true"
    >
      <DatabaseIcon
        class="h-6 w-6 mr-2"
        aria-hidden="true"
      />
      Add Equipment
    </PrimaryButton>
  </div>

  <div
    class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
    v-for="equipment in existingEquipments"
    :key="equipment"
  >
    <div class="sm:col-span-3">
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
              v-for="property in equipment.props"
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
                    v-model="equipment.data[property.property.symbolic_name]"
                    :unit="property.unit.symbol"
                    :placeholder="property.property.name"
                    :required="property.required"
                  >
                  </TextInput>
                </div>
                <div v-else-if="property.property.inputType === 'select'">
                  <SelectMenu
                    v-model="equipment.data[property.property.symbolic_name]"
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

  <AddEquipmentModal
    v-model="modalIsVisible"
    :equipmentsCategories="equipmentsCategories"
    :equipments="equipments"
    @confirmation="onAddEquipment"
  />
</template>

<script>
import { computed, ref, watch } from "vue";
import { useStore } from "vuex";

import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { DatabaseIcon, ChevronDownIcon } from "@heroicons/vue/outline";

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
    DatabaseIcon,
    PrimaryButton,
    AddEquipmentModal,
    SelectMenu,
    TextInput,
  },

  props: {
    instance: {
      type: Object,
      required: true,
    },
    equipmentsCategories: {
      type: Array,
      required: true,
    },
    equipments: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();
    const modalIsVisible = ref(false);

    const equipments = computed(() =>
      props.equipments.map((e) => ({
        key: e.id,
        value: e.name,
        parent: e.category_id,
        props: e.template_properties,
        data: {},
      }))
    );
    const sessionEquipments = computed(
      () => store.getters["sources/equipments"]
    );
    const existingEquipments = ref(
      sessionEquipments.value.length
        ? sessionEquipments.value
        : props.instance.values.equipments
    );

    watch(
      existingEquipments,
      (equipments) => {
        store.dispatch("sources/setEquipments", {
          equipments: JSON.parse(JSON.stringify(equipments)),
        });
      },
      { immediate: true, deep: true }
    );

    const onAddEquipment = (equipment) => {
      const newEquipment = JSON.parse(JSON.stringify(equipment));

      if (!Object.keys(newEquipment.props).length === 0) return;

      for (const property of newEquipment.props) {
        newEquipment.data[property.property.symbolic_name] =
          property.default_value;
      }

      //   existingEquipments.value = [...existingEquipments.value, newEquipment];
      existingEquipments.value.push(newEquipment);
    };

    return {
      modalIsVisible,
      equipments,
      existingEquipments,
      onAddEquipment,
    };
  },
};
</script>

