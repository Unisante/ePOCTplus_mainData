<template>
<action-button :title="this.title" :classTitle="this.classTitle" @clicked="this.submit"></action-button>
</template>

<script>
    import ActionButton from "./ActionButton.vue"
    export default {
        name: "AddButton",
        mounted() {
        },

        components: {
            'ActionButton': ActionButton,
        },

        props: {
            data : {
                type: Object,
                default(){
                    return {
                        title: "test",
                        instrument: "test Harp",
                        isAudible: true
                    }
                }
            },
            title: {
                type: String,
                default: "Add"
            },
            url: String,
            classTitle: {
                type: String,
                default: "btn btn-primary"
            },
        },

        methods: {
            submit: function(){
                axios.post(this.url,this.data).then(response=>{
                    this.$emit("add-success",response.data)
                }).catch(error=>{
                    this.$emit("add-error",error.response.data)
                })
            }         
        }
    }
</script>