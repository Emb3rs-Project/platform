<template>
    <div>
        <div class="flex justify-between">
            <label
                :for="value"
                class="block text-sm font-medium text-gray-700"
            >
                {{ label }}
            </label>
            <span
                v-show="required"
                class="text-sm text-gray-500"
                id="input-required"
            >
        Required
      </span>
        </div>
        <div class="mt-1 relative rounded-md shadow-sm">
            <Datepicker class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                        :ref="input"
                        v-model="value"
                        format="dd/MM/yyyy"
                        model-type="yyyy-MM-dd"
                        :enable-time-picker="false"
                        auto-apply/>
        </div>
        <p class="mt-2 text-sm text-gray-500 text-justify"></p>
    </div>
</template>

<script>
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {computed, ref} from "vue";

export default {
    name: "DateInput",
    components : {
        Datepicker,
    },
    props: {
        modelValue: {
            type: [String, Number],
            default: "",
        },
        label: {
            type: String,
            default: "",
        },
    },
    emits: ["update:modelValue"],
    setup(props, ctx) {
        const input = ref(null);

        const value = computed({
            get: () => props.modelValue,
            set: (value) => ctx.emit("update:modelValue", value),
        });

        const focus = () => {
            if (input.value) input.value.focus();
        };

        return {
            value,
            input,
            focus,
        };
    },

}
</script>

<style scoped>

</style>
