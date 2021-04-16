<template>
  <slide-over
    v-model="open"
    title="Source Details"
    subtitle=""
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-green-200"
    subtitleTextColor="text-green-300"
  >
    <div class="mt-10 sm:mt-0 p-10">
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Source</h3>
            <p class="mt-1 text-sm text-gray-600">Source Configuration</p>
          </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white sm:p-6">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-12">
                  <select-row
                    class="mt-5"
                    desc="Source Templates"
                    :options="mainTemplates"
                    v-model="selectedTemplate"
                    :disabled="true"
                  >
                    Template
                  </select-row>
                </div>
                <div class="col-span-12">
                  <select-row
                    class="my-5"
                    desc="Source Templates"
                    :options="locationSelects"
                    v-model="form.source.location_id"
                    v-if="selectedTemplate"
                    :disabled="true"
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

    <!-- Source Properties -->
    <div class="sm:mt-0 p-10" v-if="selectedTemplate">
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <p class="mt-1 text-sm text-gray-600">Source Properties</p>
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
                      v-model="form.source.data[prop.property.symbolic_name]"
                      v-if="prop.property.inputType === 'text'"
                      :required="prop.required"
                      :disabled="true"
                    >
                      {{ prop.property.name }}
                      <span v-if="prop.unit.symbol"
                        >({{ prop.unit.symbol }})</span
                      >
                    </input-row>

                    <select-row
                      :desc="prop.property.description"
                      :options="prop.property.data.options"
                      v-model="form.source.data[prop.property.symbolic_name]"
                      v-if="prop.property.inputType === 'select'"
                      :required="prop.required"
                      :disabled="true"
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

    <!-- Equipment Configuration -->
    <div
      class="mt-10 sm:mt-0 p-10"
      v-for="equip in form.equipments"
      :key="equip.id"
    >
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium leading-6 text-gray-900">
              {{ equip.template.name }}
            </h3>
            <p class="mt-1 text-sm text-gray-600">Equipment Properties</p>
          </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <div class="shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 bg-white sm:p-6">
              <div class="grid grid-cols-6 gap-6">
                <div
                  v-for="prop in equip.template.template_properties"
                  :key="prop.id"
                  class="my-4 col-span-12"
                >
                  <input-row
                    :desc="prop.property.description"
                    v-model="equip.data[prop.property.symbolic_name]"
                    v-if="prop.property.inputType === 'text'"
                    :required="prop.required"
                    :disabled="true"
                  >
                    {{ prop.property.name }}
                    <span v-if="prop.unit.symbol"
                      >({{ prop.unit.symbol }})</span
                    >
                  </input-row>

                  <select-row
                    :desc="prop.property.description"
                    :options="prop.property.data.options"
                    v-model="equip.data[prop.property.symbolic_name]"
                    v-if="prop.property.inputType === 'select'"
                    :required="prop.required"
                    :disabled="true"
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
    <template #actions>
      <inertia-link
        as="button"
        :href="route('objects.sources.edit', instance.id)"
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
      >
        Edit Source
      </inertia-link>
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

import { onMounted, ref, watch } from "vue";

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
    modelValue: {
      type: Boolean,
      required: true,
    },
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
    instance: {
      type: Object,
      required: true,
    },
  },
  setup(props) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const templateInfo = ref();
    const selectedTemplate = ref();
    const selectedEquipment = ref();
    const selectedLocation = ref();
    const marker = ref();
    const form = useForm({
      source: {
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

    //  Set Instance Values
    selectedTemplate.value = props.instance.template_id;
    templateInfo.value = props.templates.find(
      (t) => t.id === props.instance.template_id
    );
    form.value.source.data.name = props.instance.name;
    form.value.template_id = props.instance.template_id;

    form.value.source.location_id = props.instance.location_id;
    form.value.equipments = props.instance.values.equipments.map((e) => ({
      ...e,
      template: props.equipments.find((_e) => _e.id === e.id),
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
          form.value.source.data[
            prop.property.symbolic_name
          ] = prop.default_value ? prop.default_value : "";
        }
    });

    watch(selectedLocation, (locId) => {
      const location = props.locations.find((l) => l.id === locId);
      marker.value = location.geo_object;
      //this.$refs.map.centerAtLocation(marker);
    });

    return {
      open,
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
      this.form.post(route("objects.sources.store"));
    },
    onLocationSelect(locId) {},
  },
};
</script>

<style>
</style>


