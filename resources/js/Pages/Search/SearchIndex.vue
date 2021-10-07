<template>
  <AppLayout>

    <SiteHead title="Search" />

    <div class="p-4 max-w-md mx-auto sm:max-w-5xl">
      <div>
        <div class="text-center">
          <SearchIcon class="mx-auto p-2 h-12 w-auto text-white bg-yellow-600 rounded-xl" />
          <h2 class="mt-2 text-xl font-medium text-gray-900">
            Search for <span class="font-extrabold">anything</span> you need.
          </h2>
          <p class="mt-1 text-sm text-gray-500">
            You cant find what you are looking for? Try searching for it.
          </p>
        </div>
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
                v-for="index in [1, 2, 3]"
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
  </AppLayout>
</template>

<script>
import { ref, watch } from "vue";
import { getFriendlyLifetime } from "@/Helpers/helpers";
import pluralize from "pluralize";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SearchInput from "@/Components/Forms/SearchInput.vue";
import { SearchIcon } from "@heroicons/vue/outline";

export default {
  components: {
    AppLayout,
    SiteHead,
    SearchIcon,
    SearchInput,
  },

  props: {
    keyword: {
      type: String,
      required: false,
    },
  },

  setup(props) {
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
      getFriendlyLifetime,
      loading,
      query,
      results,
      beautifyResourceName,
    };
  },
};
</script>
