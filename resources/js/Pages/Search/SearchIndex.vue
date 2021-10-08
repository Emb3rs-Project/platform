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
            <TextSkeleton
              :columns="1"
              :count="3"
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
                      v-for="(entity, entityIdx) in resource"
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
  </AppLayout>
</template>

<script>
import { ref, watch } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import SiteHead from "@/Components/SiteHead.vue";
import SearchInput from "@/Components/Search/SearchInput.vue";
import TextSkeleton from "@/Components/Skeletons/TextSkeleton.vue";
import SearchItem from "@/Components/Search/SearchItem.vue";

import { SearchIcon } from "@heroicons/vue/outline";

export default {
  components: {
    AppLayout,
    SiteHead,
    SearchIcon,
    SearchInput,
    TextSkeleton,
    SearchItem,
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

    return {
      loading,
      query,
      results,
    };
  },
};
</script>
