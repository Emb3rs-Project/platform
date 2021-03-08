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
                class="flex items-center"
                v-for="option in options"
                :key="option"
            >
                <input
                    :name="option"
                    type="radio"
                    class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                    :value="option"
                    v-model="value"
                >
                <label
                    :for="option"
                    class="ml-3 block text-sm font-medium text-gray-700"
                >
                    {{option}}
                </label>
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
        modelValue: String
    },
    emits: ["update:modelValue"],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
    computed: {
        value: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit('update:modelValue', value)
            }
        }
    }
};
</script>

<style>
</style>
