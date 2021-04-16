<template>
  <table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
      <tr>
        <!-- Checkbox -->
        <th scope="col" class="relative px-6 py-3" v-if="hasCheckbox">
          <div class="flex justify-center items-center h-5">
            <input
              type="checkbox"
              class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
              v-model="allSelected"
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
        v-for="(item, index) in model"
        :key="index"
        :class="index % 2 ? 'bg-gray-50' : 'bg-white'"
      >
        <!-- Checkbox -->
        <td class="px-6 py-4 whitespace-nowrap" v-if="hasCheckbox">
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
</template>

<script>
import { computed, ref, watch } from "vue";

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
      required: false,
      default: true,
    },
  },
  emits: ["update:modelValue", "onUpdateSelection"],
  setup(props, { emit }) {
    /**
     * Properties
     */
    const mainCheckbox = ref();
    const model = computed({
      get() {
        return props.modelValue;
      },
      set(value) {
        emit("update:modelValue", value);
      },
    });

    const allSelected = computed({
      get() {
        return model.value.filter((m) => m.selected).length > 0;
      },
      set(value) {
        model.value.forEach((m) => (m.selected = value));
      },
    });

    watch(
      () => model.value,
      () => onSelectRow(),
      { deep: true }
    );

    /**
     * Methods
     */
    const onSelectRow = () => {
      let selected = model.value.filter((m) => m.selected).length;

      if (selected > 0 && selected !== model.value.length)
        mainCheckbox.value.indeterminate = true;
      else mainCheckbox.value.indeterminate = false;

      emit("onUpdateSelection");
    };

    return {
      allSelected,
      model,
      onSelectRow,
      mainCheckbox,
    };
  },
};
</script>
