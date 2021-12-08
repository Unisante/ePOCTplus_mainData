<template>
<action-button :title="this.title" :classTitle="this.classTitle" @clicked="this.submit"></action-button>
</template>

<script>
    import ActionButton from "./ActionButton.vue"
    export default {
        name: "UpdateButton",
        mounted() {
        },

        data(){
            return {
            }
        },
        components: {
            'ActionButton': ActionButton,
        },

        props: {
            data : Object,
            title: {
                type: String,
                default: "Update"
            },
            url: String,
            classTitle: {
                type: String,
                default: "btn btn-primary"
            },
        },

        methods: {
            submit: function(){
                axios.put(this.url,this.data).then(response=>{
                    this.$emit("edit-success",response.data)
                }).catch(error=>{
                    this.$emit("edit-error",error.response.data)
                })
            }
        }
    }
</script>
