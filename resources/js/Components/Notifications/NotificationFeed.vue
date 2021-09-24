<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-4">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white shadow overflow-hidden sm:rounded-md space-y-4">
        <div class="px-4 py-5 sm:px-6">
          <div class="flex space-x-3 justify-between">
            <div>
              <p class="text-2xl font-bold text-gray-900">
                Notifications
              </p>
            </div>
            <div
              class="flex-shrink-0 self-center flex"
              v-if="notifications.length"
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
                      <MenuItem v-slot="{ active }">
                      <button
                        type="button"
                        @click="markAllAsRead"
                        :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                      >
                        <CheckIcon
                          class="mr-3 h-5 w-5 text-gray-400"
                          aria-hidden="true"
                        />
                        <span>Mark all as read</span>
                      </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </div>
          </div>
        </div>
        <div v-if="unreadNotifications.length">
          <div class="px-4 sm:px-6">
            <!-- <p class=" space-x-3 text-xl font-semibold text-gray-600">
              Unread
            </p> -->
            <div class="relative">
              <div
                class="absolute inset-0 flex items-center"
                aria-hidden="true"
              >
                <div class="w-full border-t border-gray-300" />
              </div>
              <div class="relative flex justify-start">
                <span class="pr-3 bg-white text-xl font-semibold text-gray-600">
                  Unread
                </span>
              </div>
            </div>
          </div>
          <ul
            role="list"
            class="divide-y divide-gray-200"
          >
            <li
              v-for="unreadNotification in unreadNotifications"
              :key="unreadNotification.id"
              class="px-4 py-4 sm:px-6"
            >
              <div class="flex group">
                <div class="w-full">
                  <NotificationFeedItem :notification="unreadNotification" />
                </div>
                <div>
                  <Menu
                    as="div"
                    class="relative z-10 inline-block text-left"
                  >
                    <div>
                      <MenuButton class="-m-2 p-2 rounded-full flex items-center ">
                        <span class="sr-only">Open notification options</span>
                        <DotsVerticalIcon
                          class="h-5 w-5 "
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
                          <MenuItem v-slot="{ active }">
                          <button
                            type="button"
                            @click="markAsRead(unreadNotification.id)"
                            :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                          >
                            <CheckCircleIcon
                              class="mr-3 h-5 w-5 text-gray-400"
                              aria-hidden="true"
                            />
                            <span>Mark as read</span>
                          </button>
                          </MenuItem>
                          <MenuItem v-slot="{ active }">
                          <button
                            type="button"
                            @click="remove(readNotification.id)"
                            :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                          >
                            <XCircleIcon
                              class="mr-3 h-5 w-5 text-gray-400"
                              aria-hidden="true"
                            />
                            <span>Remove notification</span>
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
        <div v-if="readNotifications.length">
          <div class="px-4 sm:px-6">
            <!-- <p class="space-x-3 text-xl font-semibold text-gray-600">
              Read
            </p> -->
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
          </div>
          <div>
            <ul
              role="list"
              class="divide-y divide-gray-200"
            >
              <li
                v-for="readNotification in readNotifications"
                :key="readNotification.id"
                class="px-4 py-4 sm:px-6"
              >
                <div class="flex group">
                  <div class="w-full">
                    <NotificationFeedItem :notification="readNotification" />
                  </div>
                  <div>
                    <Menu
                      as="div"
                      class="relative z-10 inline-block text-left"
                    >
                      <div>
                        <MenuButton class="-m-2 p-2 rounded-full flex items-center ">
                          <span class="sr-only">Open notification options</span>
                          <DotsVerticalIcon
                            class="h-5 w-5 "
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
                            <MenuItem v-slot="{ active }">
                            <button
                              type="button"
                              @click="remove(readNotification.id)"
                              :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'flex px-4 py-2 text-sm w-full']"
                            >
                              <XCircleIcon
                                class="mr-3 h-5 w-5 text-gray-400"
                                aria-hidden="true"
                              />
                              <span>Remove notification</span>
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
        <div
          v-if="!notifications.length"
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
import { ref } from "vue";

import NotificationFeedItem from "@/Components/Notifications/NotificationFeedItem.vue";

import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import {
  CodeIcon,
  DotsVerticalIcon,
  FlagIcon,
  CheckIcon,
} from "@heroicons/vue/solid";
import { CheckCircleIcon, XCircleIcon } from "@heroicons/vue/outline";

export default {
  components: {
    NotificationFeedItem,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    CodeIcon,
    DotsVerticalIcon,
    FlagIcon,
    CheckIcon,
    CheckCircleIcon,
    XCircleIcon,
  },

  props: {
    notifications: {
      type: Array,
      required: true,
    },
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
    const unreadNotifications = ref(props.unreadNotifications);
    const readNotifications = ref(props.readNotifications);

    const markAllAsRead = () => {
      window.axios.post(route("notifications.mark-all-as-read")).then(() => {});
    };

    const markAsRead = (uuid) => {
      window.axios.patch(route("notifications.update", uuid)).then(() => {});
    };

    const remove = (uuid) => {
      window.axios.delete(route("notifications.destroy", uuid)).then(() => {
        // const unreadIndex = unreadNotifications.value.findIndex(
        //   (n) => n.id == uuid
        // );
        // console.log(unreadIndex);
        // if (unreadIndex) {
        //   unreadNotifications.value.splice(unreadIndex, 1);
        //   return;
        // }
        // const readIndex = readNotifications.value.findIndex(
        //   (n) => n.id == uuid
        // );
        // console.log(readIndex);
        // if (readIndex) {
        //   readNotifications.value.splice(readIndex, 1);
        //   return;
        // }
        // console.log("i fucked up");
      });
    };

    return {
      unreadNotifications,
      readNotifications,
      markAllAsRead,
      markAsRead,
      remove,
    };
  },
};
</script>
