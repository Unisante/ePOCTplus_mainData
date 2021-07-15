<template>
<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                HealthFacilities
            </h3>
        </div>
        <div class="card-body">
                <div class="d-flex-inline p-3">
                    <action-button title="Create New Health Facility"
                       classTitle="btn btn-primary"
                       @clicked="showCreateModal= true"></action-button>
                </div>
            <div class="card">
                <index :columns="columns"
                       :actions="actions"
                       :data="healthFacilities"
                       @delete="destroy"
                       @edit="edit"
                       @view="view"></index>
            </div>
            
        </div>
    </div>


    <basic-modal id="createHealthFacility" :show.sync="showCreateModal">
        <template v-slot:header>
            <h3>Create New Health Facility</h3>
        </template>
        <template v-slot:body>
            <create-form buttonTitle="Create New Health Facility"
                         :create_url="health_facilities_route"
                         :inputs="inputs"
                         @created="createSuccess"
                         @error="createFailure"></create-form>
        </template>
    </basic-modal>

    <delete-modal :url="actionURL"
                  :show.sync="showDeleteModal"
                  @delete-success="destroySuccess"
                  @delete-failure="destroyFailure"></delete-modal>
</div>
</template>

<script>
import DynamicForm from "./basic/DynamicForm.vue"
import Index from "./basic/Index.vue"
import BasicModal from "./basic/BasicModal.vue"
import DeleteModal from "./basic/DeleteModal.vue"
import ActionButton from "./basic/ActionButton.vue"
import CreateForm from "./basic/CreateForm.vue"
import EditForm from "./basic/EditForm.vue"

export default {
    name: "HealthFacilities",

    components: {
        "DynamicForm": DynamicForm,
        "Index": Index,
        "BasicModal": BasicModal,
        "ActionButton": ActionButton,
        "CreateForm" : CreateForm,
        "EditForm": EditForm,
        "DeleteModal" : DeleteModal,
    },

    created(){
        this.healthFacilities = this.health_facilities
    },

    mounted() {
    },


    data() {
        return {
            showCreateModal : false,
            showEditModal: false,
            showViewModal: false,
            showDeleteModal: false,
            healthFacilities: [],
            actionURL: "",
            columns: [
                {
                    label: "Name",
                    keyword: "name",
                },
                {
                    label: "Country",
                    keyword: "country",
                },
                {
                    label: "Area",
                    keyword: "area",
                },
            ],
            actions : [
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
                {
                    label: "Manage Devices",
                    event: "devices",
                    color: "green",
                },
            ],
            inputs : [
                {
                    label : "Name",
                    keyword: "name",
                    type: "text",
                    default: "",
                },
                {
                    label : "Country",
                    keyword: "country",
                    type: "text",
                    default: "",
                },
                {
                    label : "Area",
                    keyword: "area",
                    type: "text",
                    default: "",
                },
                {
                    label : "Longitude",
                    keyword: "long",
                    type: "text",
                    default: "0.00",
                },
                {
                    label : "Latitude",
                    keyword: "lat",
                    type: "text",
                    default: "0.00"
                },
                {
                    label : "Architecture",
                    keyword: "hf_mode",
                    type: "select",
                    default: "",
                    options: [
                        {
                            label: "Client Server",
                            value: "client-server",
                        },
                        {
                            label: "Standalone",
                            value: "standalone"
                        }
                    ]
                },
                {
                    label : "medAL-hub IP (only for Client Server)",
                    keyword: "local_data_ip",
                    type: "text",
                },
                {
                    label : "Pin Code",
                    keyword: "pin_code",
                    type: "text",
                },
            ],
        }
    },

    props : {
        health_facilities_route : String,
        health_facilities: Array,
    },


    methods : {
        createSuccess : function(healthFacility){
            console.log(healthFacility)
            this.healthFacilities.push(healthFacility)
            console.log(this.health_facilities)
        },
        createFailure: function(error){
            console.log(error)
        },
        destroy : function(id){
            this.actionURL= this.health_facilities_route + "/" + id.toString()
            this.showDeleteModal = true
        },
        destroySuccess: function(response){
            id = response['id']
            this.healthFacilities = this.healthFacilities.filter(function(value,index,arr){
                return value.id != id
            })

        },
        destroyFailure: function(error) {

        },
        edit : function(id){

        },
        view : function(id){
            this.$toasted.global.error_notification().goAway(1500)
        },
        devices : function(id){

        },
    }
}
</script>

<style>

</style>