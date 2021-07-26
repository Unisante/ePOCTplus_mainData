<template>
    <button type="button" :class="this.classTitle" v-on:click="click">{{title}}</button>
</template>

<script>
    export default {
        name: "ActionButton",
        mounted() {
        },

        props: {
            title: String,
            classTitle: String,
        },

        methods: {
            click: function(event){
                this.$emit('clicked')
            },

            
        }
    }
</script>


<style scoped>
</style>