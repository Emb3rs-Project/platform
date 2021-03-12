<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sources
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Source</h1>
    </div>
    <form @submit.prevent="submit">
      <div class="flex p-5 gap-2">
        <div class="w-6/12 md:min-h-content md:pr-4">
          <select-row
            class="mt-5"
            desc="Source Templates"
            :options="mainTemplates"
            v-model="selectedTemplate"
          >
            Template
          </select-row>
          <div v-for="prop in properties" :key="prop.id" class="my-4">
            <input-row
              :desc="prop.property.description"
              v-model="form.source[prop.property.symbolic_name]"
              v-if="prop.property.inputType === 'text'"
              :required="prop.required"
            >
              {{ prop.property.name }}
              <span v-if="prop.unit.symbol">({{ prop.unit.symbol }})</span>
            </input-row>

            <select-row
              :desc="prop.property.description"
              :options="prop.property.data.options"
              v-model="form.source[prop.property.symbolic_name]"
              v-if="prop.property.inputType === 'select'"
              :required="prop.required"
            >
              {{ prop.property.name }}
              <span v-if="prop.unit.symbol">({{ prop.unit.symbol }})</span>
            </select-row>
          </div>
          <select-row
            class="mt-5"
            desc="Template"
            :options="equipTemplates"
            v-model="selectedEquipment"
          >
            Add Equipment
          </select-row>
        </div>
        <div class="w-6/12 max-h-screen">
          <leaflet-map></leaflet-map>
        </div>
      </div>

      <div class="w-full my-5 px-16 flex justify-end">
        <jet-button :disabled="form.processing"> Create Source </jet-button>
      </div>
    </form>
  </app-layout>
</template>

<script>
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";

import { ref, watch } from "vue";

export default {
  components: {
    AppLayout,
    LeafletMap,
    InputRow,
    RadioRow,
    SelectRow,
    JetButton,
    JetInputError,
  },
  props: {
    templates: {
      type: Array,
      required: true,
    },
    equipments: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const templateInfo = ref();
    const selectedTemplate = ref();
    const selectedEquipment = ref();
    const form = useForm({
      source: {},
      equipments: [],
    });

    const mainTemplates = props.templates.map((t) => ({
      key: t.id,
      value: t.name,
    }));
    const equipTemplates = props.equipments.map((t) => ({
      key: t.id,
      value: t.name,
    }));

    watch(selectedTemplate, (template) => {
      templateInfo.value = props.templates.find((t) => t.id === template);
    });

    watch(selectedEquipment, (template) => {
      templateInfo.value = props.equipments.find((t) => t.id === template);
    });

    return {
      form,
      mainTemplates,
      templateInfo,
      equipTemplates,
      selectedTemplate,
      selectedEquipment,
    };
  },
  computed: {
    properties() {
      return Object.assign([], this.templateInfo?.template_properties);
    },
  },
};
</script>

<style>
</style>


