<template>
    <div class="h-screen flex overflow-hidden bg-white">
        <TransitionRoot
            as="template"
            :show="sidebarOpen"
        >
            <Dialog
                as="div"
                static
                class="fixed inset-0 flex z-40 lg:hidden"
                @close="sidebarOpen = false"
                :open="sidebarOpen"
            >
                <TransitionChild
                    as="template"
                    enter="transition-opacity ease-linear duration-300"
                    enter-from="opacity-0"
                    enter-to="opacity-100"
                    leave="transition-opacity ease-linear duration-300"
                    leave-from="opacity-100"
                    leave-to="opacity-0"
                >
                    <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75"/>
                </TransitionChild>
                <TransitionChild
                    as="template"
                    enter="transition ease-in-out duration-300 transform"
                    enter-from="-translate-x-full"
                    enter-to="translate-x-0"
                    leave="transition ease-in-out duration-300 transform"
                    leave-from="translate-x-0"
                    leave-to="-translate-x-full"
                >
                    <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 pb-4 bg-white">
                        <TransitionChild
                            as="template"
                            enter="ease-in-out duration-300"
                            enter-from="opacity-0"
                            enter-to="opacity-100"
                            leave="ease-in-out duration-300"
                            leave-from="opacity-100"
                            leave-to="opacity-0"
                        >
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button
                                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                    @click="sidebarOpen = false"
                                >
                                    <span class="sr-only">Close sidebar</span>
                                    <XIcon
                                        class="h-6 w-6 text-white"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </TransitionChild>
                        <div class="shrink-0 flex items-center px-4">
                            <application-logo class="h-auto w-auto"/>
                        </div>
                        <div class="mt-5 flex-1 h-0 overflow-y-auto">
                            <nav class="px-2">
                                <div class="space-y-1">
                                    <Link
                                        v-for="item in navigation"
                                        :key="item.name"
                                        :href="route(item.href.index.location)"
                                        :class="[route().current() === item.href.index.location || route().current() === item.href.show?.location || route().current() === item.href.create?.location ? 'bg-gray-200 text-yellow-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']"
                                        :aria-current="item.current ? 'page' : undefined"
                                    >
                                        <component
                                            :is="item.icon"
                                            :class="[route().current() === item.href.index.location || route().current() === item.href.show?.location || route().current() === item.href.create?.location ? 'text-yellow-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 shrink-0 h-6 w-6']"
                                            aria-hidden="true"
                                        />
                                        {{ item.name }}
                                    </Link>
                                </div>
                                <div class="mt-8">
                                    <div class="relative">
                                        <div class="relative flex items-center justify-between">
                                            <h3
                                                class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                                                id="institutions-headline"
                                            >
                                                Institutions
                                            </h3>

                                            <Menu
                                                as="div"
                                                class="relative inline-block text-left"
                                            >
                                                <div>
                                                    <MenuButton
                                                        class="mr-1.5 rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
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
                                                    <MenuItems
                                                        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                        <div class="py-1">
                                                            <MenuItem v-slot="{ active }">
                                                                <Link
                                                                    :href="route('teams.create')"
                                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                                >
                                                                    Create Institution
                                                                </Link>
                                                            </MenuItem>
                                                        </div>
                                                    </MenuItems>
                                                </transition>
                                            </Menu>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-1 space-y-1"
                                        role="group"
                                        aria-labelledby="institutions-headline"
                                    >
                                        <div
                                            v-for="institution in $page.props.user.all_teams"
                                            :key="institution.id"
                                        >
                                            <form @submit.prevent="switchToTeam(institution)">
                                                <div
                                                    class="group flex justify-between items-center w-full rounded-md"
                                                    :class="
                        institution.id === $page.props.user.current_team_id
                          ? 'text-yellow-600 bg-gray-200'
                          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'
                      "
                                                >
                                                    <button
                                                        type="submit"
                                                        class="flex w-full px-3 py-2.5 text-sm leading-4 font-medium focus:outline-none"
                                                    >
                                                        {{ institution.name }}
                                                    </button>
                                                    <Link
                                                        :href="route('teams.show', institution)"
                                                        class="text-sm mr-1.5 hidden rounded-full group-hover:block"
                                                    >
                                                        <CogIcon
                                                            class="h-5 w-5 rounded-full text-gray-400 hover:text-gray-600"
                                                            aria-hidden="true"
                                                        />
                                                    </Link>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                </TransitionChild>
                <div
                    class="shrink-0 w-14"
                    aria-hidden="true"
                >
                    <!-- Dummy element to force sidebar to shrink to fit close icon -->
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:flex lg:shrink-0">
            <div class="flex flex-col w-64 border-r border-gray-200 pt-5 pb-4 bg-gray-100">
                <div class="flex items-center shrink-0 px-6">
                    <application-logo class="h-auto w-auto"/>
                </div>
                <!-- Sidebar component, swap this element with another sidebar if you like -->
                <div class="h-0 flex-1 flex flex-col overflow-y-auto">
                    <!-- User account dropdown -->
                    <Menu
                        as="div"
                        class="px-3 mt-6 relative inline-block text-left"
                    >
                        <div>
                            <MenuButton
                                class="group w-full bg-gray-100 rounded-md px-3.5 py-2 text-sm text-left font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-purple-500">
                <span class="flex w-full justify-between items-center">
                  <span class="flex min-w-0 items-center justify-between space-x-3">
                    <span class="inline-block relative">
                      <img
                          class="h-10 w-10 rounded-full shrink-0"
                          :src="$page.props.user.profile_photo_url"
                          alt=""
                      />
                      <span
                          v-show="newNotification"
                          class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-red-400"
                      ></span>
                    </span>
                    <span class="flex-1 flex flex-col min-w-0">
                      <span class="text-gray-900 text-sm font-medium truncate">
                        {{ $page.props.user.name }}
                      </span>
                      <span class="text-gray-500 text-sm truncate">
                        {{ $page.props.user.email }}
                      </span>
                    </span>
                  </span>
                  <SelectorIcon
                      class="shrink-0 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                      aria-hidden="true"
                  />
                </span>
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
                            <MenuItems
                                class="z-10 mx-3 origin-top absolute right-0 left-0 mt-1 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none">
                                <div class="py-1">
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('profile.show')"
                                            :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']"
                                        >
                                            View profile
                                        </Link>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('notifications.index')"
                                            :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']"
                                        >
                                            Notifications
                                            <span
                                                v-show="newNotification"
                                                class="truncate inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                                            >
                    {{ unreadNotificationsCount }}
                  </span>
                                        </Link>
                                    </MenuItem>
                                </div>
                                <div class="py-1">
                                    <MenuItem v-slot="{ active }">
                                        <form @submit.prevent="logout">
                                            <button
                                                type="submit"
                                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm']"
                                            >
                                                Logout
                                            </button>
                                        </form>
                                    </MenuItem>
                                </div>
                            </MenuItems>
                        </transition>
                    </Menu>
                    <!-- Sidebar Search -->
                    <QuickSearch/>
                    <!-- Navigation -->
                    <nav class="px-3 mt-6">
                        <div class="space-y-1">
                            <Link
                                v-for="item in navigation"
                                :key="item.name"
                                :href="route(item.href.index.location)"
                                :class="[route().current() === item.href.index.location || route().current() === item.href.show?.location || route().current() === item.href.create?.location ? 'bg-gray-200 text-yellow-600' : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']"
                                :aria-current="item.current ? 'page' : undefined"
                            >
                                <component
                                    :is="item.icon"
                                    :class="[route().current() === item.href.index.location || route().current() === item.href.show?.location || route().current() === item.href.create?.location ? 'text-yellow-500' : 'text-gray-400 group-hover:text-gray-500', 'mr-3 shrink-0 h-6 w-6']"
                                    aria-hidden="true"
                                />
                                {{ item.name }}
                            </Link>
                        </div>
                        <div class="mt-8">
                            <!-- Secondary navigation -->
                            <div class="relative">
                                <div class="relative flex items-center justify-between">
                                    <h3
                                        class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                                        id="institutions-headline"
                                    >
                                        Institutions
                                    </h3>

                                    <Menu
                                        as="div"
                                        class="relative inline-block text-left"
                                    >
                                        <div>
                                            <MenuButton
                                                class="mr-1.5 rounded-full flex items-center text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
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
                                            <MenuItems
                                                class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                <div class="py-1">
                                                    <MenuItem v-slot="{ active }">
                                                        <Link
                                                            :href="route('teams.create')"
                                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                                                        >
                                                            Create Institution
                                                        </Link>
                                                    </MenuItem>
                                                </div>
                                            </MenuItems>
                                        </transition>
                                    </Menu>
                                </div>
                            </div>
                            <div
                                class="mt-1 space-y-1"
                                role="group"
                                aria-labelledby="institutions-headline"
                            >
                                <div
                                    v-for="institution in $page.props.user.all_teams"
                                    :key="institution.id"
                                >
                                    <form @submit.prevent="switchToTeam(institution)">
                                        <div
                                            class="group flex justify-between items-center w-full rounded-md"
                                            :class="
                        institution.id === $page.props.user.current_team_id
                          ? 'text-yellow-600 bg-gray-200'
                          : 'text-gray-700 hover:text-gray-900 hover:bg-gray-50'
                      "
                                        >
                                            <button
                                                type="submit"
                                                class="flex w-full px-3 py-2.5 text-sm leading-4 font-medium focus:outline-none"
                                            >
                                                {{ institution.name }}
                                            </button>
                                            <Link
                                                :href="route('teams.show', institution)"
                                                class="text-sm mr-1.5 hidden rounded-full group-hover:block"
                                            >
                                                <CogIcon
                                                    class="h-5 w-5 rounded-full text-gray-400 hover:text-gray-600"
                                                    aria-hidden="true"
                                                />
                                            </Link>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Main column -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Search header -->
            <div class="relative z-10 shrink-0 flex h-16 bg-white border-b border-gray-200 lg:hidden">
                <button
                    class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500 lg:hidden"
                    @click="sidebarOpen = true"
                >
                    <span class="sr-only">Open sidebar</span>
                    <MenuAlt1Icon
                        class="h-6 w-6"
                        aria-hidden="true"
                    />
                </button>
                <div class="flex-1 flex justify-between px-4 sm:px-6 lg:px-8">
                    <div class="flex-1 flex w-full">
                        <form
                            class="w-full flex md:ml-0"
                            action="#"
                            method="GET"
                        >
                            <label
                                for="search_field"
                                class="sr-only"
                            >
                                Search
                            </label>
                            <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                                <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                                    <SearchIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </div>
                                <input
                                    id="search_field"
                                    name="search_field"
                                    class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-0 focus:border-transparent focus:placeholder-gray-400 sm:text-sm"
                                    placeholder="Search"
                                    type="search"
                                />
                            </div>
                        </form>
                    </div>
                    <div class="flex items-center">
                        <!-- Profile dropdown -->
                        <Menu
                            as="div"
                            class="ml-3 relative"
                        >
                            <div>
                                <MenuButton
                                    class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    <span class="sr-only">Open user menu</span>
                                    <span class="inline-block relative">
                    <img
                        class="h-10 w-10 rounded-full shrink-0"
                        :src="$page.props.user.profile_photo_url"
                        alt=""
                    />
                    <span
                        v-show="newNotification"
                        class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-red-400"
                    ></span>
                  </span>
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
                                <MenuItems
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200 focus:outline-none">
                                    <div class="py-1">
                                        <MenuItem v-slot="{ active }">
                                            <Link
                                                :href="route('profile.show')"
                                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']"
                                            >
                                                View profile
                                            </Link>
                                        </MenuItem>
                                        <MenuItem v-slot="{ active }">
                                            <Link
                                                :href="route('notifications.index')"
                                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']"
                                            >
                                                Notifications
                                                <span
                                                    v-show="newNotification"
                                                    class="truncate inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                                                >
                      {{ unreadNotificationsCount }}
                    </span>
                                            </Link>
                                        </MenuItem>
                                    </div>
                                    <div class="py-1">
                                        <MenuItem v-slot="{ active }">
                                            <form @submit.prevent="logout">
                                                <button
                                                    type="submit"
                                                    :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block w-full text-left px-4 py-2 text-sm']"
                                                >
                                                    Logout
                                                </button>
                                            </form>
                                        </MenuItem>
                                    </div>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>
            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none bg-gray-50">
                <slot></slot>

                <notifications
                    group="notifications"
                    position="top right"
                    ignoreDuplicates
                >
                    <template #body="{item, close}">
                        <SimpleNotification
                            :type="item.data.type"
                            :title="item.title"
                            :message="item.text"
                            :close="close"
                        />
                    </template>
                </notifications>

                <notifications
                    group="snackbar"
                    position="bottom left"
                    :max="4"
                    ignoreDuplicates
                >
                    <template #body="{item, close}">
                        <SnackBarNotification
                            :type="item.data.type"
                            :title="item.title"
                            :message="item.text"
                            :close="close"
                        />
                    </template>
                </notifications>

            </main>
        </div>
    </div>
</template>

<script>
import {ref, onBeforeUnmount} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {Link} from "@inertiajs/inertia-vue3";
import {useStore} from "vuex";

import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import QuickSearch from "@/Components/Search/QuickSearch.vue";
import SimpleNotification from "@/Components/Notifications/SimpleNotification.vue";
import SnackBarNotification from "@/Components/Notifications/SnackBarNotification.vue";

import {
    Dialog,
    DialogOverlay,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    NewspaperIcon,
    HomeIcon,
    MenuAlt1Icon,
    ViewListIcon,
    XIcon,
    TemplateIcon,
    UserGroupIcon,
    SupportIcon,
} from "@heroicons/vue/outline";
import {
    ChevronRightIcon,
    DotsVerticalIcon,
    DuplicateIcon,
    PencilAltIcon,
    SearchIcon,
    SelectorIcon,
    CogIcon,
} from "@heroicons/vue/solid";

window.Pusher = require('pusher-js')

const navigation = [
    {
        name: "Dashboard",
        icon: HomeIcon,
        href: {
            index: {
                location: "dashboard.index",
            },
        },
    },
    {
        name: "Objects",
        href: {
            index: {
                location: "objects.index",
            },
        },
        icon: TemplateIcon,
    },
    {
        name: "Projects",
        href: {
            index: {
                location: "projects.index",
            },
            show: {
                location: "projects.show",
            },
            create: {
                location: "projects.create",
            },
        },
        icon: ViewListIcon,
    }, {
        name: "My Simulations",
        href: {
            index: {
                location: "my-simulations.index",
            },
        },
        icon: ViewListIcon,
    },
    {
        name: "Challenge",
        href: {
            index: {
                location: "challenges.index",
            },
        },
        icon: UserGroupIcon,
    },
    {
        name: "News",
        href: {
            index: {
                location: "news.index",
            },
            show: {
                location: "news.show",
            },
        },
        icon: NewspaperIcon,
    },
    {
        name: "Help",
        href: {
            index: {
                location: "help.index",
            },
        },
        icon: SupportIcon,
    },
];

export default {
    components: {
        Link,
        Dialog,
        DialogOverlay,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        TransitionChild,
        TransitionRoot,
        ChevronRightIcon,
        DotsVerticalIcon,
        DuplicateIcon,
        MenuAlt1Icon,
        PencilAltIcon,
        SearchIcon,
        SelectorIcon,
        XIcon,
        ApplicationLogo,
        CogIcon,
        QuickSearch,
        SimpleNotification,
        SnackBarNotification,
    },

     setup (props) {
        const store = useStore();

        const sidebarOpen = ref(false);
        const newNotification = ref(null);
        const unreadNotificationsCount = ref(null);

        // Check for new notifications instantly (1-time check)
        store.dispatch("notifications/checkForNewNotifications");

        // start watching for new notifications (polling)
        store.dispatch("notifications/watchForNewNotifications");

        const stopWatcher = store.watch(
            (state) => state.notifications.unreadNotificationsCount,
            (count) => {
                if (count) {
                    newNotification.value = true;
                    unreadNotificationsCount.value = count;
                } else {
                    newNotification.value = false;
                    unreadNotificationsCount.value = null;
                }
            },
            {immediate: true}
        );

        // stop watching for notifications when the component has been destroyed
        onBeforeUnmount(() => {
            store.dispatch("notifications/stopWatchingForNewNotifications");
            stopWatcher();
        });

        function logout () {
            Inertia.post(route("logout"));
        }

        function switchToTeam (team) {
            Inertia.put(
                route("current-team.update"),
                {
                    team_id: team.id,
                },
                {
                    preserveState: false,
                }
            );
        }

        return {
            navigation,
            sidebarOpen,
            newNotification,
            unreadNotificationsCount,
            logout,
            switchToTeam,
        };
    },
};
</script>
