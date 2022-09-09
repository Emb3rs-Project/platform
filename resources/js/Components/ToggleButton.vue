<template>
   <div>
        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
            <input type="checkbox" v-model="value" name="toggle" id="toggle"
                   class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"/>
            <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
        </div>
        <label for="toggle" class="text-xs text-gray-700">{{label}}</label>
   </div>
</template>

<script>
export default {
    name: "ToggleButton",
    props: {
        label: String,
        modelValue: Boolean,

    },
    emits: ["update:modelValue"],
    computed: {
        value: {
            get() {
                return this.modelValue;
            },
            set(value) {
                this.$emit("update:modelValue", value);
            },
        },
    },
}
</script>

<style scoped>
/* CHECKBOX TOGGLE SWITCH */
/* @apply rules for documentation, these do not work as inline style */
.toggle-checkbox:checked {
    @apply: right-0 border-green-400;
    right: 0;
    border-color: #68D391;
}
.toggle-checkbox:checked + .toggle-label {
    @apply: bg-green-400;
    background-color: #68D391;
}
</style>
