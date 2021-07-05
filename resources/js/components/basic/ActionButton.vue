<template>
    <div class="container">
        <div class="row justify-content-center">
            <button v-on:click="click">{{title}}</button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ActionButton",
        mounted() {
            console.log('button Component mounted.')
            console.log(this.title)
        },

        props: {
            title: String,
        },

        methods: {
            click: function(event){
                this.$emit('clicked')
            },

            
        }
    }
</script>


<style scoped></style>