<template>
  <slide-over
    v-model="open"
    title="Objects"
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
        <amazing-index-table v-model="objects" :columns="tableColumns">
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
              <inertia-link
                :href="route(`${selectedObject.path}.show`, item.id)"
              >
                <detail-icon
                  class="text-gray-500 font-medium text-sm w-5"
                ></detail-icon>
              </inertia-link>
              <inertia-link
                :href="route(`${selectedObject.path}.edit`, item.id)"
              >
                <edit-icon
                  class="text-gray-500 font-medium text-sm w-5"
                ></edit-icon>
              </inertia-link>
              <button class="focus:outline-none">
                <trash-icon
                  class="text-red-500 font-medium text-sm w-5"
                  @click="showModal(item, modalTypes.delete)"
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
      <primary-link-button :path="`${selectedObject.path}.create`">
        Create New {{ getSingular(selectedObject.title) }}
      </primary-link-button>
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
import { computed, ref, watch, defineAsyncComponent } from "vue";
import { Inertia } from "@inertiajs/inertia";
import pluralize from "pluralize";

import SlideOver from "../../Components/NewLayout/SlideOver.vue";
import FilterDropdown from "../../Components/NewLayout/BrandedDropdown.vue";
import AmazingIndexTable from "../../Components/Tables/AmazingIndexTable.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";
import PrimaryLinkButton from "../../Components/PrimaryLinkButton.vue";
import SecondaryLinkButton from "../../Components/SecondaryLinkButton.vue";

export default {
  components: {
    SlideOver,
    PrimaryLinkButton,
    SecondaryLinkButton,
    AmazingIndexTable,
    TrashIcon,
    EditIcon,
    DetailIcon,
    FilterDropdown,
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
    const tableColumns = ["name", "location", "actions"];
    const filterOptions = [
      {
        title: "Sources",
        path: "objects.sources",
        description:
          "Display all the Sources for the currently selected Institution",
        current: true,
      },
      {
        title: "Sinks",
        path: "objects.sinks",
        description:
          "Display all the Sinks for the currently selected Institution",
        current: false,
      },
      {
        title: "Links",
        path: "objects.links",
        description:
          "Display all the Links for the currently selected Institution",
        current: false,
      },
    ];
    const modalTypes = {
      delete: {
        type: "delete",
        path: "@/Components/NewLayout/Modals/DeleteModal.vue",
      },
    };

    const objects = ref(null);
    const selectedObject = ref(filterOptions[0]);
    const filterDropdown = ref(false);
    const modalIsOpen = ref(false);
    const currentModal = ref(null);
    const itemToDelete = ref(null);

    const open = computed({
      get: () => props.modelValue,
      set: (value) => emit("update:modelValue", value),
    });

    const modalComponent = computed(() =>
      currentModal.value
        ? defineAsyncComponent(() => import(`${currentModal.value.path}`))
        : false
    );

    watch(
      selectedObject,
      (current, previous) => {
        if (current.title === "Sinks") {
          objects.value = props.instances.filter(
            (i) => i.template.category.type === "sink"
          );
          return;
        }
        if (current.title === "Sources") {
          objects.value = props.instances.filter(
            (i) => i.template.category.type === "source"
          );
          return;
        }
        if (current.title === "Links") {
          objects.value = props.instances.filter(
            (i) => i.template.category.type === "link"
          );
          return;
        }
      },
      { immediate: true }
    );

    const getSingular = (val) => pluralize.singular(val);

    const showModal = (item, type) => {
      switch (type) {
        case modalTypes.delete:
          currentModal.value = modalTypes.delete;
          itemToDelete.value = item;
          break;

        default:
          break;
      }

      modalIsOpen.value = true;
    };

    const centerAtLocation = (loc) => emit("onCenter", loc);

    const onConfirmation = () => {
      switch (currentModal.value) {
        case modalTypes.delete:
          Inertia.delete(
            route(`${selectedObject.value.path}.destroy`, itemToDelete.value.id)
          );
          break;

        default:
          break;
      }
    };

    return {
      tableColumns,
      filterOptions,
      modalTypes,
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
    };
  },
};
</script>

<style>
</style>
