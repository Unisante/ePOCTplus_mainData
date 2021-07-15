<template>
    <table class="table">
        <thead>
            <tr>
                <th v-for="column in columns" :key="column.label">{{column.label}}</th>
                <th v-if="actions.length > 0">Actions</th>
            </tr>
        </thead>
        <tr v-for="object in data" :key="object.id">
            <th v-for="column in columns" :key="column.keyword">{{object[column.keyword]}}</th>
            <td v-if="actions.length > 0">
                <div class="btn-group btn-group-sm" role="group">
                <action-button v-for="action in actions" :key="action.event"
                               :title="action.label" 
                               @clicked="fireAction(object.id,action.event)"
                               :classTitle="colorToClass(action.color)"></action-button>
                </div>
            </td>
        </tr>
    </table>
</template>

<script>
    import ActionButton from "./ActionButton.vue"
    export default {

        mounted(){
        },
        name: "Index",

        components: {
            'ActionButton': ActionButton,
        },

        props: {
            columns : {
                type: Array,
                default() {
                    return [
                        {
                            keyword: "name",
                            label: "Name",
                        },
                        {
                            keyword: "id",
                            label: "ID",
                        }
                    ]
                }
            },
            actions : {
                type: Array,
                default() {
                    return [
                        {
                            label: "View",
                            event: "view",
                            color: "blue",
                        },
                        {
                            label: "Edit",
                            event: "edit",
                            color: "yellow",
                        },
                        {
                            label: "Delete",
                            event: "delete",
                            color: "red",
                        },
                    ]
                }
            },
            data : {
                type: Array,
                default() {
                    return [
                        {
                            name: "Philipps",
                            id: "12345"
                        },
                        {
                            name: "Morgan",
                            id: "12332"
                        },
                        {
                            name: "Adrien",
                            id: "09876"
                        },
                    ]
                }
            }
        },

        methods: {
            fireAction : function(id,event){
                this.$emit(event,id)
            },
            colorToClass: function(color){
                switch(color){
                    case "blue":
                        return "btn btn-outline-primary"
                    case "yellow":
                        return "btn btn-outline-warning"
                    case "red":
                        return "btn btn-outline-danger"
                    case "green":
                        return "btn btn-outline-success"
                    default:
                        return "btn btn-outline-dark"

                }
            }
        }
    }
</script>


<style scoped></style>