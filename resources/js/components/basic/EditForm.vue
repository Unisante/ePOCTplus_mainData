<template>
<div class= "card">
    <dynamic-form :inputs="inputs" :data.sync="form"></dynamic-form>
    <update-button :data="form" :url="edit_url" :title="buttonTitle" @edit-success="feedbackSuccess" @edit-error="feedbackFailure"></update-button>
</div>

</template>

<script>
import DynamicForm from "./DynamicForm.vue"
import UpdateButton from "./UpdateButton.vue"


export default {
    
    components : {
        "DynamicForm": DynamicForm,
        "UpdateButton": UpdateButton,
    },

    name: "EditForm",

    data() {
        return {
            form : {},
            title: "Edit Object"
        }
    },

    created() {
        for(const [key,value] of Object.entries(this.data)){
            this.form[key] = this.data[key]
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
            this.$emit('edit-success',createdObject)
        },
        feedbackFailure: function(error){
            this.$emit('edit-failure',error)
        },
    },

}
</script>