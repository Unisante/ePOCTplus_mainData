<template>
<div>
    <div class="card">
        <index></index> 
    </div>

</div>




</template>

<script>
    import Index from "./Index.vue"

    export default {
        name: "ReactiveIndex",
        mounted() {
            
        },

        data(){
            return {
                basicActions: [
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
        }
    }
</script>


<style scoped></style>