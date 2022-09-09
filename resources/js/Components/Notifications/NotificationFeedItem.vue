<template>
  <div class="p-2">
    <div class="flex items-center w-full">
      <div
        v-if="notification.data.type !== 'system'"
        class="shrink-0 inline-flex rounded-full border-2 border-white"
      >
        <img
          class="h-12 w-12 rounded-full"
          :src="notification.data.from.profile_photo_url"
          alt=""
        />
      </div>
      <div class="flex flex-col ml-3 gap-y-1">
        <div class="text-sm font-medium text-gray-900">
          <div v-if="notification.data.type === 'invitation'">
            <span class="font-semibold">{{ notification.data.from.name }}</span>
            ({{ notification.data.from.email }})
            invited you to join
            <span class="font-semibold">
              {{ notification.data.team.name }}
            </span>
            Institution
          </div>
          <div v-else-if="notification.data.type === 'inclusion'">
            <span class="font-semibold">{{ notification.data.from.name }}</span>
            ({{ notification.data.from.email }})
            added you in
            <span class="font-semibold">
              {{ notification.data.team.name }}
            </span>
            Institution
          </div>
          <div v-else-if="notification.data.type === 'object'">
            <span class="font-semibold">{{ notification.data.from.name }}</span>
            ({{ notification.data.from.email }})
            {{ notification.data.description }}
            <span class="font-semibold">
              {{ notification.data.team.name }}
            </span>
            Institution
          </div>
        <div v-else-if="notification.data.type === 'simulation'">
            <span class="font-semibold">{{ notification.data.from.name }}</span>
            ({{ notification.data.from.email }})
            {{ notification.data.description }}
            <span class="font-semibold">
          {{ notification.data.team.name }}
        </span>
            Institution
        </div>
            <div v-else-if="notification.data.type === 'import'">
                <span class="font-semibold">{{ notification.data.from.name }}</span>
                ({{ notification.data.from.email }})
                <span v-html="notification.data.description"></span>
                <span class="font-semibold">
          {{ notification.data.team.name }}
        </span>
                Institution
            </div>
          <div v-else>DEBUG: Unknown notification type passed</div>
        </div>
        <!-- Time -->
        <div class="text-sm font-medium text-gray-500">
          {{ getFriendlyLifetime(notification.created_at) }}
        </div>
        <!-- Tags -->
        <div
          v-for="(tag, tagIdx) in notification.data.tags"
          :key="tagIdx"
          class="flex flex-wrap gap-1"
        >
          <div class="relative inline-flex items-center rounded-full border border-gray-300 px-3 py-0.5 text-sm">
            <span class="absolute shrink-0 flex items-center justify-center">
              <span
                class="h-1.5 w-1.5 rounded-full bg-blue-500"
                aria-hidden="true"
              />
            </span>
            <span class="ml-3.5 font-medium text-gray-900">
              <Link
                v-if="tag && tag.hasOwnProperty('path')"
                :key="tag.name"
                :href="route(tag.path, notification.data.contentId)"
                class="group flex items-center text-sm font-medium rounded-md focus:outline-none"
                @click.prevent="
                  onActionRequest(`${tag.path}`, notification.data.contentId, notification.id)
                ">
              {{ tag.name }}
              </Link>
            </span>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { getFriendlyLifetime } from "@/Helpers/helpers";
import { Link } from "@inertiajs/inertia-vue3";
import { useStore } from "vuex";
import {Inertia} from "@inertiajs/inertia";

export default {
  props: {
    notification: {
      type: Object,
      required: true,
    },
  },

  components: {
    Link,
  },

  setup() {
    const store = useStore();

    const onActionRequest = (path, param, uuid) => {
      markAsRead(uuid);
      Inertia.visit(route(path, param))
      //store.commit("objects/setShowObject", { route, param });
    };

    const markAsRead = (uuid) => {
      window.axios.patch(route("notifications.update", uuid));
    };

    return {
      onActionRequest,
      getFriendlyLifetime,
    };
  },
};
</script>
