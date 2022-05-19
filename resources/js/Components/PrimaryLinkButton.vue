<template>
    <Link
        as="button"
        :href="route(path, parameter)"
        :class="[
            styleClasses,
            'inline-flex justify-center items-center px-3 py-2 leading-4 border border-transparent text-sm font-medium uppercase tracking-widest rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
        ]"
        :disabled="disabled"
    >
        <slot></slot>
    </Link>
</template>

<script>
import { computed } from "vue";

import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        Link,
    },

    props: {
        variant: {
            type: String,
            default: "default",
        },
        path: {
            type: String,
            default: "'#'",
        },
        parameter: {
            type: String | Number,
            required: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },

    setup(props) {
        const styleClasses = computed(
            () =>
                ({
                    default:
                        "text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 disabled:bg-blue-600",
                }[props.variant])
        );

        return {
            styleClasses,
        };
    },
};
</script>
