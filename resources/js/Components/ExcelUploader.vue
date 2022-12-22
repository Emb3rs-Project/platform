<template>
    <div>
        <input type="file" @change="onChange"/>
        <xlsx-read :file="file">
            <xlsx-json @parsed="setCollection">
            </xlsx-json>
        </xlsx-read>

        <p v-if="fieldName"> Download an example file: <a :href="`/sample-import?type=${fieldName}`" class="font-bold text-primary">click here</a></p>
    </div>
</template>


<script>
import {XlsxRead, XlsxJson} from "vue3-xlsx";
import {notify} from "@kyvg/vue3-notification";

export default {
    components: {
        XlsxRead,
        XlsxJson
    },
    props: ['fieldName'],
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
            if (collection.length > 0 && collection[0].hasOwnProperty('capacity')) {
                let newCollection = collection.map(({capacity}) => capacity)
                this.$emit('input', JSON.stringify(newCollection))
                return
            }

            notify({
                group: "notifications",
                title: "Error",
                text: "Invalid XLSX, please check the columns",
                data: {
                    type: "danger",
                },
            });
            this.file = null
            this.$emit('input', '')
        }
    }
};
</script>
