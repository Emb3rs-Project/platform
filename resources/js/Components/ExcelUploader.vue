<template>
    <div>
        <input type="file" @change="onChange"/>
        <xlsx-read :file="file">
            <xlsx-json @parsed="setCollection">
            </xlsx-json>
        </xlsx-read>
    </div>
</template>


<script>
import {XlsxRead, XlsxJson} from "vue3-xlsx";

export default {
    components: {
        XlsxRead,
        XlsxJson
    },
    data () {
        return {
            file: null,
            data: []
        };
    },
    methods: {
        onChange (event) {
            this.file = event.target.files ? event.target.files[0] : null;
        },
        setCollection (collection) {
            this.data = collection
            this.$emit('input', collection)
        }
    }
};
</script>
