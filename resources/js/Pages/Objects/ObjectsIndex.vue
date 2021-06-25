<template>
  <slide-over
    v-model="open"
    :title="`Objects`"
    subtitle="A list of all the Objects for the currently selected Instituion"
    headerBackground="bg-yellow-600"
    dismissButtonTextColor="text-gray-100"
    subtitleTextColor="text-gray-100"
  >
    <div class="flex justify-end m-3">
      <filter-dropdown
        v-model="selectedObject"
        :options="filterOptions"
      ></filter-dropdown>
    </div>

    <div class="overflow-y-auto overflow-x-auto">
      <div v-if="objects?.length">
        <amazing-index-table
          v-model="objects"
          :columns="tableColumns"
        >
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
            <td class="pr-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 justify-end">
              <button
                class="focus:outline-none"
                @click="
                  onActionRequest(`${selectedObject.paths.details}`, item.id)
                "
              >
                <detail-icon class="text-gray-500 font-medium text-sm w-5"></detail-icon>
              </button>
              <button
                class="focus:outline-none"
                @click="
                  onActionRequest(`${selectedObject.paths.edit}`, item.id)
                "
              >
                <edit-icon class="text-gray-500 font-medium text-sm w-5"></edit-icon>
              </button>
              <button class="focus:outline-none">
                <trash-icon
                  class="text-red-500 font-medium text-sm w-5"
                  @click="showModal(item, DeleteModal)"
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
      <primary-button @click="onActionRequest(`${selectedObject.paths.create}`)">
        Create New {{ getSingular(selectedObject.title) }}</primary-button>
    </template>
  </slide-over>

  <component
    class="z-50"
    :is="modalComponent"
    v-if="modalComponent"
    v-model="modalIsOpen"
    @confirmation="onConfirmation"
  >
  </component>
</template>

<script>
import { computed, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { useStore } from "vuex";
import pluralize from "pluralize";

import SlideOver from "@/Components/SlideOver.vue";
import FilterDropdown from "@/Components/BrandedDropdown.vue";
import AmazingIndexTable from "@/Components/Tables/AmazingIndexTable.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";
import DeleteModal from "@/Components/Modals/DeleteModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { filterOptions } from "@/Utils/objectIndex";

export default {
  components: {
    SlideOver,
    PrimaryLinkButton,
    AmazingIndexTable,
    TrashIcon,
    EditIcon,
    DetailIcon,
    FilterDropdown,
    DeleteModal,
    PrimaryButton,
  },

  props: {
    instances: {
      type: Array,
      default: [],
    },
    links: {
      type: Array,
      default: [],
    },
  },
  setup(props) {
    const store = useStore();

    const tableColumns = ["name", "location", "actions"];
    const selectedObject = ref(filterOptions[0]);

    const objects = ref(null);
    const filterDropdown = ref(false);
    const modalIsOpen = ref(false);
    const currentModal = ref(null);
    const itemToDelete = ref(null);

    const open = computed({
      get: () => store.getters["objects/slideOpen"],
      set: (value) => store.commit("objects/setSlide", value),
    });

    const modalComponent = computed(() =>
      currentModal.value ? currentModal.value : false
    );

    watch(
      selectedObject,
      (current) => {
        if (current.title === "Sinks") {
          objects.value = props.instances.filter(
            (i) => i.template?.category?.type === "sink"
          );
          return;
        }
        if (current.title === "Sources") {
          objects.value = props.instances.filter(
            (i) => i.template?.category?.type === "source"
          );
          return;
        }
        if (current.title === "Links") {
          objects.value = props.links;
          return;
        }
      },
      { immediate: true }
    );

    const getSingular = (val) => pluralize.singular(val);

    const showModal = (item, modal) => {
      currentModal.value = modal;
      itemToDelete.value = item;

      modalIsOpen.value = true;
    };

    const centerAtLocation = (loc) =>
      store.dispatch("map/centerAt", { marker: loc });

    const onConfirmation = (modalType) => {
      switch (modalType) {
        case "delete":
          const _type = itemToDelete.value?.template?.category?.type;

          if (_type === "source")
            Inertia.delete(
              route(`objects.sources.destroy`, itemToDelete.value.id),
              {
                onSuccess: () => store.dispatch("map/refreshMap"),
              }
            );
          if (_type === "sink")
            Inertia.delete(
              route(`objects.sinks.destroy`, itemToDelete.value.id)
            );

          objects.value.splice(objects.value.indexOf(itemToDelete.value), 1);

          break;

        default:
          break;
      }
    };

    const onActionRequest = async (route, param) =>
      store.dispatch("objects/showSlide", { route, props: param });

    return {
      tableColumns,
      filterOptions,
      objects,
      selectedObject,
      filterDropdown,
      modalIsOpen,
      open,
      modalComponent,
      getSingular,
      showModal,
      centerAtLocation,
      onConfirmation,
      onActionRequest,
      DeleteModal,
    };
  },
};
</script>

<style>
</style>
