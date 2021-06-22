<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div
      class="flow-root mt-6"
      v-if="notifications.length"
    >
      <div class="flex justify-end">
        <button
          type="button"
          class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          @click="markAllAsRead()"
        >
          Mark all as read
        </button>
      </div>

      <ul class="-mb-8">
        <li
          v-for="(notificationItem, notificationItemIdx) in notifications"
          :key="notificationItem.id"
        >
          <div class="relative pb-8">
            <span
              v-if="(notificationItemIdx !== notifications.length - 1)"
              class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"
              aria-hidden="true"
            />
            <div class="relative flex items-start space-x-3">

              <template
                v-if="notificationItem.data.type === 'invitation'"
                condition="notificationItem.data.type === 'invitation'"
              >
                <div>
                  <div class="relative px-1">
                    <div :class="[
                        notificationItem.read_at
                          ? 'ring-white'
                          : 'ring-blue-500',
                          'h-8 w-8 bg-gray-100 rounded-full ring-8 flex items-center justify-center'
                        ]">
                      <img
                        :class="[
                            notificationItem.read_at
                              ? 'ring-white'
                              : 'ring-blue-500',
                            'rounded-full bg-gray-100 flex items-center justify-center ring-8'
                        ]"
                        :src="notificationItem.data.from.profile_photo_url"
                        alt=""
                      />
                    </div>
                  </div>
                </div>
                <div class="min-w-0 flex-1 py-0">
                  <div class="text-sm leading-8 text-gray-500">
                    <span>
                      <span class="font-semibold">
                        {{notificationItem.data.from.email}}
                      </span>
                      invited you to join
                      <span class="font-semibold">
                        {{notificationItem.data.team.name}} Institution
                      </span>
                    </span>
                    {{ ' ' }}
                    <span class="mr-0.5">
                      <template
                        v-for="(tagItem, tagItemIdx) in notificationItem.data.tags"
                        :key="tagItemIdx"
                      >
                        <div class="relative inline-flex items-center rounded-full border border-gray-300 px-3 py-0.5 text-sm">
                          <span class="absolute flex-shrink-0 flex items-center justify-center">
                            <span
                              :class="['bg-blue-300', 'h-1.5 w-1.5 rounded-full']"
                              aria-hidden="true"
                            />
                          </span>
                          <span class="ml-3.5 font-medium text-gray-900">{{ tagItem }}</span>
                        </div>
                        {{ ' ' }}
                      </template>
                    </span>
                    <span class="whitespace-nowrap">{{ notificationItem.created_at }}</span>
                    {{ ' ' }}
                    <span class="whitespace-nowrap italic text-blue-500">{{ notificationItem.read_at ? "": "Unread" }}</span>
                  </div>
                  <div class="mt-2 text-sm text-gray-700">
                    <p>
                      {{ notificationItem.data.description }}
                    </p>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </li>
      </ul>
    </div>
    <div v-else>
      <div class="flex flex-col place-items-center place-content-center h-screen">
        <h2 class="text-3xl leading-8 font-extrabold tracking-tight text-gray-500 sm:text-4xl">
          There are no notifications at this time.
        </h2>
        <h2 class="text-3xl leading-8 font-extrabold tracking-tight text-gray-500 sm:text-4xl">
          Come back later.
        </h2>
      </div>
    </div>
  </div>
</template>

<script>
import { UserCircleIcon } from "@heroicons/vue/solid";

export default {
  components: {
    UserCircleIcon,
  },

  props: {
    notifications: {
      type: Array,
      default: [],
    },
  },

  setup(props) {
    const markAllAsRead = async () => {
      await axios.post(route("notifications.markAllAsRead"));
    };
    console.log(props.notifications);
    return {
      markAllAsRead,
    };
  },
};
</script>
