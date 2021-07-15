<template>
<div class="card">
    <div class="container">
        <div class="row p-2">
            <div class="col">
                <h5 class="card-title">{{label}}</h5>
            </div>
            <div class="col">
                <input type="checkbox" class="form-check-input" 
                       :value="value" @input="$emit('input',$event.target.checked)" 
                       style="margin-left: 0rem;">
            </div>
        </div>
    </div>
</div>

    
</template>


<script>
    export default {
        name: "CheckboxInput",
        mounted() {
        },

        data(){
            return {
            }
        },
        components: {
        },

        props: {
            value : {
                type: Boolean,
                default: false,
            },
            label: {
                type: String,
                default: "checkbox",
            }
        },

        methods: {     
        }
    }
</script>