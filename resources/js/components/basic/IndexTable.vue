<template>
<div v-if="data != null && data.length > 0">
    <table class="table">
        <thead>
            <tr v-if="columns != null && columns.length > 0">
                <th v-for="column in columns" v-bind:key="column">{{column}}</th>
                <th v-if="actions != null && actions.length > 0">Actions</th>
            </tr>
        </thead>
        <tr v-for="object in data" :key="object.id">
            <th v-for="keyword in keys" :key="keyword">{{object[keyword]}}</th>
            <td v-if="hasActions">
                <div v-if="actions != null">
                     <action-button v-for="action in actions" :key="action"
                    :title="buttons[action].title" 
                    @clicked="buttons[action].callback(object.id)"></action-button>
                </div>
                <div v-if="custom_actions != null">
                    <action-button v-for="action in custom_actions" 
                                   :key="action"
                                   :title="action['title']"
                                   @clicked="custom(object.id,action['method'],action['before_url'],action['after_url'])"></action-button>
                </div>
               
            </td>
        </tr>
    </table>
</div>
<div v-else>
    There are no {{title}}
</div>
    
</template>

<script>
    import ActionButton from "./ActionButton.vue"
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
                "buttons": {
                    "view" : {
                        "title" : "View",
                        "callback": this.view,
                    },
                    "edit" : {
                        "title" : "Edit",
                        "callback": this.edit,
                    },
                    "delete": {
                        "title": "Delete",
                        "callback": this.delete,
                    },
                    
                },
                "hasActions" : false,
            }
        },
        components: {
            'ActionButton': ActionButton,
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
            }

            
        }
    }
</script>


<style scoped></style>