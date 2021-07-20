<template>
    <ActionButton :title="title" @clicked="handler"></ActionButton>
</template>

<script>
    import ActionButton from "./ActionButton.vue"
    export default {
        name: "LinkButton",
        components: {
            'ActionButton': ActionButton,
        },
        mounted() {
        },

        props: {
            url: String,
            title: String,
        },

        methods: {
            "handler" : function(){
                window.location.href = this.url
            },
        }
    }
</script>


<style scoped></style>