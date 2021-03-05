<template>
    <div class="flex flex-1 flex-col md:flex-row">
        <div class="w-full md:w-6/12">
            <h3 class="font-bold">
                {{ heading }}
            </h3>
            <p class="text-sm text-gray-500 mt-3">
                {{ desc }}
            </p>
        </div>
        <div class="w-full md:w-6/12 pl-0 md:pl-4">
            <label
                for="name"
                class="block text-sm text-indigo-400 mb-2"
            >
                <slot></slot>
            </label>
            <div
                class="flex items-start"
                v-for="option in options"
                :key="option"
            >
                <div class="flex items-center h-5">
                    <input
                        name="option"
                        type="radio"
                        class="outline-none focus:ring h-4 w-4 text-indigo-400 border-gray-300"
                    >
                </div>
                <div class="ml-3 text-sm">
                    <label
                        for="option"
                        class="font-medium text-gray-700"
                    >
                        {{option}}
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        heading: String,
        desc: String,
        options: Array,
        placeholder: String,
        // TODO: We need to get back the selected option
        modelValue: String
    },
    emits: ["update:modelValue"],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
};
</script>

<style>
</style>
