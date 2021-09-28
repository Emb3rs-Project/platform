<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-4">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white shadow overflow-hidden sm:rounded-md space-y-4">
        <!-- Section title -->
        <div class="px-4 py-5 sm:px-6">
          <div class="flex space-x-3 justify-between">
            <div>
              <p class="text-2xl font-bold text-gray-900">
                Notifications
              </p>
            </div>
            <div
              class="flex-shrink-0 self-center flex"
              v-if="unreadNotifications.length || readNotifications.length"
            >
              <Menu
                as="div"
                class="relative z-30 inline-block text-left"
              >
                <div>
                  <MenuButton class="-m-2 p-2 rounded-full flex items-center text-gray-400 hover:text-gray-600">
                    <span class="sr-only">Open options</span>
                    <DotsVerticalIcon
                      class="h-5 w-5"
                      aria-hidden="true"
                    />
                  </MenuButton>
                </div>

                <transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <MenuItems class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                      <MenuItem
                        v-slot="{ active }"
                        v-if="unreadNotifications.length"
                      >
                      <button
                        type="button"
                        @click="markAllAsRead"
                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                      >
                        <CheckIcon
                          class="mr-3 h-5 w-5 text-green-400"
                          aria-hidden="true"
                        />
                        <span>Mark all as read</span>
                      </button>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                      <button
                        type="button"
                        @click="removeAll"
                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                      >

                        <XCircleIcon
                          class="mr-3 h-5 w-5 text-red-400"
                          aria-hidden="true"
                        />
                        <span>Remove all notifications</span>
                      </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
        </div>

        <!-- New Notifications -->
        <div
          v-if="unreadNotifications.length || readNotifications.length"
          class="px-4 sm:px-6"
        >
          <!-- Title -->
          <div class="relative">
            <div
              class="absolute inset-0 flex items-center"
              aria-hidden="true"
            >
              <div class="w-full border-t border-gray-300" />
            </div>
            <div class="relative flex justify-start">
              <span class="pr-3 bg-white text-xl font-semibold text-gray-600">
                New
              </span>
            </div>
          </div>

          <!-- Notifications -->
          <div v-if="unreadNotifications.length">
            <ul role="list">
              <li
                v-for="unreadNotification in unreadNotifications"
                :key="unreadNotification.id"
                class="py-2"
              >
                <div class="flex items-center rounded-md bg-gray-100">
                  <div class="w-full">
                    <NotificationFeedItem :notification="unreadNotification" />
                  </div>
                  <div>
                    <Menu
                      as="div"
                      class="relative z-10 inline-block text-left"
                    >
                      <div>
                        <!-- <MenuButton class="-m-2 p-2 rounded-full flex items-center "> -->
                        <MenuButton class="mr-2 inline-flex items-center p-1 border border-transparent rounded-full text-gray-400 hover:bg-gray-200 hover:shadow-sm focus:outline-none">
                          <span class="sr-only">Open notification options</span>
                          <DotsVerticalIcon
                            class="h-5 w-5"
                            aria-hidden="true"
                          />
                        </MenuButton>
                      </div>
                      <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                      >
                        <MenuItems class="z-50 origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                          <div class="py-1">
                            <MenuItem v-slot="{ active }">
                            <button
                              type="button"
                              @click="markAsRead(unreadNotification.id)"
                              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                            >
                              <CheckCircleIcon
                                class="mr-3 h-5 w-5 text-green-400"
                                aria-hidden="true"
                              />
                              <span>Mark as read</span>
                            </button>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <button
                              type="button"
                              @click="remove('unreadNotifications', unreadNotification.id)"
                              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                            >
                              <XCircleIcon
                                class="mr-3 h-5 w-5 text-red-400"
                                aria-hidden="true"
                              />
                              <span>Remove</span>
                            </button>
                            </MenuItem>
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>
                  </div>
                </div>
              </li>
            </ul>
          </div>
          <!-- No new notifications placeholder -->
          <div
            v-else
            class="flex flex-col items-center justify-center bg-gray-100 rounded-md h-32"
          >
            <div>
              <CheckCircleIcon class="h-10 w-10 text-green-500" />
            </div>
            <p class="text-xl font-bold text-gray-400">
              You're All Caugth Up
            </p>
          </div>
        </div>

        <!-- Read Notifications -->
        <div
          v-if="readNotifications.length"
          class="px-4 sm:px-6"
        >
          <!-- Title -->
          <div class="relative">
            <div
              class="absolute inset-0 flex items-center"
              aria-hidden="true"
            >
              <div class="w-full border-t border-gray-300" />
            </div>
            <div class="relative flex justify-start">
              <span class="pr-3 bg-white text-xl font-semibold text-gray-600">
                Read
              </span>
            </div>
          </div>

          <!-- Read Notifications -->
          <div>
            <ul role="list">
              <li
                v-for="readNotification in readNotifications"
                :key="readNotification.id"
                class="py-2"
              >
                <div class="flex items-center rounded-md bg-gray-100">
                  <div class="w-full">
                    <NotificationFeedItem :notification="readNotification" />
                  </div>
                  <div>
                    <Menu
                      as="div"
                      class="relative z-10 inline-block text-left"
                    >
                      <div>
                        <MenuButton class="mr-2 inline-flex items-center p-1 border border-transparent rounded-full text-gray-400 hover:bg-gray-200 hover:shadow-sm focus:outline-none">
                          <span class="sr-only">Open notification options</span>
                          <DotsVerticalIcon
                            class="h-5 w-5"
                            aria-hidden="true"
                          />
                        </MenuButton>
                      </div>
                      <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                      >
                        <MenuItems class="z-50 origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                          <div class="py-1">
                            <MenuItem v-slot="{ active }">
                            <button
                              type="button"
                              @click="remove('readNotifications', readNotification.id)"
                              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                            >
                              <XCircleIcon
                                class="mr-3 h-5 w-5 text-red-400"
                                aria-hidden="true"
                              />
                              <span>Remove</span>
                            </button>
                            </MenuItem>
                          </div>
                        </MenuItems>
                      </transition>
                    </Menu>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>

        <!-- No notifications placeholder -->
        <div
          v-if="!readNotifications.length && !unreadNotifications.length"
          class="flex items-center justify-center h-60"
        >
          <div>
            <p class="text-2xl font-bold text-gray-300">
              It looks like there are no notifications at this time.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { onBeforeUnmount, ref } from "vue";
import { useStore } from "vuex";

import NotificationFeedItem from "@/Components/Notifications/NotificationFeedItem.vue";

import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { DotsVerticalIcon, CheckIcon } from "@heroicons/vue/solid";
import { CheckCircleIcon, XCircleIcon } from "@heroicons/vue/outline";

export default {
  components: {
    NotificationFeedItem,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    DotsVerticalIcon,
    CheckIcon,
    CheckCircleIcon,
    XCircleIcon,
  },

  props: {
    unreadNotifications: {
      type: Array,
      required: true,
    },
    readNotifications: {
      type: Array,
      required: true,
    },
  },

  setup(props) {
    const store = useStore();
    const unreadNotifications = ref(props.unreadNotifications);
    const readNotifications = ref(props.readNotifications);

    onBeforeUnmount(() => {
      unsubscribe();
    });

    const unsubscribe = store.subscribe((mutation, _) => {
      if (mutation.type === "notifications/setUnreadNotifications") {
        // prettier-ignore
        for (const _newUnreadNotification of mutation.payload.unreadNotifications) {
          const duplicate = unreadNotifications.value.find(
            (n) => n.id === _newUnreadNotification.id
          );

          if (!duplicate)
            unreadNotifications.value.unshift(_newUnreadNotification);
        }
      }
    });

    const markAllAsRead = () => {
      window.axios.post(route("notifications.mark-all-as-read")).then(() => {
        readNotifications.value = [
          ...readNotifications.value,
          ...unreadNotifications.value,
        ];

        unreadNotifications.value = [];
      });
    };

    const removeAll = () => {
      window.axios.post(route("notifications.remove-all")).then(() => {
        unreadNotifications.value = [];
        readNotifications.value = [];
      });
    };

    const markAsRead = (uuid) => {
      window.axios.patch(route("notifications.update", uuid)).then(() => {
        const unreadIndex = unreadNotifications.value.findIndex(
          (n) => n.id == uuid
        );

        if (unreadIndex !== -1) {
          const notification = unreadNotifications.value[unreadIndex];

          unreadNotifications.value.splice(unreadIndex, 1);

          readNotifications.value.unshift(notification);
        }
      });
    };

    const remove = (type, uuid) => {
      window.axios.delete(route("notifications.destroy", uuid)).then(() => {
        if (type === "unreadNotifications") {
          const unreadIndex = unreadNotifications.value.findIndex(
            (n) => n.id == uuid
          );

          if (unreadIndex !== -1)
            unreadNotifications.value.splice(unreadIndex, 1);

          return;
        }

        if (type === "readNotifications") {
          const readIndex = readNotifications.value.findIndex(
            (n) => n.id == uuid
          );

          if (readIndex !== -1) readNotifications.value.splice(readIndex, 1);
        }
      });
    };

    return {
      unreadNotifications,
      readNotifications,
      markAllAsRead,
      removeAll,
      markAsRead,
      remove,
    };
  },
};
</script>
