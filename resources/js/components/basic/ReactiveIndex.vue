<template>
<div>
    <div class="card">
        <div class="card-body">
                <div v-if="has_create_button" class="d-flex-inline p-3">
                    <action-button :title="'Create New ' + resource_name"
                       classTitle="btn btn-primary"
                       @clicked="showCreateModal= true"></action-button>
                </div>
            <div class="card">
                <index :columns="columns"
                       :actions="actions"
                       :data="resources"
                       @delete="destroy"
                       @edit="edit"
                       @view="view"
                       v-on="$listeners"></index>
            </div>
        </div>
    </div>


    <basic-modal id="createResource" :show.sync="showCreateModal">
        <template v-slot:header>
            <h3>Create New {{resource_name}}</h3>
        </template>
        <template v-slot:body>
            <create-form :buttonTitle="'Create New ' + resource_name"
                         :create_url="resources_route"
                         :inputs="inputsCreate"
                         @created="createSuccess"
                         @error="createFailure"></create-form>
        </template>
    </basic-modal>

    <delete-modal :url="actionURL"
                  :show.sync="showDeleteModal"
                  @delete-success="destroySuccess"
                  @delete-failure="destroyFailure">
                  Are you sure you want to delete this {{resource_name}}?
                  </delete-modal>
    
    <basic-modal id="showResource" :show.sync="showViewModal">
        <template v-slot:header>
            <h5>Showing {{resource_name}} {{selectedResource.name}}</h5>
        </template>
        <template v-slot:body>
            <show-details :data="selectedResource"
                          :inputs="details"></show-details>
        </template>
    </basic-modal>

    <basic-modal id="editHealthFacilty" :show.sync="showEditModal">
         <template v-slot:header>
            <h5>Editing {{resource_name}} {{selectedResource.name}}</h5>
        </template>
        <template v-slot:body>
            <edit-form buttonTitle="Submit"
               :edit_url="actionURL"
               :data="selectedResource"
               :inputs="inputsEdit"
               @edit-success="editSuccess"
               @edit-failure="editFailure"></edit-form>
        </template>
        
    </basic-modal>
</div>
</template>

<script>
import DynamicForm from "@/components/basic/DynamicForm.vue"
import Index from "@/components/basic/Index.vue"
import BasicModal from "@/components/basic/BasicModal.vue"
import DeleteModal from "@/components/basic/DeleteModal.vue"
import ActionButton from "@/components/basic/ActionButton.vue"
import CreateForm from "@/components/basic/CreateForm.vue"
import EditForm from "@/components/basic/EditForm.vue"
import ShowDetails from "@/components/basic/ShowDetails.vue"

export default {
    name: "ReactiveIndex",

    components: {
        "DynamicForm": DynamicForm,
        "Index": Index,
        "BasicModal": BasicModal,
        "ActionButton": ActionButton,
        "CreateForm" : CreateForm,
        "EditForm": EditForm,
        "DeleteModal" : DeleteModal,
        "ShowDetails" : ShowDetails,
    },

    created(){
        this.resources = this.resources_data
        this.default_actions.forEach((val) => {
            this.actions.push(this.defaultActions[val])
        })
        this.custom_actions.forEach((val) => {
            this.actions.push(val)
        })

    },

    data() {
        return {
            showCreateModal : false,
            showEditModal: false,
            showViewModal: false,
            showDeleteModal: false,
            actionURL: "",
            selectedResource: "",
            actions: [],
            defaultActions : {
                "view" : {
                    label: "View",
                    event: "view",
                    color: "blue",
                },
                'edit':  {
                    label: "Edit",
                    event: "edit",
                    color: "yellow",
                },
                'delete' : {
                    label: "Delete",
                    event: "delete",
                    color: "red",
                },
            }
        }
    },

    props : {
        resources_route : String,
        resources_data: Array,
        columns: Array,
        details: Array,
        inputsCreate: Array,
        inputsEdit: Array,
        default_actions: {
            type: Array,
            default(){
                return [
                    'view','edit','delete'
                ]
            }
        },
        custom_actions: {
            type: Array,
            default(){
                return []
            }
        },
        resource_name: {
            type: String,
            default: "",
        },
        has_create_button: {
            type: Boolean,
            default: true,
        }
    },


    methods : {
        createSuccess : function(resource){
            this.showCreateModal = false
            console.log(resource.data)
            this.resources.push(resource)
            this.$emit("create-success",resource)
        },

        createFailure: function(error){
            this.$emit("create-failure",error)
        },

        destroy : function(id){
            this.$emit("delete",id)
            this.actionURL= this.resources_route + "/" + id.toString()
            this.showDeleteModal = true
        },

        destroySuccess: function(response){
            var id = response['id']
            this.removeResource(id)
            this.$emit("destroy-success",response)
        },

        destroyFailure: function(error) {
            this.$emit("destroy-failure",error)
        },

        edit : function(id){
            this.$emit("edit",id)
            this.actionURL = this.resources_route + "/" + id.toString()
            this.selectedResource = this.getResource(id)
            this.showEditModal = true
        },

        editSuccess : function(resource){
            this.showEditModal = false
            this.resources.forEach((res,index) =>{
                if (res.id == resource.id){
                    this.$set(this.resources,index,resource)
                }
            })
            this.$emit("edit-success",resource)
        },

        editFailure: function(error){
            this.$emit("edit-failure",error)
        },
        view : function(id){
            this.$emit("view",id)
            this.selectedResource = this.getResource(id)
            this.showViewModal = true
        },

        getResource: function(id) {
            var dev = {}
            this.resources.forEach((resource) => {
                if(resource.id == id) {
                    dev = resource
                }
            })
            return dev
        },

        removeResource: function(id) {
            this.resources = this.resources.filter(function(value,index,arr){
                return value.id != id
            })
        },
    },

    computed: {
        
        resources : {
            get() {
                return this.resources_data
            },
            set(newValue) {
                this.$emit('update:resources_data',newValue)
            }
        }
    },
}
</script>

<style>

</style>