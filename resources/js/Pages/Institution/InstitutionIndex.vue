<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Institution
            </h2>
        </template>
        <div class="py-12 h-full">
            <div class="h-full max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="h-full grid grid-cols-2 grid-rows-2 gap-x-6 grid-flow-row">
                    <div class="h-full row-span-2 bg-gray-300 shadow-lg">
                        Map
                    </div>
                    <div>
                        <div class="flex flex-col bg-gray-300 h-4/5 rounded mb-3">
                            <h2 class="p-5 font-semibold text-2xl text-gray-800 leading-tight">
                                Users
                            </h2>
                            <div class="flex flex-col justify-center">
                                <div
                                    class="w-full bg-white rounded mb-3 text-xl"
                                    v-for="user in users"
                                    :key="user.name"
                                >
                                    {{user.id}} - {{ user.name }} - {{ user.username}}
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-300 h-4/5 rounded mb-3">
                            <h2 class="p-5 font-semibold text-md text-gray-800 leading-tight">
                                Teams
                            </h2>
                            <div class="ml-5">
                                <ul>
                                    <li
                                        v-for="team in teams"
                                        :key="team.name"
                                    >
                                        {{ team.name }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mx-auto"></div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    components: {
        AppLayout,
    },
    data() {
        return {
            users: [{
                id: '1',
                name: 'George Mantellos',
                username: 'geocfu'
            }, {
                id: '2',
                name: 'David Fernandes',
                username: 'davidfs'
            },
            {
                id: '3',
                name: 'Luis Miguel Campos',
                username: 'luismc'
            },
            {
                id: '4',
                name: 'Carlos Nuno Marques',
                username: 'carlosnm'
            }],
            teams: [{
                name: 'George\'s team'
            }]
        }
    }

};
</script>

<style scoped>
</style>
