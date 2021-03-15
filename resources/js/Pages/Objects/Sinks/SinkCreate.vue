<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Objects | Sinks
      </h2>
    </template>
    <div class="p-5">
      <h1 class="text-lg font-bold">New Sink</h1>
    </div>
    <div class="flex p-5 gap-2">
      <div class="w-6/12 md:min-h-content md:pr-4">
        <select-row
          class="mt-5"
          desc="Sink Templates"
          :options="mainTemplates"
          v-model="selectedTemplate"
        >
          Template
        </select-row>
        <select-row
          class="my-5"
          desc="sink Templates"
          :options="locationSelects"
          v-model="form.sink.location_id"
          v-if="selectedTemplate"
        >
          Location
        </select-row>
        <hr class="my-5" />
        <!-- sink -->
        <h1 v-if="selectedTemplate">Sink properties</h1>
        <div v-for="prop in properties" :key="prop.id" class="my-4">
          <input-row
            :desc="prop.property.description"
            v-model="form.sink.data[prop.property.symbolic_name]"
            v-if="prop.property.inputType === 'text'"
            :required="prop.required"
          >
            {{ prop.property.name }}
            <span v-if="prop.unit.symbol">({{ prop.unit.symbol }})</span>
          </input-row>

          <select-row
            :desc="prop.property.description"
            :options="prop.property.data.options"
            v-model="form.sink.data[prop.property.symbolic_name]"
            v-if="prop.property.inputType === 'select'"
            :required="prop.required"
          >
            {{ prop.property.name }}
            <span v-if="prop.unit.symbol">({{ prop.unit.symbol }})</span>
          </select-row>
        </div>
      </div>
      <div class="w-6/12 max-h-screen">
        <leaflet-map ref="map" :marker="marker"></leaflet-map>
      </div>
    </div>
    <div class="w-full my-5 px-16 flex justify-end">
      <jet-button :disabled="form.processing" @click="submit()">
        Create Sink
      </jet-button>
    </div>
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
    locations: {
      type: Array,
      required: true,
    },
  },
  setup(props) {
    const templateInfo = ref();
    const selectedTemplate = ref();
    const selectedEquipment = ref();
    const selectedLocation = ref();
    const marker = ref();
    const form = useForm({
      sink: {
        data: {},
      },
      equipments: [],
      template_id: null,
    });

    const mainTemplates = props.templates.map((t) => ({
      key: t.id,
      value: t.name,
    }));
    const equipTemplates = props.equipments.map((t) => ({
      key: t.id,
      value: t.name,
    }));
    const locationSelects = props.locations.map((t) => ({
      key: t.id,
      value: t.name,
    }));

    watch(selectedTemplate, (template) => {
      templateInfo.value = props.templates.find((t) => t.id === template);
      form.value.equipments = [];
      form.value.template_id = template;

      // Check if there are Children
      if (templateInfo.value.values.children)
        for (const child of templateInfo.value.values.children) {
          const equipment = props.equipments.find((t) => t.id === child);
          const equip = {
            id: child,
            data: {},
            template: equipment,
          };
          // Load Default Values
          for (const prop of equip.template.template_properties) {
            equip.data[prop.property.symbolic_name] = prop.default_value
              ? prop.default_value
              : "";
          }

          form.value.equipments.push(equip);
        }

      if (templateInfo.value?.template_properties)
        for (const prop of templateInfo.value?.template_properties) {
          form.value.sink.data[prop.property.symbolic_name] = prop.default_value
            ? prop.default_value
            : "";
        }
    });

    watch(selectedLocation, (locId) => {
      const location = props.locations.find((l) => l.id === locId);
      marker.value = location.geo_object;
      //this.$refs.map.centerAtLocation(marker);
    });

    return {
      form,
      mainTemplates,
      templateInfo,
      equipTemplates,
      selectedTemplate,
      selectedEquipment,
      locationSelects,
      selectedLocation,
      marker,
    };
  },
  computed: {
    properties() {
      return Object.assign([], this.templateInfo?.template_properties);
    },
  },
  methods: {
    addEquipment() {
      const equipment = this.equipments.find(
        (t) => t.id === this.selectedEquipment
      );
      const equip = {
        id: child,
        data: {},
        template: equipment,
      };
      // Load Default Values
      for (const prop of equip.template.template_properties) {
        equip.data[prop.property.symbolic_name] = prop.default_value
          ? prop.default_value
          : "";
      }

      form.equipments.push(equip);
    },
    submit() {
      console.log("saving ", this.form);
      this.form.post(route("objects.sinks.store"));
    },
    onLocationSelect(locId) {},
  },
};
</script>

<style>
</style>
