<template>
<div class="card" style="width: 18 rem;">
    <dynamic-form></dynamic-form>
        <button class="btn" @click="showModal">Show</button>
        <basic-modal v-if="this.showBasicModal" @close="closeModal">
            <template v-slot:header>
                Modal Header
            </template>
            <template v-slot:body>
                Modal Body
            </template>
        </basic-modal>
    <div v-if="data != null && data.length > 0">
        <table class="table">
            <thead>
                <tr v-if="columns != null && columns.length > 0">
                    <th v-for="column in columns" :key="column">{{column}}</th>
                    <th v-if="actions != null && actions.length > 0">Actions</th>
                </tr>
            </thead>
            <tr v-for="object in data" :key="object.id">
                <th v-for="keyword in keys" :key="keyword">{{object[keyword]}}</th>
                    <td v-if="hasActions">
                        <div class="btn-group btn-group-sm" role="group">
                            <action-button v-for="action in actions" :key="action"
                            :title="buttons[action].title"
                            :classTitle="buttons[action].classTitle" 
                            @clicked="buttons[action].callback(object.id)"></action-button>
                            <template v-if="custom_actions != null">
                                    <action-button v-for="action in custom_actions" 
                                       :key="action"
                                       :title="action['title']"
                                       classTitle="btn btn-outline-secondary"
                                       @clicked="custom(object.id,action['method'],action['before_url'],action['after_url'])"></action-button>
                            </template>
                        </div> 
                    </td>
            </tr>
        </table>
    </div>
    <div v-else>
        There are no {{title}}
    </div>
</div>

    
</template>

<script>
    import ActionButton from "./ActionButton.vue"
    import AddButton from "./AddButton.vue"
    import DynamicForm from "./DynamicForm.vue"
    import BasicModal from "./BasicModal.vue"
    export default {
        name: "IndexTable",
        mounted() {
            console.log('Index table mounted')
            if (this.actions != null || this.custom_actions != null){
                this.hasActions = true
            }
        },

        data(){
            return {
                "showBasicModal": false,
                "buttons": {
                    "view" : {
                        "title" : "View",
                        "callback": this.view,
                        "classTitle": "btn btn-outline-primary",
                    },
                    "edit" : {
                        "title" : "Edit",
                        "callback": this.edit,
                        "classTitle": "btn btn-outline-warning",
                    },
                    "delete": {
                        "title": "Delete",
                        "callback": this.delete,
                        "classTitle": "btn btn-outline-danger",
                    },
                    
                },
                "hasActions" : false,
                "items" : [
                    { age: 40, first_name: "prosti"},
                    { age: 96, first_name: "hello"}
                ]
            }
        },
        components: {
            'ActionButton': ActionButton,
            'AddButton': AddButton,
            "DynamicForm": DynamicForm,
            "BasicModal" : BasicModal,
        },

        props: {
            title: String,
            columns: Array,
            data: Array,
            keys: Array,
            actions: Array,
            resource_url: String,
            custom_actions: Array,
        },

        methods: {
            view: function(id){
                console.log(id)
                console.log(window.location)
                console.log(this.resource_url)
                window.location.href = this.resource_url + "/" +  id.toString()
            },

            edit: function(id){
                window.location.href = this.resource_url + "/" + id.toString() + "/edit"
            },

            delete: function(id){
                console.log(this.resource_url + "/" + id.toString())
                //axios.delete(this.resource_url + "/" + id.toString())
                axios.post(this.resource_url + "/" + id.toString(),{_method: 'delete'})
            },

            custom: function(id,method,before_url,after_url){
                var full_url = before_url + "/" + id.toString() + "/" + after_url
                switch(method){
                    case 'post':
                        axios.post(full_url)
                    case 'put':
                        axios.post(full_url,{_method:'put'})
                    case 'delete':
                        axios.post(full_url,{_method:'delete'})
                    case 'link':
                        window.location.href = full_url
                    default:
                        return
                }
            },
            showModal: function(){
                this.showBasicModal = true
                console.log(this.showBasicModal)
            },
            closeModal: function(){
                this.showBasicModal = false
                console.log(this.showBasicModal)
            }       
        }
    }
</script>


<style scoped>
.buttoncontainer{
    display: flex;
    flex-direction: row;
}
</style>