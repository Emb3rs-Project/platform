<template>
    <inertia-link
        as="button"
        :href="route(path, parameter)"
        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 uppercase bg-indigo-100 hover:bg-indigo-200 focus:outline-none"
    >
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: {
            path: {
                type: String,
                require: true
            },
            parameter: {
                type: String,
                require: false
            }
        }
    }
</script>

<style scoped>
</style>
