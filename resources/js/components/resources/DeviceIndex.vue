<template>
    <reactive-index :columns="columns"
    resource_name="Device"
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
    name: "DevicesIndex",

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
                    label: "Name",
                    keyword: "name",
                },
                {
                    label: "Type",
                    keyword: "type_label",
                },
                {
                    label: "Auth ID",
                    keyword: "oauth_client_id",
                },
                {
                    label: "Health Facility",
                    keyword: "health_facility_name",
                }
            ],
            inputs : [
                {
                    label : "Name",
                    keyword: "name",
                    type: "text",
                    default: "",
                },
                {
                    label: "Device Type",
                    keyword: "type",
                    type: "select",
                    options: [
                        {
                            label: "medAL Hub",
                            value: "hub",
                        },
                        {
                            label: "medAL Reader",
                            value: "reader",
                        }
                    ],
                }
            ],
            details: [
                {
                    label: "Name",
                    keyword: "name",
                    type: "text",
                },
                {
                    label: "Type",
                    keyword: "type_label",
                    type: "text",
                },
                {
                    label: "Model",
                    keyword: "model",
                    type: "text",
                },
                {
                    label: "Brand",
                    keyword: "brand",
                    type: "text",
                },
                {
                    label: "Operating System",
                    keyword: "os",
                    type: "text",
                },
                {
                    label: "Operating System Version",
                    keyword: "os_version",
                    type: "text",
                },
                {
                    label: "MAC Address",
                    keyword: "mac_address",
                    type: "code",
                },
                {
                    label: "Health Facility",
                    keyword: "health_facility",
                    type: "text",
                },
                {
                    label: "Authentication ID",
                    keyword: "oauth_client_id",
                    type: "text",
                },
                {
                    label: "Authentication Secret",
                    keyword: "oauth_client_secret",
                    type: "code",
                },

            ],
        }
    },

    methods : {

        createSuccess : function(device){
            this.$toasted.global.success_notification("Device Successfully created")
        },

        createFailure : function(error){
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Device Creation Failed: " + errorMsg)
            if (errors) {
                for (const [key,value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },

        editSuccess : function(device){
            this.$toasted.global.success_notification("Device Succesfully Edited")
        },

        editFailure : function(error){
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Device Edition Failed: " + errorMsg)
            if (errors) {
                for (const [key,value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },

        destroySuccess : function(response){
            this.$toasted.global.success_notification("Device Successfully Deleted")
        },

        destroyFailure : function(error){
            this.$toasted.global.error_notification("An error occurred:" + error)
        },
    },

}
</script>

<style>

</style>