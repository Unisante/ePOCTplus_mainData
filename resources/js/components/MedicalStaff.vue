<template>
<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 30px">
                Medical Staff
            </h3>
        </div>
        <div class="card-body">
            <div class="card">
                <medical-staff-index :resources_route="medical_staff_route" 
                                     :resources_data.sync="medical_staff"
                                     :medical_staff_roles="medical_staff_roles"
                ></medical-staff-index>
            </div>
        </div>
    </div>
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
import ShowDetails from "./basic/ShowDetails.vue"
import MedicalStaffIndex from "./resources/MedicalStaffIndex.vue"

export default {
    name: "MedicalStaff",

    components: {
        "DynamicForm": DynamicForm,
        "Index": Index,
        "BasicModal": BasicModal,
        "ActionButton": ActionButton,
        "CreateForm" : CreateForm,
        "EditForm": EditForm,
        "DeleteModal" : DeleteModal,
        "ShowDetails" : ShowDetails,
        "MedicalStaffIndex" : MedicalStaffIndex,
    },

    created(){
        this.medical_staff = this.medical_staff_data
        this.medical_staff_roles = this.medical_staff_roles_data
    },

    mounted() {
    },


    data() {
        return {
            showCreateModal : false,
            showEditModal: false,
            showViewModal: false,
            showDeleteModal: false,
            medical_staff: [],
            medical_staff_roles: [],
            actionURL: "",
            selectedMedicalStaff: "",
            columns: [
                {
                    label: "Name",
                    keyword: "name",
                },
                {
                    label: "Creation Data",
                    keyword: "created_at",
                },
                {
                    label: "Authentication ID",
                    keyword: "oauth_client_id",
                },
                {
                    label: "Health Facility ID",
                    keyword: "health_facility_id",
                }
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
            ],
            inputs : [
                {
                    label : "Name",
                    keyword: "name",
                    type: "text",
                    default: "",
                },
            ],
            details: [
                {
                    label: "Name",
                    keyword: "name",
                    type: "text",
                },
                {
                    label: "Model",
                    keyword: "model",
                    type: "text",
                },
                {
                    label: "Brand",
                    keyword: "brand",
                    type: "text",
                },
                {
                    label: "Operating System",
                    keyword: "os",
                    type: "text",
                },
                {
                    label: "Operating System Version",
                    keyword: "os_version",
                    type: "text",
                },
                {
                    label: "MAC Address",
                    keyword: "mac_address",
                    type: "text",
                },
                {
                    label: "Health Facility",
                    keyword: "health_facility",
                    type: "text",
                },
                {
                    label: "Authentication ID",
                    keyword: "oauth_client_id",
                    type: "text",
                },

            ],
        }
    },

    props : {
        medical_staff_route : String,
        health_facilities_route: String,
        medical_staff_data: Array,
        medical_staff_roles_data: Array,
    },


    methods : {
        createSuccess : function(ms){
            this.$toasted.global.success_notification("Medical Staff Successfully created")
            this.showCreateModal = false
            this.medical_staff.push(ms)
        },
        createFailure: function(error){
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Medical Staff Creation Failed: " + errorMsg)
            if (errors) {
                for (const [key, value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },
        destroy : function(id){
            this.actionURL= this.medical_staff_route + "/" + id.toString()
            console.log(this.actionURL)
            this.showDeleteModal = true
        },
        destroySuccess: function(response){
            var id = response['id']
            this.removeDevice(id)
            this.$toasted.global.success_notification("Medical Staff Successfully Deleted")

        },
        destroyFailure: function(error) {
            this.$toasted.global.error_notification("An error occurred...")
        },
        edit : function(id){
            this.actionURL = this.medical_staff_route + "/" + id.toString()
            this.selectedMedicalStaff = this.getMedicalStaff(id)
            this.showEditModal = true
        },
        editSuccess : function(ms){
            this.showEditModal = false
            this.medical_staff.forEach((medical_per, index) =>{
                if (medical_per.id == ms.id){
                    this.$set(this.medical_staff, index, ms)
                }
            })
            this.$toasted.global.success_notification("Medical Staff Succesfully Edited")
        },

        editFailure: function(error){
            console.log(error)
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Medical Staff Editing Failed: " + errorMsg)
            if (errors) {
                for (const [key, value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },
        view : function(id){
            this.selectedMedicalStaff = this.getMedicalStaff(id)
            this.showViewModal = true
        },

        getMedicalStaff: function(id) {
            var ms = {}
            this.medical_staff.forEach((medical_per) => {
                if(medical_per.id == id) {
                    ms = medical_per
                }
            })
            return ms
        },

        removeMedicalStaff: function(id) {
            this.medical_staff = this.medical_staff.filter(function(value, index, arr){
                return value.id != id
            })
        },

        addMedicalStaff: function(ms){
            this.medical_staff.push(ms)
        },
    }
}
</script>

<style>

</style>