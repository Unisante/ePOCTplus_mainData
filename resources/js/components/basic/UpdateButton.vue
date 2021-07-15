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
                data: {
                    name: "facility 000",
                    country: "switzerland",
                    area: "VD",
                    pin_code: "1234",
                    hf_mode: "standalone",
                    lat: 0.0,
                    long: 0.5,
                    local_data_ip: "127.0.0.1",
                },
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
                axios.post(this.url,{
                    data: this.data,
                    _method: 'patch'
                }).then(response=>{
                    this.$emit("update-success",response.data)
                }).catch(error=>{
                    this.$emit("update-error",error)
                })
            }         
        }
    }
</script>