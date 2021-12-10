<template>
    <reactive-index :columns="columns"
    resource_name="Medical Staff"
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
    name: "MedicalStaffIndex",

    components: {
        "ReactiveIndex" : ReactiveIndex,
    },

    created(){
    },

    mounted() {
    },

    data() {
        var role_options = [];
        if (typeof this.medical_staff_roles !== 'undefined') {
            this.medical_staff_roles.forEach(function(role){
                role_options.push({
                    label: role.label,
                    value: role.id
                })
            })
        }

        return {
            columns: [
                {
                    label: "First Name",
                    keyword: "first_name",
                },
                {
                    label: "Last Name",
                    keyword: "last_name",
                },
                {
                    label: "Role",
                    keyword: "role",
                },
                {
                    label: "Health Facility",
                    keyword: "health_facility"
                }
            ],

            inputs : [
                {
                    label : "First Name",
                    keyword: "first_name",
                    type: "text",
                    default: "",
                },
                {
                    label : "Last Name",
                    keyword: "last_name",
                    type: "text",
                    default: "",
                },
                {
                    label: "Role",
                    keyword: "medical_staff_role_id",
                    type: "select",
                    options: role_options,
                }
            ],

            details: [
                {
                    label: "First Name",
                    keyword: "first_name",
                    type: "text",
                },
                {
                    label: "Last Name",  
                    keyword: "last_name",
                    type: "text",
                },
                {
                    label: "Role",
                    keyword: "role",
                    type: "text",
                },
                {
                    label: "Health Facility",
                    keyword: "health_facility",
                    type: "text",
                }
            ]
        }
    },

    props : {
        medical_staff_roles: Array,
    },

    methods : {

        createSuccess : function(medical_ind){
            this.$toasted.global.success_notification("Medical Staff Successfully created")
        },

        createFailure : function(error){
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Medical Staff Creation Failed: " + errorMsg)
            if (errors) {
                for (const [key,value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },

        editSuccess : function(device){
            this.$toasted.global.success_notification("Medical Staff Succesfully Edited")
        },

        editFailure : function(error){
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Medical Staff Edition Failed: " + errorMsg)
            if (errors) {
                for (const [key,value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },

        destroySuccess : function(response){
            this.$toasted.global.success_notification("Medical Staff Successfully Deleted")
        },

        destroyFailure : function(error){
            this.$toasted.global.error_notification("An error occurred:" + error)
        },
    },

}
</script>

<style>

</style>