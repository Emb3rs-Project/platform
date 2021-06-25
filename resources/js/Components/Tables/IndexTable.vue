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
                  <EyeIcon
                    class="text-gray-500 font-medium text-sm w-5"
                    aria-hidden="true"
                  />
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
import { watch, ref } from "vue";

import DeletionModal from "@/Components/Modals/DeletionModal";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";
import { EyeIcon } from "@heroicons/vue/solid";

export default {
  components: {
    DeletionModal,
    EyeIcon,
    TrashIcon,
    EditIcon,
    DetailIcon,
  },

  props: {
    headers: {
      type: Array,
      required: true,
    },
    items: {
      type: Array,
      required: true,
    },
  },

  emits: [
    "itemAdded",
    "itemRemoved",
    "allItemsAdded",
    "allItemsRemoved",
    "itemDeleted",
    "centerAtLocation",
  ],

  setup(props, context) {
    const massSelection = ref(true);
    const massSelectionIndeterminated = ref(false);

    // We can use .filter() here instead of .flatMap() and
    // apply the .map() later (i.e. .filter().map())
    // it depends on the developer's writting style
    const allInitialySelected = props.items.flatMap((item) => {
      if (item.location.name === "Not assigned") return [];
      return item;
    });

    const selected = ref([...allInitialySelected]);

    watch(massSelection, (current, previous) => {
      console.log("massSelection", current);
      // All the entries are selected, so, if pressesed, deselect them all.
      if (previous && selected.value.length === allInitialySelected.length) {
        deSelectAll();
        return;
      }

      if (!previous && !selected.value.length) {
        selectAll();
        return;
      }

      if (
        current &&
        selected.value.length &&
        selected.value.length !== allInitialySelected.length
      ) {
        massSelectionIndeterminated.value = false;
        selectAll();
        return;
      }
    });

    watch(selected, (current, previous) => {
      // The user pressed the massSelection checkbox and selected all the entries
      // ,so we only emit this event as an allItemsAdded and not as many indivudual
      // removedItem events
      if (
        massSelection.value &&
        previous.length !== allInitialySelected.length
      ) {
        return;
      }

      // The user pressed the massSelection checkbox and deSelected all the entries
      // ,so we only emit this event as an allItemsRemoved and not as many indivudual
      // addedItem events
      console.log(
        "massSelectionIndeterminated",
        massSelectionIndeterminated.value
      );
      if (
        !massSelectionIndeterminated.value &&
        current.length === allInitialySelected.length
      ) {
        console.log("tt");
        return;
      }

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

        itemRemoved(current, previous);
        return;
      }

      // An item was added to the selected entries
      if (current.length > previous.length) {
        // If the added item was the last selected item for array fulliness,
        // update mass action to be selected.
        // Else, update mass action to the indeterminated state.
        if (current.length === allInitialySelected.length) {
          massSelection.value = true;
          massSelectionIndeterminated.value = false;
        } else {
          massSelection.value = false;
          massSelectionIndeterminated.value = true;
        }

        itemAdded(current, previous);
        return;
      }
    });

    function itemRemoved(currentSelections, previousSelections) {
      for (const _previousSelection of previousSelections) {
        const entry = currentSelections.find(
          (element) => element.id.value === _previousSelection.id.value
        );

        if (!entry) {
          context.emit("itemRemoved", _previousSelection);

          // we found the element that was removed, so stop the itteration
          break;
        }
      }
    }

    function itemAdded(currentSelections, previousSelections) {
      for (const _currentSelection of currentSelections) {
        const item = previousSelections.find(
          (item) => item.id === _currentSelection.id
        );

        if (!item) {
          context.emit("itemAdded", _currentSelection);

          // we found the element that was added, so stop the itteration
          break;
        }
      }
    }

    // Select (checked) all the applicable entries
    function selectAll() {
      selected.value = allInitialySelected;
      context.emit("allItemsAdded");
    }

    // De select (unckecked) all the applicable entries
    function deSelectAll() {
      selected.value = [];
      context.emit("allItemsRemoved");
    }

    function centerAtLocation(location) {
      // we must check if the pressed location is present in the selected array
      // if not, do nothing
      const locationIsChecked = selected.value.find(
        (element) => element.location.value === location
      );

      if (!locationIsChecked) return; // we can disable the link in general (recommended) or do nothing () or display a toast notification

      context.emit("centerAtLocation", location);
    }

    function deleteEntity(entity) {
      context.emit("itemDeleted", entity);
    }

    return {
      massSelection,
      massSelectionIndeterminated,
      centerAtLocation,
      deleteEntity,
      selected,
    };
  },
};
</script>
