<template>
<div class= "card">
    <alert-message alert_color="green" ref="successAlert">Edited Successfully</alert-message>
    <alert-message alert_color="red" ref="failureAlert">An error occured</alert-message>
    <h3>{{title}}</h3>
    <dynamic-form :inputs="inputs" :data="data"></dynamic-form>
    <update-button :data="data" :url="edit_url" :title="buttonTitle" @edit-success="feedbackSuccess" @edit-error="feedbackFailure"></update-button>
</div>

</template>

<script>
import DynamicForm from "./DynamicForm.vue"
import UpdateButton from "./UpdateButton.vue"
import AlertMessage from "./AlertMessage.vue"


export default {
    
    components : {
        "DynamicForm": DynamicForm,
        "UpdateButton": UpdateButton,
        "AlertMessage" : AlertMessage,
    },

    name: "EditForm",

    data() {
        return {
            form : {
            },
            successEdit : false,
            failureEdit: false,
            title: "Edit Object"
        }
    },

    mounted() {
        this.title = "Create " + this.objectName
    },

    props : {
        edit_url: {
            type: String,
            default: "/post-data-test",
        },
        objectName: {
            type: String,
            default: "Object"
        },
        buttonTitle: {
            type: String,
            default: "Submit",
        },
        data: {
            type: Object,
            default() {
                return {
                    name: "Test Name",
                    is_checked: false,
                    option: "",
                } 
            },
        },
        inputs : {
            type: Array,
            default() {
                return [
                    {
                        keyword: "name",
                        title: "Name",
                        type: "text",
                    },
                    {
                        keyword: "is_checked",
                        title: "Checked",
                        type: "checkbox",
                    },
                    {
                        keyword: "option",
                        title: "options",
                        type: "select",
                        options: [
                            {
                                label: "option 1",
                                value: "option_1"
                            },
                            {
                                label:"option 2",
                                value:"option_2"
                            }
                        ]
                    },
                ]
            }
        },
    },

    methods : {

        feedbackSuccess: function(createdObject){
            this.$refs.successAlert.fire()
            console.log(createdObject)
            this.$emit('created',createdObject)
        },
        feedbackFailure: function(error){
            this.$refs.failureAlert.fire()
            console.log(error)
            this.$emit('error',error)
        }
    },

}
</script>