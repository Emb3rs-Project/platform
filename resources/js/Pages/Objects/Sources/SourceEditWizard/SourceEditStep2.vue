<template>
  {{}}
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

  setup(props) {
    const store = useStore();
    const modalIsVisible = ref(false);
    console.log(store.state.source);

    // const equipments = computed(() =>
    //   props.equipments.map((e) => ({
    //     key: e.id,
    //     value: e.name,
    //     parent: e.category_id,
    //     props: e.template_properties,
    //     data: {},
    //   }))
    // );
    // const sessionEquipments = computed(
    //   () => store.getters["sources/equipments"]
    // );
    // const existingEquipments = ref(
    //   sessionEquipments.value.length
    //     ? sessionEquipments.value
    //     : props.instance.values.equipments
    // );

    // watch(
    //   existingEquipments,
    //   (equipments) => {
    //     store.dispatch("sources/setEquipments", {
    //       equipments: JSON.parse(JSON.stringify(equipments)),
    //     });
    //   },
    //   { immediate: true, deep: true }
    // );

    // const onAddEquipment = (equipment) => {
    //   const newEquipment = JSON.parse(JSON.stringify(equipment));

    //   if (!Object.keys(newEquipment.props).length === 0) return;

    //   for (const property of newEquipment.props) {
    //     newEquipment.data[property.property.symbolic_name] =
    //       property.default_value;
    //   }

    //   //   existingEquipments.value = [...existingEquipments.value, newEquipment];
    //   existingEquipments.value.push(newEquipment);
    // };

    // return {
    //   modalIsVisible,
    //   equipments,
    //   existingEquipments,
    //   onAddEquipment,
    // };
  },
};
</script>

