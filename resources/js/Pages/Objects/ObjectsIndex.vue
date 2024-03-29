<template>
  <SlideOver
    type="objects"
    title="Objects"
    subtitle="A list of all the Objects for the currently selected Instituion"
  >
    <div class="flex justify-end m-3">
      <FilterDropdown
        v-model="selectedObject"
        :options="filterOptions"
      />
    </div>

    <div class="overflow-y-auto overflow-x-auto">
      <div v-if="objects.length">
        <AmazingIndexTable
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
                <DetailIcon class="text-gray-500 font-medium text-sm w-5" />
              </button>
              <button
                class="focus:outline-none"
                :disabled="startLinks || startMarker"
                @click="
                  onActionRequest(`${selectedObject.paths.edit}`, item.id)
                "
              >
                <EditIcon class="text-gray-500 font-medium text-sm w-5" />
              </button>
              <button 
                class="focus:outline-none"
                :disabled="startLinks || startMarker"
                @click="showModal(item, DeleteModal)"
              >
                <TrashIcon class="text-red-500 font-medium text-sm w-5" />
              </button>
            </td>
          </template>
        </AmazingIndexTable>
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
      <PrimaryButton 
        :disabled="(startLinks && selectedObject.title != 'Links') 
                    || (startMarker && selectedObject.title != startMarker)" 
        @click="onActionRequest(`${selectedObject.paths.create}`)"
      >
        Create New {{ getSingular(selectedObject.title) }}
      </PrimaryButton>
    </template>
  </SlideOver>

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

import SlideOver from "@/Components/SlideOvers/SlideOver.vue";
import FilterDropdown from "@/Components/FilterDropdown.vue";
import AmazingIndexTable from "@/Components/Tables/AmazingIndexTable.vue";
import TrashIcon from "@/Components/Icons/TrashIcon.vue";
import EditIcon from "@/Components/Icons/EditIcon.vue";
import DetailIcon from "@/Components/Icons/DetailIcon.vue";
import PrimaryLinkButton from "@/Components/PrimaryLinkButton.vue";
import DeleteModal from "@/Components/Modals/DeleteModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { filterOptions } from "@/Utils/objectIndex";
import { notify } from "@kyvg/vue3-notification";

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

    const tableColumns = ["id", "name", "location", "actions"];

    const storeFilterOption = computed(
      () => store.getters["objects/filterOption"]
    );

    const startLinks = computed(
      () => store.getters["map/startLinks"]
    );

    const startMarker = computed(
      () => store.getters["map/selectedMarkerType"]
    );

    const storeInstances = computed(() => store.getters["objects/instances"]);

    const selectedLocation = computed(() => store.getters["map/selectedMarker"]);

    const selectedObject = computed({
      get: () => storeFilterOption.value ?? filterOptions[0],
      set: (value) =>
        store.commit("objects/setFilterOption", {
          filterOption: value,
        }),
    });

    const objects = ref(null);

    // TODO: Add links when they are ready
    const instances = ref(
      props.instances.map((i) => ({
        ...i,
        selected: true,
      }))
    );

    const filterDropdown = ref(false);
    const modalIsOpen = ref(false);
    const currentModal = ref(null);
    const itemToDelete = ref(null);

    const modalComponent = computed(() =>
      currentModal.value ? currentModal.value : false
    );

    watch(
      selectedObject,
      (selected) => {
        const title = selected.title.toLowerCase();

        switch (title) {
          case "sources":
            objects.value = instances.value.filter(
              (i) => i.template.category.type === "source"
            );
            break;
          case "sinks":
            objects.value = instances.value.filter(
              (i) => i.template.category.type === "sink"
            );
            break;
          case "links":
            objects.value = props.links;
            break;

          default:
            break;
        }
      },
      { immediate: true, deep: true }
    );

    watch(
      instances,
      (values) => {
        store.commit("objects/setInstances", {
          instances: window._.cloneDeep(values),
        });
      },
      { deep: true, immediate: true }
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
          const type = itemToDelete.value?.template?.category?.type ?? selectedObject.value.title.toLowerCase();

          if (type === "source")
            Inertia.delete(
              route(`objects.sources.destroy`, itemToDelete.value.id),
              {
                onSuccess: (data) => {
                  instances.value = data.props.instances.map((i) => ({
                    ...i,
                    selected: true,
                  }));
                  store.dispatch("map/refreshMap");
                  store.commit("objects/setNotify", {
                      title: 'Source',
                      text: "Source Deleted Successfully",
                      type: 'success'
                  });
                },
                onError: (error) => {
                    store.commit("objects/setNotify", {...error});
                },
              }
            );
          if (type === "sink")
            Inertia.delete(
              route(`objects.sinks.destroy`, itemToDelete.value.id),
              {
                onSuccess: (data) => {
                  instances.value = data.props.instances.map((i) => ({
                    ...i,
                    selected: true,
                  }));
                  store.dispatch("map/refreshMap");
                  store.commit("objects/setNotify", {
                      title: 'Sink',
                      text: "Sink Deleted Successfully",
                      type: 'success'
                  });
                },
                onError: (error) => {
                    store.commit("objects/setNotify", {...error});
                },
              }
            );
          if (type === 'links')
            Inertia.delete(
              route(`objects.links.destroy`, itemToDelete.value.id),
              {
                onSuccess: (data) => {
                  instances.value = data.props.instances.map((i) => ({
                    ...i,
                    selected: true,
                  }));
                  store.dispatch("map/refreshMap");
                  store.commit("objects/setNotify", {
                      title: 'Link',
                      text: "Link Deleted Successfully",
                      type: 'success'
                  });
                },
                onError: (error) => {
                    store.commit("objects/setNotify", {...error});
                },
              }
            );
          objects.value.splice(objects.value.indexOf(itemToDelete.value), 1);

          break;

        default:
          break;
      }
    };

    const onActionRequest = async (route, param) => {
      if (!param) {
        store.dispatch("map/startMarker", selectedObject.value.title.toLowerCase());
      } else {
        store.dispatch("objects/showSlide", { route, props: param });
      }
    };

    return {
      tableColumns,
      filterOptions,
      objects,
      selectedObject,
      filterDropdown,
      modalIsOpen,
      modalComponent,
      startLinks,
      startMarker,
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
