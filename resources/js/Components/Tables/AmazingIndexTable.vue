<template>
  <div :class="headerClasses">
    <table class="table-auto min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-200">
        <tr>
          <!-- Checkbox -->
          <th
            scope="col"
            class="relative px-6 py-3"
            v-if="hasCheckbox"
          >
            <div class="flex justify-center items-center h-5">
              <input
                type="checkbox"
                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                v-model="selectAll"
                ref="mainCheckbox"
              />
            </div>
          </th>
          <!-- Custom Headers -->
          <th
            v-for="column in columns"
            :key="column"
            scope="col"
            class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-left pl-4"
          >
            <slot :name="'header-' + column"></slot>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(item, index) in items"
          :key="index"
          :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
        >
          <!-- Checkbox -->
          <td
            class="px-6 py-4 whitespace-nowrap"
            v-if="hasCheckbox"
          >
            <div class="flex justify-center items-center h-5">
              <input
                v-model="item.selected"
                type="checkbox"
                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded disabled:opacity-50"
              />
            </div>
          </td>

          <!-- Custom Columns -->
          <slot
            v-for="column in columns"
            :key="column"
            :name="'body-' + column"
            :item="item"
          ></slot>
        </tr>
      </tbody>
    </table>
  </div>

</template>

<script>
import { computed, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";

export default {
  components: {},

  props: {
    modelValue: {
      type: Array,
      required: true,
    },
    columns: {
      type: Array,
      required: true,
    },
    hasCheckbox: {
      type: Boolean,
      default: true,
    },
    headerClasses: {
      type: String,
      default: "",
    },
    tableClasses: {
      type: String,
      default: "",
    },
  },

  emits: ["update:modelValue", "onUpdateSelection"],

  setup(props, ctx) {
    const store = useStore();

    const mainCheckbox = ref(null);

    const items = computed(() => props.modelValue);

    const selectAll = computed({
      get: () => items.value.filter((m) => m.selected).length > 0,
      set: (value) => items.value.forEach((m) => (m.selected = value)),
    });

    watch(
      () => items,
      () => {
        onSelectRow();
      },
      { deep: true }
    );

    const onSelectRow = () => {
      console.log("onSelectRow", onSelectRow);
      let selectedItems = items.value.filter((m) => m.selected).length;

      if (selectedItems > 0 && selectedItems !== items.value.length)
        mainCheckbox.value.indeterminate = true;
      else mainCheckbox.value.indeterminate = false;

      ctx.emit("onUpdateSelection");
    };

    onMounted(() => onSelectRow());

    return {
      selectAll,
      items,
      onSelectRow,
      mainCheckbox,
    };
  },
};
</script>
