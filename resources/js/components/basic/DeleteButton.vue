<template>
<action-button :title="this.title" :classTitle="this.classTitle" @clicked="this.submit"></action-button>
</template>

<script>
    import ActionButton from "./ActionButton.vue"
    export default {
        name: "DeleteButton",
        mounted() {
        },

        data(){
            return {
                buttonTitle: "",
            }
        },
        components: {
            'ActionButton': ActionButton,
        },

        props: {
            title: {
                type: String,
                default: "Delete",
            },
            url: String,
            classTitle: {
                type: String,
                default: 'btn btn-danger'
            }
        },

        methods: {
            submit: function(){
                axios.delete(this.url).then(response=>{
                    this.$emit("delete-success",response.data)
                }).catch(error=>{
                    console.log(error)
                    this.$emit("delete-error",error)
                })
            }         
        }
    }
</script>