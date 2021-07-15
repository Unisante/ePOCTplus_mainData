<template>
<div class= "card">
    <alert-message alert_color="green" ref="successAlert" :show.sync="showSuccess">Created Successfully</alert-message>
    <alert-message alert_color="red" ref="failureAlert" :show.sync="showFailure">An error occured</alert-message>
    <dynamic-form :inputs="inputs" :data="form"></dynamic-form>
    <add-button :data="form" :url="create_url" :title="buttonTitle" @add-success="feedbackSuccess" @add-error="feedbackFailure"></add-button>
</div>

</template>

<script>
import DynamicForm from "./DynamicForm.vue"
import AddButton from "./AddButton.vue"
import AlertMessage from "./AlertMessage.vue"


export default {
    
    components : {
        "DynamicForm": DynamicForm,
        "AddButton": AddButton,
        "AlertMessage" : AlertMessage,
    },

    name: "CreateForm",

    data() {
        return {
            form : {
            },
            showSuccess : false,
            showFailure : false,
        }
    },

    mounted() {
    },

    created(){
        this.inputs.forEach((input,i)=> this.form[input.keyword] = input.default)
    },

    props : {
        create_url: {
            type: String,
            default: "/post-data-test",
        },
        buttonTitle: {
            type: String,
            default: "Submit",
        },
        inputs : {
            type: Array,
            default() {
                return [
                    {
                        keyword: "name",
                        title: "Name",
                        type: "text",
                        default: "test-value"
                    },
                    {
                        keyword: "is_checked",
                        title: "Checked",
                        type: "checkbox",
                        default: false,
                    },
                    {
                        keyword: "option",
                        title: "options",
                        type: "select",
                        default: "",
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
            this.showSuccess = true
            console.log(createdObject)
            this.$emit('created',createdObject)
        },
        feedbackFailure: function(error){
            this.showFailure = true
            console.log(error)
            this.$emit('error',error)
        }
    },
}
</script>