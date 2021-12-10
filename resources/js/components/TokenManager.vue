<template>
<div>
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <div class="container">
                <div class="row p-3">
                    <div class="col">
                        <h5 class="card-title">Name</h5>
                    </div>
                    <div class="col">
                        <code-block :text="device_name"></code-block>
                    </div>
                </div>
                <div class="row p-3">
                    <div class="col">
                        <h5 class="card-title">Number of valide tokens</h5>
                    </div>
                    <div class="col">
                        <code-block :text="nb_tokens"></code-block>
                    </div>
                </div>
            </div>
            <action-button classTitle="btn btn-primary"
                           title="Revoke all tokens"
                           @clicked="revokeToken"></action-button>
        </div>
    </div>
</div>

</template>

<script>
import ActionButton from "./basic/ActionButton.vue"
import TextInput from "./basic/TextInput.vue"
import CodeBlock from "./basic/CodeBlock";

export default {


    components : {
        CodeBlock,
        "ActionButton" : ActionButton,
        "TextInput" : TextInput,
    },

    created(){
    },

    data(){
        return {
            showTokensModal : false
        }
    },


    props: {
        nb_tokens : Number,
        devices_route : String,
        device_id : Number,
        device_name : String
    },

    methods: {
        revokeToken(){
            var url = this.devices_route + "/" + this.device_id + "/revoke-tokens"
            axios.get(url)
                .then((response) => {
                    this.$toasted.global.success_notification("Tokens revoked")
                    this.showLocal = false
                })
                .catch((error) => {
                    this.$toasted.global.error_notification("Error:" + error)
                });
        }
    }
}
</script>

<style>

</style>
