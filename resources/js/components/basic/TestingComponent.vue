<template>

<div class="card">
    <button class="btn" @click="showModal = true">Show</button>
    <basic-modal :show.sync="showModal" >
        <template v-slot:header>
            Modal Header
        </template>
        <template v-slot:body>
            <create-form></create-form>
        </template>
    </basic-modal>
    <div class="card">
        <dynamic-form :data="formData" :inputs="formInputs"></dynamic-form>
    </div>
    <button class="btn" @click="showData">Show Data</button>
    <index></index>
    <show-details></show-details>
</div>
</template>

<script>
import DynamicForm from "./DynamicForm.vue"
import BasicModal from "./BasicModal.vue"
import CreateForm from "./CreateForm.vue"
import Index from "./Index.vue"
import ShowDetails from "./ShowDetails.vue"
export default {
    components : {
        "Index": Index,
        "BasicModal": BasicModal,
        "DynamicForm": DynamicForm,
        "CreateForm": CreateForm,
        "ShowDetails": ShowDetails,
    },

    data() {
        return {
            showModal : false,
            formData: {
                name_2: "Test Name",
                is_checked: false,
                option: "",
            },
            formInputs: [
                {
                    keyword: "name_2",
                    title: "Name",
                    type: "text",
                },
                {
                    keyword: "is_checked",
                    title: "Checked",
                    type: "checkbox",
                },
                {
                    keyword: "option",
                    title: "options",
                    type: "select",
                    options: [
                        {
                            label: "option 1",
                            value: "option_1"
                        },
                        {
                            label:"option 2",
                            value:"option_2"
                        },
                        {
                            label: "option 3",
                            value: "option_3"
                        }
                    ]
                },
            ],
        }
    },

    methods : {
    }
}
</script>
