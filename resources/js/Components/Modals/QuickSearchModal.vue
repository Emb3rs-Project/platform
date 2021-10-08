<template>
  <TransitionRoot
    as="template"
    :show="open"
  >
    <Dialog
      as="div"
      class="fixed z-10 inset-0 pt-16 sm:pt-24"
      @close="open = false"
    >
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0"
          enter-to="opacity-100"
          leave="ease-in duration-200"
          leave-from="opacity-100"
          leave-to="opacity-0"
        >
          <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
        </TransitionChild>

        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          enter-to="opacity-100 translate-y-0 sm:scale-100"
          leave="ease-in duration-200"
          leave-from="opacity-100 translate-y-0 sm:scale-100"
          leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <div class="inline-block align-bottom bg-white rounded-lg pb-10 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full p-4">
            <div class="mx-auto">
              <div class="shadow-md">
                <SearchInput
                  v-model="query"
                  placeholder="Search"
                />
              </div>
              <div class="mt-10">
                <div v-if="query === ''">
                  <div class="flex items-center justify-center h-60">
                    <p class="text-2xl font-bold text-gray-500">
                      Use the search box to find what you are looking for.
                    </p>
                  </div>
                </div>
                <div v-else>
                  <div class="flex justify-between w-full pb-2">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                      Search results
                    </h3>
                    <Link
                      v-if="showMoreResults"
                      :href="route('search.index', { keyword: query })"
                      class="text-sm text-blue-400"
                    >
                    Click to see more results
                    </Link>
                  </div>

                  <div class="overflow-y-auto sm:max-h-96">
                    <div v-if="loading">
                      <TextSkeleton
                        :columns="1"
                        :count="2"
                      />
                    </div>
                    <div v-else>
                      <div v-if="Object.keys(results).length">
                        <div
                          v-for="(resource, resourceName) in results"
                          :key="resourceName"
                        >
                          <div v-if="resource.length">
                            <ul
                              role="list"
                              class="mt-4 grid grid-cols-1 gap-4"
                            >
                              <li
                                v-for="(entity, entityIdx) in resource.slice(0, 1)"
                                :key="entityIdx"
                              >
                                <SearchItem
                                  :resourceName="resourceName"
                                  :entity="entity"
                                />
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                      <div
                        v-else
                        class="flex items-center justify-center h-60"
                      >
                        <p class="text-2xl font-bold text-gray-400">
                          It looks like there are no results for this search.
                        </p>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import { computed, ref, watch } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

import SearchInput from "@/Components/Search/SearchInput.vue";
import TextSkeleton from "@/Components/Skeletons/TextSkeleton.vue";
import SearchItem from "@/Components/Search/SearchItem.vue";

import {
  Dialog,
  DialogOverlay,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { SearchIcon } from "@heroicons/vue/solid";

export default {
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    Link,
    SearchIcon,
    SearchInput,
    TextSkeleton,
    SearchItem,
  },

  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
  },

  emits: ["update:modelValue"],

  setup(props, ctx) {
    const open = computed({
      get: () => props.modelValue,
      set: (value) => ctx.emit("update:modelValue", value),
    });

    const loading = ref(false);
    const query = ref("");
    const results = ref({});

    const lazilySearch = window._.debounce((query) => {
      if (query !== "") search(query);
      else results.value = {};
    }, 250);

    const search = (query) => {
      loading.value = true;

      window.axios
        .post(route("search.query"), { keyword: query })
        .then(({ data }) => {
          loading.value = false;

          let counter = 0;
          for (const resource in data) if (!data[resource].length) counter++;

          if (counter === Object.keys(data).length) results.value = {};
          else results.value = data;
        })
        .catch((e) => console.log(e));
    };

    const showMoreResults = computed(() => {
      const data = results.value;
      for (const resource in data) if (data[resource].length > 1) return true;

      return false;
    });

    watch(query, (value) => lazilySearch(value), { immediate: true });

    return {
      open,
      loading,
      query,
      results,
      showMoreResults,
    };
  },
};
</script>
