<template>
  <slide-over v-model="slideOverIsOpen">
    <template #header>
      <div class="py-6 px-4 bg-green-700 sm:px-6">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-medium text-white">New Sink</h2>
          <div class="ml-3 h-7 flex items-center">
            <button
              type="button"
              class="bg-green-700 rounded-md text-green-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
              @click="slideOverIsOpen = false"
            >
              <span class="sr-only">Close panel</span>
              <svg
                class="h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>
        </div>
        <div class="mt-1">
          <p class="text-sm text-green-300">
            Get started by filling in the information below to create your new
            Sink. This Sink will be attached to your currently selected
            Institution
          </p>
        </div>
      </div>
    </template>

    <template #content>
      <!-- Sink Template -->
      <div class="mt-10 sm:mt-0 p-10">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <h3 class="text-lg font-medium leading-6 text-gray-900">Sink</h3>
              <p class="mt-1 text-sm text-gray-600">Sink Configuration</p>
            </div>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow overflow-hidden sm:rounded-md">
              <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                  <div class="col-span-12">
                    <select-row
                      class="mt-5"
                      desc="Sink Templates"
                      :options="mainTemplates"
                      v-model="selectedTemplate"
                    >
                      Template
                    </select-row>
                  </div>
                  <div class="col-span-12">
                    <select-row
                      class="my-5"
                      desc="sink Templates"
                      :options="locationSelects"
                      v-model="form.sink.location_id"
                      v-if="selectedTemplate"
                    >
                      Location
                    </select-row>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- sink Properties -->
      <div class="sm:mt-0 p-10" v-if="selectedTemplate">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <p class="mt-1 text-sm text-gray-600">sink Properties</p>
            </div>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow overflow-hidden sm:rounded-md">
              <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                  <div class="col-span-12">
                    <div v-for="prop in properties" :key="prop.id" class="my-4">
                      <input-row
                        :desc="prop.property.description"
                        v-model="form.sink.data[prop.property.symbolic_name]"
                        v-if="prop.property.inputType === 'text'"
                        :required="prop.required"
                      >
                        {{ prop.property.name }}
                        <span v-if="prop.unit.symbol"
                          >({{ prop.unit.symbol }})</span
                        >
                      </input-row>

                      <select-row
                        :desc="prop.property.description"
                        :options="prop.property.data.options"
                        v-model="form.sink.data[prop.property.symbolic_name]"
                        v-if="prop.property.inputType === 'select'"
                        :required="prop.required"
                      >
                        {{ prop.property.name }}
                        <span v-if="prop.unit.symbol"
                          >({{ prop.unit.symbol }})</span
                        >
                      </select-row>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <template #actions>
      <button
        type="button"
        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        @click="slideOverIsOpen = false"
      >
        Cancel
      </button>
      <button
        type="submit"
        class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        @click="submit()"
        :disabled="form.processing"
      >
        Save
      </button>
    </template>
  </slide-over>
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
import SlideOver from "../../../Components/NewLayout/SlideOver.vue";

export default {
  components: {
    AppLayout,
    LeafletMap,
    InputRow,
    RadioRow,
    SelectRow,
    JetButton,
    JetInputError,
    SlideOver,
  },
  props: {
    templates: {
      type: Array,
      default: [],
    },
    equipments: {
      type: Array,
      default: [],
    },
    locations: {
      type: Array,
      default: [],
    },
  },
  setup(props) {
    const slideOverIsOpen = ref(true);
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
      slideOverIsOpen,
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
