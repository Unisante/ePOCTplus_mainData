<template>
<div>
    <div v-for="input in inputs" :key="input.keyword">
        <text-input v-if="input.type == 'text'" :label="input.label"
                    :value.sync="localData[input.keyword]"></text-input>
        <select-input v-if="input.type == 'select'" 
                        :label="input.label" :options="input.options"
                        :value.sync="localData[input.keyword]"></select-input>
        <checkbox-input v-if="input.type == 'checkbox'" 
                        :label="input.label"
                        :value.sync="localData[input.keyword]"></checkbox-input>
    </div>
</div>
</template>


<script>
    import CheckboxInput from "./CheckboxInput.vue"
    import TextInput from "./TextInput.vue"
    import SelectInput from "./SelectInput.vue"
    import ActionButton from"./ActionButton.vue"
    export default {
        name: "DynamicForm",
        mounted() {
        },

        data(){
            return {
            }
        },
        components: {
            'CheckboxInput': CheckboxInput,
            'TextInput' : TextInput,
            'SelectInput': SelectInput,
            'ActionButton': ActionButton,
        },

        props: {
            data: {
                type: Object,
                default() {
                    return {
                        name: "Test Name",
                        is_checked: false,
                        option: "",
                    } 
                },
            },
            inputs: {
                type: Array,
                default() {
                    return [
                        {
                            keyword: "name",
                            label: "Name",
                            type: "text",
                        },
                        {
                            keyword: "is_checked",
                            label: "Checked",
                            type: "checkbox",
                        },
                        {
                            keyword: "option",
                            label: "Options",
                            type: "select",
                            options: [
                                {
                                    label: "option 1",
                                    value: "option_1"
                                },
                                {
                                    label:"option 2",
                                    value:"option_2"
                                }
                            ]
                        },
                    ]
                }, 
            }
        },

        methods: { 
        },

        computed : {
            localData : {
                get: function() {
                    return this.data
                },
                set: function(newVal) {
                    this.$emit("update:data",newVal)
                }
            },
        },
    }
</script>