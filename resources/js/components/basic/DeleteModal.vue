<template>
<div>
    <basic-modal :show.sync="show">
        <template v-slot:header>
            <h4>Warning</h4>
        </template>
        <template v-slot:body>
            <slot></slot>
            <action-button title="Cancel" classTitle="btn btn-primary" @clicked="hide"></action-button>
            <delete-button title="Delete" classTitle="btn btn-danger" 
                           :url="url"
                           @delete-success="success"
                           @delete-error="error"></delete-button>
        </template>
    </basic-modal>
</div>
</template>

<script>
import BasicModal from "./BasicModal.vue"
export default {
    name: "DeleteModal",

    components: {
        "BasicModal": BasicModal
    },
    
    data() {
        return {

        }
    },

    props : {
        url : String,
        show: Boolean,

    },

    methods: {
        hide : function() {
            this.$emit("update:show",false)
        },
        success: function(response) {
            this.$emit("delete-success",response)
            this.hide()
        },
        error: function(error) {
            this.$emit('delete-error',error)
            this.hide()
        }
    }

}
</script>

<style>

</style>