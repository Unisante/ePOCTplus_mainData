<template>
<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Assign New Device</h5>
        </div>
        <div class="card-body">
            <select-input :options="options"
                          :value.sync="deviceID"
                          :label="label"></select-input>
            <action-button classTitle="btn btn-primary"
                           title="Assign"
                           @clicked="assignDevice"></action-button>
        </div>
    </div>
    <device-index :resources_data.sync="assignedDevices"
                  :resources_route="devices_route"
                  :has_create_button="false"
                  :custom_actions="custom_actions"
                  :default_actions="default_actions"
                  @unassign="unassignDevice"></device-index>
</div>

</template>

<script>
import DeviceIndex from "./resources/DeviceIndex.vue"
import ActionButton from "./basic/ActionButton.vue"
import SelectInput from "./basic/SelectInput.vue"

export default {


    components : {
        "DeviceIndex" : DeviceIndex,
        "ActionButton" : ActionButton,
        "SelectInput" : SelectInput,
    },

    created(){
        this.unassignedDevices.forEach((device) => {
            this.options.push({
                label: device.name,
                value: device.id,
            })
            this.allDevices.set(device.id,device)
        })
        this.devices.forEach((device) => {
            this.assignedDevices.push(device)
            this.allDevices.set(device.id,device)
        })
    },

    data(){
        return {
            options : [],
            label: "Device Name:",
            deviceID: "",
            assignedDevices: [],
            allDevices : new Map(),
            default_actions : [
                "view",
            ],
            custom_actions : [
                {
                    label: "Unassign",
                    event: "unassign",
                    color: "red"
                }
            ],         
        }
    },


    props: {
        devices: Array,
        devices_route: String,
        assign_base_url: String,
        unassignedDevices: Array,
    },

    methods: {
        assignDevice() {
            axios.post(this.assign_base_url + "/assign-device/" + this.deviceID.toString()).then((response) => {
                this.options = this.options.filter((opt) => {
                    return opt.value != this.deviceID
                })
                var device = response.data
                this.assignedDevices.push(device)
                this.$emit("assign-success",response)
                this.$toasted.global.success_notification("Device Successfully Assigned to Health Facility")
              })
              .catch((error) => {
                this.$emit("assign-failure",error)
                this.$toasted.global.error_notification("Something went wrong...")
              });
        },

        unassignDevice(id) {
            axios.post(this.assign_base_url + "/unassign-device/" + id.toString()).then((response) =>{
                var dev = response.data
                this.options.push({
                    label: dev.name,
                    value: dev.id,
                })
                this.assignedDevices = this.assignedDevices.filter((de) => {
                   return de.id != id
                })
                this.$emit("unassign-success",response)
                this.$toasted.global.success_notification("Device Successfully Unassigned from Health Facility")
            }).catch((error) =>{
                this.$emit("unassign-failure",error)
                this.$toasted.global.error_notification("Something went wrong...")
            })
        }

    }
}
</script>

<style>

</style>