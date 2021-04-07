<template>
  <div class="flex flex-col max-h-full">
    <div class="px-4 py-5 sm:px-6 bg-white shadow sm:rounded-t-md">
      <h3 class="text-lg leading-6 font-bold text-gray-900">
        <slot name="header"></slot>
      </h3>
      <p class="text-sm text-gray-500">
        <slot name="description"></slot>
      </p>
    </div>
    <div class="overflow-y-auto overflow-x-auto shadow sm:rounded-b-md">

      <div v-if="items.length">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                scope="col"
                class="relative px-6 py-3"
              >
                <div class="flex justify-center items-center h-5">
                  <input
                    type="checkbox"
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                    v-model="massSelection"
                    :indeterminate="massSelectionIndeterminated"
                  />
                </div>
              </th>
              <th
                v-for="(header, index) in headers"
                :key="index"
                scope="col"
                class="px-6 py-3 text-xs text-left font-medium text-gray-500 uppercase tracking-wider"
                :class="
                    header.isNumeric
                    ? 'px-6 py-3 text-xs text-right font-medium text-gray-500 uppercase tracking-wider'
                    : 'px-6 py-3 text-xs text-left font-medium text-gray-500 uppercase tracking-wider'
                "
              >
                {{ header.name }}
              </th>
              <th
                scope="col"
                class="relative px-6 py-3"
              >
                <span class="sr-only">Edit</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(item, index) in items"
              :key="index"
              :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex justify-center items-center h-5">
                  <input
                    :value="item"
                    v-model="selected"
                    type="checkbox"
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded disabled:opacity-50"
                    :disabled="item.location.name === 'Not assigned' ? true : false"
                  />
                </div>
              </td>
              <!-- for the list of elements of this current row, print them -->
              <td
                v-for="(currentItem, index) in item"
                :key="index"
                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                :class="[currentItem.isNumeric ? 'text-right' : 'text-left']"
              >
                <div v-if="currentItem.name !== 'Not assigned' && currentItem.isLocation">
                  <a
                    class="font-bold text-green-500 cursor-pointer hover:text-green-700"
                    @click="centerAtLocation(currentItem.value)"
                  >
                    {{ currentItem.name }}
                  </a>
                </div>
                <div v-else>
                  {{ currentItem.name }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                <inertia-link :href="route('objects.sources.show', item.id.value)">
                  <detail-icon class="text-gray-500 font-medium text-sm w-5"></detail-icon>
                </inertia-link>
                <inertia-link :href="route('objects.sources.edit', item.id.value)">
                  <edit-icon class="text-gray-500 font-medium text-sm w-5"></edit-icon>
                </inertia-link>
                <button
                  class="focus:outline-none"
                  @click="deleteEntity(item.id.value)"
                >
                  <trash-icon class="text-red-500 font-medium text-sm w-5"></trash-icon>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div
        v-else
        class="flex items-center place-content-center bg-gray-50 h-20 md:h-64"
      >
        <h1 class="text-2xl font-extrabold text-gray-300 uppercase">
          No
          <slot name="header"></slot>
          Found
        </h1>
      </div>
    </div>

  </div>
</template>

<script>
  import { watch, ref, computed } from "vue";

  import useUniqueLocations from "@/Composables/useUniqueLocations";

  import DeletionModal from "@/Components/Modals/DeletionModal";
  import TrashIcon from "@/Icons/TrashIcon.vue";
  import EditIcon from "@/Icons/EditIcon.vue";
  import DetailIcon from "@/Icons/DetailIcon.vue";

  export default {
    components: {
      DeletionModal,
      TrashIcon,
      EditIcon,
      DetailIcon
    },

    props: {
      modelValue: {
        type: Array,
        required: true
      },
      headers: {
        type: Array,
        required: true
      },
      items: {
        type: Array,
        required: true
      },
    },

    emits: ['update:modelValue', 'centerAtLocation', 'deleteEntity'],

    setup(props, context) {
      const massSelection = ref(true);
      const massSelectionIndeterminated = ref(false);
      const allSelectedInitial = props.modelValue;
      const allSelectedLength = props.modelValue.length;

      const selected = computed({
        get: () => props.modelValue,
        set: (value) => {
          context.emit('update:modelValue', value);
        }
      });

      watch(massSelection, (current, previous) => {
        // All the entries are selected, so, if pressesed, deselect them all.
        if (previous && selected.value.length === allSelectedLength) {
          deSelectAll();
          return;
        }

        if (!previous && !selected.value.length) {
          selectAll();
          return;
        }

        if (current && selected.value.length && selected.value.length !== allSelectedLength) {
          massSelectionIndeterminated.value = false;
          selectAll();
          return;
        }
      });

      watch(selected, (current, previous) => {
        // An iteam was removed from the selected entries
        if (current.length < previous.length) {
          // Since we "touched" the selected items, on the next mass selection
          // user action, we must select them all
          massSelection.value = false;

          // If the removed item was the last selected item, update mass action
          // to be empty.
          // Else, update mass action to the indeterminated state.
          if (!current.length) {
            massSelectionIndeterminated.value = false;
          } else {
            massSelectionIndeterminated.value = true;
          }
          return;
        }

        // An item was added to the selected entries
        if (current.length > previous.length) {
          // If the added item was the last selected item for array fulliness,
          // update mass action to be selected.
          // Else, update mass action to the indeterminated state.
          if (current.length === allSelectedLength) {
            massSelection.value = true;
            massSelectionIndeterminated.value = false;
          } else {
            massSelection.value = false;
            massSelectionIndeterminated.value = true;
          }
          return;
        }
      });

      // Select (checked) all the applicable sources
      function selectAll() {
        selected.value = allSelectedInitial;
      }

      function deSelectAll() {
        selected.value = [];
      }

      function centerAtLocation(location) {
        // we must check if the pressed location is present in the selected array
        // if not, do nothing
        const locationIsChecked = selected.value.find((element) => element.location.value === location);

        if (!locationIsChecked) return;// we can disable the link in general (recommended) or do nothing () or display a toast notification

        context.emit('centerAtLocation', location);
      }

      function deleteEntity(entity) {
        context.emit('deleteEntity', entity);
      }

      return {
        massSelection,
        massSelectionIndeterminated,
        centerAtLocation,
        deleteEntity,
        selected
      };
    },
  }
</script>
