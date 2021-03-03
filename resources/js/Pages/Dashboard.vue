<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="py-12 h-full">
            <div class="h-full max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="h-full grid grid-cols-2 grid-rows-3 gap-x-6 grid-flow-row">
                    <div class="h-full row-span-3 bg-gray-300 shadow-lg">
                        Map
                    </div>
                    <div>
                        <div class="bg-gray-300 h-4/5 rounded mb-3">Last News</div>
                        <div class="bg-gray-300 h-4/5 rounded mb-3">Last Notifications</div>
                        <div class="bg-gray-300 h-4/5 rounded">Lorem</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mx-auto"></div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";

export default {
    components: {
        AppLayout,
        Welcome,
    },
};
</script>

<style>
</style>
