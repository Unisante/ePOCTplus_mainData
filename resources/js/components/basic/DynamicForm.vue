<template>
<div>
    <div v-for="input in inputs" :key="input.keyword">
        <text-input v-if="input.type == 'text'" v-model="data[input.keyword]" :label="input.label"
                    :value="data[input.keyword]"></text-input>
        <select-input v-if="input.type == 'select'" v-model="data[input.keyword]" 
                        :label="input.label" :options="input.options"
                        :value="data[input.keyword]"></select-input>
        <checkbox-input v-if="input.type == 'checkbox'" 
                        v-model="data[input.keyword]" 
                        :label="input.label"
                        :value="data[input.keyword]"></checkbox-input>
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
        }
    }
</script>