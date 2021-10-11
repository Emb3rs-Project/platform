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
          <SearchResult
            :loading="loading"
            :results="results"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, watch } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SearchInput from "@/Components/Search/SearchInput.vue";
import SearchResult from "@/Components/Search/SearchResult.vue";

import { SearchIcon } from "@heroicons/vue/outline";

export default {
  components: {
    AppLayout,
    SiteHead,
    SearchIcon,
    SearchInput,
    SearchResult,
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
    const results = ref([]);

    const lazilySearch = window._.debounce((query) => {
      if (query !== "") search(query);
      else results.value = [];

      updateUrl(query);
    }, 250);

    const search = (query) => {
      loading.value = true;

      window.axios
        .post(route("search.query"), { keyword: query })
        .then(({ data }) => {
          loading.value = false;
          results.value = [];

          for (const resource in data) {
            if (!data[resource].length) continue;

            for (const entity of data[resource]) {
              results.value.push({
                ...entity,
                type: resource,
              });
            }
          }
        })
        .catch((e) => console.error(e));
    };

    const updateUrl = (url) => {
      if (url !== "")
        window.history.pushState({}, window.document.title, `?keyword=${url}`);
      else window.history.pushState({}, window.document.title, "search");
    };

    watch(query, (value) => lazilySearch(value), { immediate: true });

    return {
      loading,
      query,
      results,
    };
  },
};
</script>
