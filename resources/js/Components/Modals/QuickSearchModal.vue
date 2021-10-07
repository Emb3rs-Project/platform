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
          <div class="inline-block align-bottom bg-white rounded-lg pt-5 pb-10 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full p-6">
            <div class="mx-auto">
              <div class="shadow-md z-10">
                <SearchInput
                  v-model="query"
                  placeholder="Search"
                />
              </div>
              <!-- sm:max-h-[40rem] -->
              <div class="mt-10 overflow-y-auto sm:max-h-96">
                <div v-if="query === ''">
                  <!-- <div class="flex items-center justify-center h-60">
                    <p class="text-2xl font-bold text-gray-500">
                      Use the search box to find what you are looking for.
                    </p>
                  </div> -->
                </div>
                <div v-else>
                  <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                    Search results
                  </h3>
                  <div v-if="loading">
                    <!-- Loading placeholder -->
                    <ul
                      role="list"
                      class="mt-4 grid grid-cols-1 gap-4"
                    >
                      <li
                        v-for="index in [1, 2]"
                        :key="index"
                      >
                        <div class="border border-gray-300 shadow rounded-md p-4 w-full">
                          <div class="animate-pulse flex space-x-4">
                            <div class="flex-1 space-y-4 py-1">
                              <div class="h-4 bg-gray-400 rounded w-3/4"></div>
                              <div class="space-y-2">
                                <div class="h-4 bg-gray-400 rounded"></div>
                                <div class="h-4 bg-gray-400 rounded w-5/6"></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
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
                              v-for="(entity, entityIdx) in resource"
                              :key="entityIdx"
                            >
                              <div class="border border-gray-300 shadow rounded-md p-4">
                                <div class="flex space-x-4">
                                  <div class="space-y-4 py-1 whitespace-normal w-full">
                                    <div class="flex justify-between">
                                      <p class="text-xl font-semibold text-gray-800">
                                        {{ beautifyResourceName(resourceName) }}
                                      </p>
                                      <p class="text-sm text-gray-500">
                                        Last update: {{ getFriendlyLifetime(entity.updated_at) }}
                                      </p>
                                    </div>

                                    <div class="space-y-2">
                                      <div v-if="resourceName === 'sources' || resourceName === 'sinks'">
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            ID
                                          </span>
                                          : {{ entity.id }}
                                        </p>
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            Name
                                          </span>
                                          : {{ entity.name }}
                                        </p>
                                      </div>
                                      <div v-else-if="resourceName === 'links'">
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            ID
                                          </span>
                                          : {{ entity.id }}
                                        </p>
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            Name
                                          </span>
                                          : {{ entity.name }}
                                        </p>
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            Description
                                          </span>
                                          : {{ entity.description ?? 'Not provided.' }}
                                        </p>
                                      </div>
                                      <div v-else-if="resourceName === 'locations'">
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            ID
                                          </span>
                                          : {{ entity.id }}
                                        </p>
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            Name
                                          </span>
                                          : {{ entity.name }}
                                        </p>
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            Description
                                          </span>
                                          : {{ entity.description ?? 'Not provided.' }}
                                        </p>
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            Coordinates
                                          </span>
                                          : {{ entity.data.center[0] }} , {{ entity.data.center[1] }}
                                        </p>
                                      </div>
                                      <div v-else-if="resourceName === 'projects'">
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            ID
                                          </span>
                                          : {{ entity.id }}
                                        </p>
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            Name
                                          </span>
                                          : {{ entity.name }}
                                        </p>
                                        <p class="text-base">
                                          <span class="font-semibold">
                                            Description
                                          </span>
                                          : {{ entity.description ?? 'Not provided.' }}
                                        </p>
                                      </div>
                                      <div v-else-if="resourceName === 'news'">
                                        <p class="text-lg">{{ entity.title }}</p>
                                        <p
                                          class="text-base text-gray-600"
                                          v-html="entity.content"
                                        ></p>
                                      </div>
                                      <div v-else-if="resourceName === 'faqs'">
                                        <p class="text-lg">{{ entity.question }}</p>
                                        <p
                                          class="text-base text-gray-600"
                                          v-html="entity.answer"
                                        ></p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
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

        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script>
import { computed, ref, watch } from "vue";
import { getFriendlyLifetime } from "@/Helpers/helpers";
import pluralize from "pluralize";

import SearchInput from "@/Components/Forms/SearchInput.vue";

import {
  Dialog,
  DialogOverlay,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon } from "@heroicons/vue/outline";

export default {
  components: {
    Dialog,
    DialogOverlay,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
    CheckIcon,
    SearchInput,
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
    const query = ref(props.keyword ?? "");
    const results = ref({});

    const lazilySearch = window._.debounce((query) => {
      if (query !== "") search(query);
      else results.value = {};

      updateUrl(query);
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

    const updateUrl = (url) => {
      if (url !== "")
        window.history.pushState({}, window.document.title, `?keyword=${url}`);
      else window.history.pushState({}, window.document.title, "search");
    };

    watch(query, (value) => lazilySearch(value), { immediate: true });

    const beautifyResourceName = (name) => {
      const signularName = getSingular(name);

      return signularName.charAt(0).toUpperCase() + signularName.slice(1);
    };

    const getSingular = (value) => pluralize.singular(value);

    return {
      open,
      getFriendlyLifetime,
      loading,
      query,
      results,
      beautifyResourceName,
    };
  },
};
</script>
