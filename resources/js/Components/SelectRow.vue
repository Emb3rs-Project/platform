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
                for="shape"
                class="block text-sm text-indigo-400 mb-2"
            >
                <slot></slot>
            </label>
            <select
                name="shape"
                class="border border-gray-300 outline-none focus:ring focus:ring-indigo-200 border-opacity-25 pl-3 text-sm w-full leading-6 rounded"
                v-model="value"
            >
                <option
                    v-for="option in options"
                    :key="option"
                >
                    {{ option }}
                </option>
            </select>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        heading: String,
        desc: String,
        options: Array,
        modelValue: String
    },
    emits: ['update:modelValue'],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
    computed: {
        value: {
            get() {
                return this.modelValue
            },
            set(value) {
                this.$emit('update:modelValue', value);
            }
        }
    }
};
</script>
