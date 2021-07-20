<template>
<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title" style="font-size: 30px">
                Devices
            </h3>
        </div>
        <div class="card-body">
            <div class="card">
                <device-index :resources_route="devices_route" :resources_data.sync="devices"
                ></device-index>
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
import DeviceIndex from "./resources/DeviceIndex.vue"

export default {
    name: "Devices",

    components: {
        "DynamicForm": DynamicForm,
        "Index": Index,
        "BasicModal": BasicModal,
        "ActionButton": ActionButton,
        "CreateForm" : CreateForm,
        "EditForm": EditForm,
        "DeleteModal" : DeleteModal,
        "ShowDetails" : ShowDetails,
        "DeviceIndex" : DeviceIndex,
    },

    created(){
        this.devices = this.devices_data
    },

    mounted() {
    },


    data() {
        return {
            showCreateModal : false,
            showEditModal: false,
            showViewModal: false,
            showDeleteModal: false,
            devices: [],
            actionURL: "",
            selectedDevice: "",
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
        devices_route : String,
        health_facilities_route: String,
        devices_data: Array,
    },


    methods : {
        createSuccess : function(device){
            this.$toasted.global.success_notification("Device Successfully created")
            this.showCreateModal = false
            this.devices.push(device)
        },
        createFailure: function(error){
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Device Creation Failed: " + errorMsg)
            if (errors) {
                for (const [key,value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },
        destroy : function(id){
            this.actionURL= this.devices_route + "/" + id.toString()
            console.log(this.actionURL)
            this.showDeleteModal = true
        },
        destroySuccess: function(response){
            var id = response['id']
            this.removeDevice(id)
            this.$toasted.global.success_notification("Device Successfully Deleted")

        },
        destroyFailure: function(error) {
            this.$toasted.global.error_notification("An error occurred...")
        },
        edit : function(id){
            this.actionURL = this.devices_route + "/" + id.toString()
            this.selectedDevice = this.getDevice(id)
            this.showEditModal = true
        },
        editSuccess : function(device){
            this.showEditModal = false
            this.devices.forEach((dev,index) =>{
                if (dev.id == device.id){
                    this.$set(this.devices,index,device)
                }
            })
            this.$toasted.global.success_notification("Device Succesfully Edited")
        },

        editFailure: function(error){
            console.log(error)
            var errorMsg = error['message']
            var errors = error['errors']
            this.$toasted.global.error_notification("Device Editing Failed: " + errorMsg)
            if (errors) {
                for (const [key,value] of Object.entries(errors)){
                    this.$toasted.global.error_notification(value)
                }
            }
        },
        view : function(id){
            this.selectedDevice = this.getDevice(id)
            this.showViewModal = true
        },

        getDevice: function(id) {
            var dev = {}
            this.devices.forEach((device) => {
                if(device.id == id) {
                    dev = device
                }
            })
            return dev
        },

        removeDevice: function(id) {
            this.devices = this.devices.filter(function(value,index,arr){
                return value.id != id
            })
        },

        addDevice: function(device){
            this.devices.push(device)
        },
    }
}
</script>

<style>

</style>