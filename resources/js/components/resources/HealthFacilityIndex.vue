<template>
    <reactive-index :columns="columns"
    resource_name="Health Facility"
    :details="details"
    :inputsCreate="inputs"
    :inputsEdit="inputs"
    v-on="$listeners"
    v-bind="$attrs"
    @destroy-success="destroySuccess"
    @destroy-failure="destroyFailure"
    @create-success="createSuccess"
    @create-failure="createFailure"
    @edit-success="editSuccess"
    @edit-failure="editFailure"
    ></reactive-index>
</template>

<script>
import ReactiveIndex from "./../basic/ReactiveIndex.vue"

export default {
    name: "HealthFacilityIndex",

    components: {
        "ReactiveIndex" : ReactiveIndex,
    },

    created(){
    },

    mounted() {
    },


    data() {
        return {
            columns: [
                {
                    label: "Id",
                    keyword: "group_id"
                },
                {
                    label: "Name",
                    keyword: "name",
                },
                {
                    label: "Country",
                    keyword: "country",
                },
                {
                    label: "Area",
                    keyword: "area",
                },
            ],
            inputs : [
                {
                    label : "Name",
                    keyword: "name",
                    type: "text",
                    default: "",
                },
                {
                    label : "Country",
                    keyword: "country",
                    type: "text",
                    default: "",
                },
                {
                    label : "Area",
                    keyword: "area",
                    type: "text",
                    default: "",
                },
                {
                    label : "Longitude",
                    keyword: "long",
                    type: "text",
                    default: "0.00",
                },
                {
                    label : "Latitude",
                    keyword: "lat",
                    type: "text",
                    default: "0.00"
                },
                {
                    label : "Architecture",
                    keyword: "hf_mode",
                    type: "select",
                    default: "",
                    options: [
                        {
                            label: "Client Server",
                            value: "client_server",
                        },
                        {
                            label: "Standalone",
                            value: "standalone"
                        }
                    ]
                },
                {
                    label : "medAL-hub IP (only for Client Server)",
                    keyword: "local_data_ip",
                    type: "text",
                    default: "",
                },
                {
                    label : "Pin Code",
                    keyword: "pin_code",
                    type: "text",
                    default: "",
                },
            ],
            details: [
                {
                    label : "Id",
                    keyword: "group_id",
                    type: "text",
                },
                {
                    label : "Name",
                    keyword: "name",
                    type: "text",
                },
                {
                    label : "Country",
                    keyword: "country",
                    type: "text",
                },
                {
                    label : "Area",
                    keyword: "area",
                    type: "text",
                },
                {
                    label : "Longitude",
                    keyword: "long",
                    type: "code",
                },
                {
                    label : "Latitude",
                    keyword: "lat",
                    type: "code",
                },
                {
                    label : "Architecture",
                    keyword: "hf_mode",
                    type: "text",
                },
                {
                    label : "medAL-hub IP (only for Client Server)",
                    keyword: "local_data_ip",
                    type: "code",
                },
                {
                    label : "Pin Code",
                    keyword: "pin_code",
                    type: "code",
                },
            ],
        }
    },

    methods : {

        createSuccess : function(device){
            this.$toasted.global.success_notification("Health Facility Successfully created")
        },

        createFailure : function(error){
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Health Facility Creation Failed: " + errorMsg)
            if (errors) {
                for (const [key,value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },

        editSuccess : function(device){
            this.$toasted.global.success_notification("Health Facility Succesfully Edited")
        },

        editFailure : function(error){
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Health Facility Edition Failed: " + errorMsg)
            if (errors) {
                for (const [key,value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },

        destroySuccess : function(response){
            this.$toasted.global.success_notification("Health Facility Successfully Deleted")
        },

        destroyFailure : function(error){
            this.$toasted.global.error_notification("An error occurred:" + error)
        }
    },

}
</script>

<style>

</style>
