<template>
  <slide-over
    v-model="open"
    title="New Source"
    subtitle=" Get started by filling in the information below to create your new Source. This Source will be attached to your currently selected Institution."
    headerBackground="bg-green-700"
    dismissButtonTextColor="text-green-200"
    subtitleTextColor="text-green-300"
  >
    <!-- Source Configuration -->
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
                  >
                    Location
                  </select-row>
                </div>
                <!-- <div class="col-span-12">
                  <select-row
                    class="mt-5"
                    :options="equipTemplates"
                    v-model="selectedEquipment"
                    v-if="selectedTemplate"
                  >
                    Add Equipment
                    <button
                      class="inline-flex items-center bg-gray-300 p-2"
                      @click="addEquipment()"
                      :disabled="!selectedEquipment"
                    >
                      +
                    </button>
                  </select-row>
                </div> -->
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
                    >
                      {{ prop.property.name }}
                      <span v-if="prop.unit.symbol"
                        >({{ prop.unit.symbol }})</span
                      >
                    </select-row>

                    <div
                      v-if="prop.property.inputType === 'week_schedule'"
                      class="grid grid-cols-7 gap-2"
                    >
                      <div class="col-span-7">
                        <p class="text-indigo-400 text-sm mb-2 mx-5">
                          {{ prop.property.name }}
                        </p>
                      </div>
                      <div
                        class="col-span-1"
                        v-for="i of [
                          'Monday',
                          'Tuesday',
                          'Wednesday',
                          'Thursday',
                          'Friday',
                          'Saturday',
                          'Sunday',
                        ]"
                        :key="i"
                      >
                        <div class="text-center text-indigo-400 text-sm">
                          <p>{{ i }}</p>
                        </div>
                        <div class="mt-2">
                          <input
                            type="text"
                            class="border border-gray-300 outline-none focus:ring focus:ring-indigo-200 border-opacity-25 pl-3 text-sm w-full leading-6 rounded"
                            v-model="
                              form.source.data[prop.property.symbolic_name][i]
                                .start
                            "
                            placeholder="00:00"
                          />
                        </div>
                        <div class="mt-2">
                          <input
                            type="text"
                            class="border border-gray-300 outline-none focus:ring focus:ring-indigo-200 border-opacity-25 pl-3 text-sm w-full leading-6 rounded"
                            v-model="
                              form.source.data[prop.property.symbolic_name][i]
                                .end
                            "
                            placeholder="00:00"
                          />
                        </div>
                      </div>
                    </div>
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
                  >
                    {{ prop.property.name }}
                    <span v-if="prop.unit.symbol"
                      >({{ prop.unit.symbol }})</span
                    >
                  </select-row>

                  <div
                    v-if="prop.property.inputType === 'week_schedule'"
                    class="grid grid-cols-7 gap-2"
                  >
                    <div class="col-span-7">
                      <p class="text-indigo-400 text-sm mb-2 mx-5">
                        {{ prop.property.name }}
                      </p>
                    </div>
                    <div
                      class="col-span-1"
                      v-for="i of [
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday',
                        'Saturday',
                        'Sunday',
                      ]"
                      :key="i"
                    >
                      <div class="text-center text-indigo-400 text-sm">
                        <p>{{ i }}</p>
                      </div>
                      <div class="mt-2">
                        <input
                          type="text"
                          class="border border-gray-300 outline-none focus:ring focus:ring-indigo-200 border-opacity-25 pl-3 text-sm w-full leading-6 rounded"
                          v-model="
                            equip.data[prop.property.symbolic_name][i].start
                          "
                        />
                      </div>
                      <div class="mt-2">
                        <input
                          type="text"
                          class="border border-gray-300 outline-none focus:ring focus:ring-indigo-200 border-opacity-25 pl-3 text-sm w-full leading-6 rounded"
                          v-model="
                            equip.data[prop.property.symbolic_name][i].end
                          "
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <template #actions>
      <secondary-button
        @click="equipModal = true"
        class="mx-2"
        v-if="selectedTemplate"
        >Add Equipment</secondary-button
      >
      <primary-button :disabled="form.processing" @click="submit()">
        Create
      </primary-button>
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
  <equipment-add-modal
    v-model="equipModal"
    :categories="equipmentCategories"
    :equipments="equipments"
    @add="addEquipment($event)"
  ></equipment-add-modal>
</template>

<script>
import { ref, watch, computed } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout";
import LeafletMap from "@/Components/LeafletMap";
import InputRow from "@/Components/InputRow";
import RadioRow from "@/Components/RadioRow";
import SelectRow from "@/Components/SelectRow";
import JetButton from "@/Jetstream/Button";
import JetInputError from "@/Jetstream/InputError";
import EquipmentAddModal from "@/Components/Modals/EquipmentAddModal.vue";

import PrimaryButton from "../../../Components/PrimaryButton.vue";
import SecondaryButton from "../../../Components/SecondaryButton.vue";
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
    EquipmentAddModal,
    PrimaryButton,
    SecondaryButton,
    SlideOver,
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
    equipmentCategories: Array,
  },

  emits: ["update:modelValue"],

  setup(props) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const slideOverIsOpen = ref(true);
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
    const equipModal = ref(false);

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

      console.log(form.value.source);

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
            if (prop.property.inputType === "week_schedule") {
              equip.data[prop.property.symbolic_name] = {
                Monday: {},
                Tuesday: {},
                Wednesday: {},
                Thursday: {},
                Friday: {},
                Saturday: {},
                Sunday: {},
              };
            } else {
              equip.data[prop.property.symbolic_name] = prop.default_value
                ? prop.default_value
                : "";
            }
          }

          form.value.equipments.push(equip);
        }

      if (templateInfo.value?.template_properties)
        for (const prop of templateInfo.value?.template_properties) {
          if (prop.property.inputType === "week_schedule") {
            form.value.source.data[prop.property.symbolic_name] = {
              Monday: {},
              Tuesday: {},
              Wednesday: {},
              Thursday: {},
              Friday: {},
              Saturday: {},
              Sunday: {},
            };
          } else {
            form.value.source.data[
              prop.property.symbolic_name
            ] = prop.default_value ? prop.default_value : "";
          }
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
      equipModal,
    };
  },
  computed: {
    properties() {
      return Object.assign([], this.templateInfo?.template_properties);
    },
  },
  methods: {
    addEquipment(equipId) {
      const equipment = this.equipments.find((t) => t.id === equipId);

      const equip = {
        id: equipId,
        data: {},
        template: equipment,
      };

      for (const prop of equipment.template_properties) {
        equip.data[prop.property.symbolic_name] = prop.default_value
          ? prop.default_value
          : "";
      }

      this.form.equipments.push(equip);
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


