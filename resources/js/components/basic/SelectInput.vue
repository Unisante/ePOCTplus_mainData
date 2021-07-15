<template>
    <div class="card">
        <div class="container">
            <div class="row p-2">
                <div class="col">
                    <h5 class="card-title">{{this.label}}</h5>
                </div>
                <div class="col">
                    <select class="form-select" :value="value" @input="$emit('input',$event.target.value)">
                        <option disabled value="">Please select one</option>
                        <option v-for="option in this.options" :key="option.value" v-bind:value="option.value">
                          {{option.label}}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        name: "SelectInput",
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
                default: "option_1",
            },
            label: {
                type: String,
                default: "first option",
            },
            options : {
                type : Array,
                default() {
                    return [
                        {
                            label: "first option",
                            value: "option_1",
                        },
                        {
                            label: "second option",
                            value: "option_2",
                        }
                    ]
                }
            }
        },

        methods: {     
        }
    }
</script>