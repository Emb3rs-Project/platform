<template>
  <div v-if="loading">
    <TextSkeleton
      :columns="1"
      :count="2"
    />
  </div>
  <div v-else>
    <div v-if="results.length">
      <ul
        role="list"
        class="mt-4 grid grid-cols-1 divide-y divide-gray-200 p-1"
      >
        <li
          v-for="(entity, entityIdx) in results"
          :key="entityIdx"
          class="hover:bg-gray-100 hover:rounded-md px-2 hover:cursor-pointer"
        >
          <SearchItem
            :entity="entity"
            @click="onClick(entity)"
          />
        </li>
      </ul>
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
</template>

<script>
import { useStore } from "vuex";
import { Inertia } from "@inertiajs/inertia";
import mapUtils from "@/Utils/map.js";

import TextSkeleton from "@/Components/Skeletons/TextSkeleton.vue";
import SearchItem from "@/Components/Search/SearchItem.vue";
import route from "ziggy";

export default {
  components: {
    TextSkeleton,
    SearchItem,
  },

  props: {
    loading: {
      type: Boolean,
      required: true,
    },
    results: {
      type: Array,
      required: true,
    },
  },

  setup() {
    const store = useStore();

    const onClick = (entity) => {
      if (
        entity.type === "sources" ||
        entity.type === "sinks" ||
        entity.type === "links"
      )
        Inertia.visit(route("objects.index"), {
          onSuccess: (_) => {
            const route = `objects.${entity.type}.show`;

            store.dispatch("objects/showSlide", {
              route,
              props: entity.id,
            });
          },
        });
      else if (entity.type === "locations")
        Inertia.visit(route("objects.index"), {
          onSuccess: (_) => {
            store.dispatch("map/centerAt", {
              marker: {
                type: "point",
                data: {
                  center: entity.data.center,
                },
              },
            });
          },
        });
      else if (entity.type === "projects")
        Inertia.visit(route("projects.show", entity.id));
      else if (entity.type === "simulations")
        Inertia.visit(
          route("projects.simulations.show", {
            project: entity.project_id,
            simulation: entity.id,
          })
        );
      else if (entity.type === "news")
        Inertia.visit(route("news.show", entity.id));
      else if (entity.type === "faqs")
        Inertia.visit(
          route("help.index", {
            faq: entity.id,
          })
        );
    };

    return {
      onClick,
    };
  },
};
</script>
