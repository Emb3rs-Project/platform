<template>
    <div class="sm:col-span-3">
        <div>
            <div class="flex justify-between">
                <label for="sim_metadata" class="block text-sm font-medium text-gray-700">
                    {{label}}
                </label>
                <span class="text-sm text-gray-500" id="input-required" v-if="required">
                                Required
                </span>
            </div>
            <div class="mt-1 relative rounded-md shadow-sm">
               <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Field",
    props: {
        label: String,
        required: Boolean
    }
}
</script>

<style scoped>

</style>
