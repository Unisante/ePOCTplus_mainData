<template>
<div>
    <div class="card">
        <div class="card-header">
            <text-input :value.sync="n_stickers_str"
                        label="Number of stickers"></text-input>
            <action-button classTitle="btn btn-primary"
                title="Generate"
                @clicked="generateStickers"></action-button>
        </div>
    </div>
</div>

</template>

<script>
import ActionButton from "./basic/ActionButton.vue"
import TextInput from "./basic/TextInput.vue"

export default {


    components : {
        "ActionButton" : ActionButton,
        "TextInput" : TextInput,
    },

    created(){
    },

    data(){
        return {
            n_stickers_str: "5"
        }
    },


    props: {
        health_facilities_route: String,
        health_facility_id: String,
    },

    methods: {
        generateStickers(){
            var str = this.n_stickers_str.trim()
            var n_stickers = parseInt(str)
            if(n_stickers == Infinity || Number.isNaN(n_stickers) || String(n_stickers) != str || n_stickers <= 0){
                var error = "The specified number of stickers is not valid."
                this.$emit("invalid-input", error)
                this.$toasted.global.error_notification(error)
                return;
            }
            if(n_stickers > 1000){
                var error = "The specified number of stickers is too big."
                this.$emit("invalid-input", error)
                this.$toasted.global.error_notification(error)
                return;
            }
            var id = this.health_facility_id
            window.location.replace('generate-stickers?n_stickers=' + n_stickers + '&group_id=' + id);
        }
    }
}
</script>

<style>

</style>