<template>
  <!-- This example requires Tailwind CSS v2.0+ -->
  <div
    v-if="value"
    class="fixed z-10 inset-0 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
  >
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!--
      Background overlay, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
      <transition
        enter-active-class="ease-out duration-300"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="value"
          @click="value = false"
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
          aria-hidden="true"
        ></div>
      </transition>
      <!-- This element is to trick the browser into centering the modal contents. -->
      <span
        class="hidden sm:inline-block sm:align-middle sm:h-screen"
        aria-hidden="true"
      >&#8203;</span>

      <!--
      Modal panel, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        To: "opacity-100 translate-y-0 sm:scale-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100 translate-y-0 sm:scale-100"
        To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
    -->
      <transition
        enter-active-class="ease-out duration-300"
        enter-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="ease-in duration-200"
        leave-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      >
        <div
          v-if="value"
          class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-6"
        >
          <div>
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
              <!-- Heroicon name: outline/check -->
              <svg
                class="w-6 h-6"
                fill="none"
                stroke="darkblue"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"
                ></path>
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-5">
              <h3
                class="text-lg leading-6 font-medium text-gray-900"
                id="modal-title"
              >
                Adding Equipment...
              </h3>
              <div class="mt-4">
                <label
                  for="country"
                  class="block text-sm font-medium text-gray-700 text-left"
                >
                  Category
                </label>
                <div class="mt-1">
                  <select
                    id="country"
                    name="country"
                    autocomplete="country"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    v-model="selectedCategory"
                  >
                    <option
                      v-for="item of categorySelect"
                      :key="item.id"
                      :value="item.key"
                    >
                      {{ item.value }}
                    </option>
                  </select>
                </div>
              </div>
              <div class="mt-4">
                <label
                  for="country"
                  class="block text-sm font-medium text-gray-700 text-left"
                >
                  Equipment
                </label>
                <div class="mt-1">
                  <select
                    id="country"
                    name="country"
                    autocomplete="country"
                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                    v-model="selectedEquipment"
                  >
                    <option
                      v-for="item of availableEquipments"
                      :key="item.id"
                      :value="item.key"
                    >
                      {{ item.value }}
                    </option>
                    <option
                      :value="null"
                      v-if="availableEquipments?.length == 0"
                    >
                      - No Equipments Available -
                    </option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-5 sm:mt-6">
            <button
              :disabled="!selectedEquipment"
              type="button"
              class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm"
              @click="onAddEquipment()"
            >
              Add equipment
            </button>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
  import { ref, computed, watch } from "vue";
  export default {
    props: {
      categories: {
        type: Array,
        required: true,
      },
      equipments: {
        type: Array,
        required: true,
      },
      modelValue: {
        type: Boolean,
        required: true,
      },
    },

    emits: ["update:modelValue", "add"],

    setup(props, { emit }) {
      const selectedCategory = ref();
      const availableEquipments = ref([]);
      const selectedEquipment = ref();

      const categorySelect = props.categories.map((c) => ({
        key: c.id,
        value: c.name,
      }));

      const equipmentSelect = props.equipments.map((e) => ({
        key: e.id,
        value: e.name,
        parent: e.category_id,
      }));

      watch(
        () => selectedCategory.value,
        (category) => {
          availableEquipments.value = equipmentSelect.filter(
            (e) => e.parent == category
          );
        }
      );

      const value = computed({
        get() {
          return props.modelValue;
        },
        set(value) {
          emit("update:modelValue", value);
        },
      });

      const onAddEquipment = () => {
        emit("add", selectedEquipment.value);
        value.value = false;
      };

      return {
        categorySelect,
        equipmentSelect,
        selectedCategory,
        selectedEquipment,
        availableEquipments,
        value,
        onAddEquipment,
      };
    },
  };
</script>

<style>
</style>
