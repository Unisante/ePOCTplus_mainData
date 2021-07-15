<template>
<div class="container-custom-alert" v-if="show" role="alert">
    <div :class="alertClass" role="alert">
      <slot></slot>
    </div>
</div>
    
</template>

<script>
export default {


    created(){
        switch(this.alert_color){
            case "red":
                this.alertClass = "alert alert-danger alert-dismissible fade show"
            case "yellow":
                this.alertClass = "alert alert-warning alert-dismissible fade show"
            case "green":
                this.alertClass = "alert alert-success alert-dismissible fade show"
        }
    },
    data() {
        return {
            alertClass: "alert alert-danger alert-dismissible fade show",
        }
    },

    props: {
        alert_color: {
            type: String,
            default: "red",
        },
        duration: {
            type: Number,
            default: 1500,
        },
        show: {
            type: Boolean,
            default: false,
        },
    },


    methods : {
        hide : function(){
            this.$emit("update:show",false)
        }
    },

    watch : {
        show(val) {
            if (val) {
                setTimeout(()=> this.hide(),this.duration)
            }
        }
    }
}
</script>

<style>
.container-custom-alert {
    display: inline-block;
    position: absolute;
    z-index: 100;
}
</style>