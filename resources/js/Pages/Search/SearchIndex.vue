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
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
          Search results
        </h3>

        <div
          v-for="(resource, resourceIdx) in results"
          :key="resourceIdx"
        >
          <div v-if="resource.length">
            <div class="relative">
              <div
                class="absolute inset-0 flex items-center"
                aria-hidden="true"
              >
                <div class="w-full border-t border-gray-300" />
              </div>
              <div class="relative flex justify-start">
                <span class="pr-3 bg-gray-50 text-sm font-medium text-gray-900">
                  {{ resourceIdx }}
                </span>
              </div>
            </div>

            <div
              v-for="(entity, entityIdx) in resource"
              :key="entityIdx"
            ></div>
            <ul
              role="list"
              class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2"
            >
              <li
                v-for="(entity, entityIdx) in resource"
                :key="entityIdx"
              >
                <button
                  type="button"
                  class="group p-2 w-full flex items-center justify-between rounded-full border border-gray-300 shadow-sm space-x-3 text-left hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <span class="min-w-0 flex-1 flex items-center space-x-3">
                    <span class="block min-w-0 flex-1">
                      <span class="block text-sm font-medium text-gray-900 truncate">{{ entity.id }}</span>
                      <span class="block text-sm font-medium text-gray-500 truncate">{{entity.name }}</span>
                    </span>
                  </span>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script>
import { ref, watch } from "vue";

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
    const query = ref(props.keyword ?? "");
    const results = ref([]);

    const lazilySearch = window._.debounce((query) => {
      if (query !== "") onSearch(query);
      else results.value = [];

      updateUrl(query);
    }, 250);

    const onSearch = (query) => {
      window.axios
        .post(route("search.query"), { keyword: query })
        .then(({ data }) => (results.value = data))
        .catch((e) => console.log(e));
    };

    const updateUrl = (url) => {
      if (url !== "")
        window.history.pushState({}, window.document.title, `?keyword=${url}`);
      else window.history.pushState({}, window.document.title, "search");
    };

    watch(query, (value) => lazilySearch(value), { immediate: true });

    return {
      query,
      results,
      onSearch,
    };
  },
};
</script>
