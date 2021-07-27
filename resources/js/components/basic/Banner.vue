<template>
<div :class="alertClass" role="alert">{{this.text}}</div>
</template>
<script>
export default {

    name: "Banner",
    data(){
        return {
            alertClass : "alert alert-primary",
        }
    },

    created(){
        switch(this.color){
            case "dark-blue":
                this.alertClass = "alert alert-primary"
                break
            case "green":
                this.alertClass = "alert alert-success"
                break
            case "red":
                this.alertClass = "alert alert-danger"
                break
            case "grey":
                this.alertClass = "alert alert-secondary"
                break
            case "yellow":
                this.alertClass = "alert alert-warning"
                break
        }
    },

    props: {
        color : String,
        text: String,
    }
}
</script>

<style>

</style>