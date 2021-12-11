<template>
<div>
    <basic-modal :show.sync="showLocal">
        <template v-slot:header>
            <h4>Warning</h4>
        </template>
        <template v-slot:body>
            <slot></slot>

        </template>
        <template v-slot:footer>
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
import DeleteButton from "./DeleteButton.vue"
import ActionButton from "./ActionButton.vue"
export default {
    name: "DeleteModal",

    components: {
        "BasicModal": BasicModal,
        "DeleteButton": DeleteButton,
        "ActionButton": ActionButton,
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
            this.showLocal = false
        },
        success: function(response) {
            this.$emit("delete-success",response)
            this.hide()
        },
        error: function(error) {
            this.$emit('delete-error',error)
            this.$toasted.global.error_notification("An error occurred:" + error)
            this.hide()
        }
    },

    computed : {
        showLocal : {
            set: function(newVal) {
                this.$emit('update:show',false)
            },

            get: function() {
                return this.show
            }
        }
    }

}
</script>

<style>

</style>
