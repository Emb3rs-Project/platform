<template>
  <slide-over
    v-model="open"
    title="Objects"
    subtitle="A list of all the Objects for the currently selected Instituion"
    headerBackground="bg-yellow-600"
    dismissButtonTextColor="text-gray-100"
    subtitleTextColor="text-gray-100"
  >
    <div class="overflow-y-auto overflow-x-auto">
      <div v-if="instances.length">
        <amazing-index-table v-model="sinks" :columns="tableColumns">
          <!-- ID -->
          <template #header-id> ID </template>
          <template #body-id="{ item }">
            <td class="text-left pl-4">
              {{ item.id }}
            </td>
          </template>

          <!-- Name -->
          <template #header-name> Name </template>
          <template #body-name="{ item }">
            <td class="text-left pl-4">
              {{ item.name }}
            </td>
          </template>

          <!-- Template -->
          <template #header-template> Template </template>
          <template #body-template="{ item }">
            <td class="text-left pl-4">
              {{ item.template.name }}
            </td>
          </template>

          <!-- Location -->
          <template #header-location> Location </template>
          <template #body-location="{ item }">
            <td class="text-left pl-4">
              <a
                :class="{
                  'font-bold text-green-600 cursor-pointer hover:text-green-700':
                    item.location,
                }"
                @click="centerAtLocation(item.location)"
              >
                {{ item.location ? item.location.name : "Not Assigned" }}
              </a>
            </td>
          </template>

          <!-- Actions -->
          <template #header-actions> </template>
          <template #body-actions="{ item }">
            <td
              class="pr-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end"
            >
              <inertia-link :href="route('objects.sinks.show', item.id)">
                <detail-icon
                  class="text-gray-500 font-medium text-sm w-5"
                ></detail-icon>
              </inertia-link>
              <inertia-link :href="route('objects.sinks.edit', item.id)">
                <edit-icon
                  class="text-gray-500 font-medium text-sm w-5"
                ></edit-icon>
              </inertia-link>
              <button class="focus:outline-none" @click="onDelete(item)">
                <trash-icon
                  class="text-red-500 font-medium text-sm w-5"
                ></trash-icon>
              </button>
            </td>
          </template>
        </amazing-index-table>
      </div>
      <div
        v-else
        class="flex items-center place-content-center bg-gray-50 h-20 md:h-64"
      >
        <h1 class="text-2xl font-extrabold text-gray-300 uppercase">
          No Objects Found
        </h1>
      </div>
    </div>

    <template #actions>
      <primary-link-button :path="'objects.sinks.create'" parameter="">
        Create New Sink
      </primary-link-button>
    </template>
  </slide-over>
</template>

<script>
import { computed, ref } from "vue";
import SlideOver from "../../Components/NewLayout/SlideOver.vue";
import PrimaryLinkButton from "../../Components/PrimaryLinkButton.vue";
import AmazingIndexTable from "../../Components/Tables/AmazingIndexTable.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";

export default {
  components: {
    SlideOver,
    PrimaryLinkButton,
    AmazingIndexTable,
    TrashIcon,
    EditIcon,
    DetailIcon,
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    instances: {
      type: Array,
      default: [],
    },
  },

  emits: ["update:modelValue", "onCenter"],

  setup(props, { emit }) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const sinks = computed({
      get: () =>
        props.instances.filter((i) => i.template.category.type === "sink"),
    });

    const onDelete = (e) => {};
    const centerAtLocation = (loc) => emit("onCenter", loc);
    const tableColumns = ["name", "location", "actions"];

    return {
      onDelete,
      centerAtLocation,
      sinks,
      tableColumns,
      open,
    };
  },
};
</script>

<style>
</style>
