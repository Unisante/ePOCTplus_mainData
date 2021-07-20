<template>
<div class="card">
    <div class="container">
        <div class="row p-2">
            <div class="col">
                <h5 class="card-title">
                        {{this.label}}
                </h5>
            </div>
            <div class="col">
                <input type="text" class="form-control" v-model="localValue">
            </div>
        </div>
        
    </div>
</div>
    
</template>


<script>
    export default {
        name: "TextInput",
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
                type: String,
                default: "example text",
            },
            label: {
                type: String,
                default: "text input",
            }
        },

        methods: {     
        },


        computed : {
            localValue : {
                get : function() {
                    return this.value
                },
                set: function(newVal) {
                    this.$emit('update:value',newVal)
                } 
            }
        }
    }
</script>