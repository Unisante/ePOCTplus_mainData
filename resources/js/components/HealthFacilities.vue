<template>
<div>
    <div class="card">
        <div class="card-header">
            <h1 class="card-title" style="font-size: 30px">
                Health Facilities
            </h1>
        </div>
        <div class="card-body">
            <health-facility-index :resources_route="health_facilities_route"
            :resources_data.sync="health_facilities"
            :custom_actions="custom_actions"
            @devices="manageDevices"
            @algorithms="manageAlgorithms"></health-facility-index>
        </div>
    </div>
    <basic-modal :show.sync="showDevices">
        <template v-slot:header>
            <h5>Devices from Health Facility {{selectedHealthFacility.name}}</h5>
        </template>
        <template v-slot:body>
            <device-manager :devices="devices"
                            :assign_base_url="actionURL"
                            :devices_route="devices_route"
                            :unassignedDevices="unassignedDevices"></device-manager>
        </template>
    </basic-modal>
    <basic-modal :show.sync="showAlgorithms">
        <template v-slot:header>
            <h5>Algorithms for Health Facility {{selectedHealthFacility.name}}</h5>
        </template>
        <template v-slot:body>
            <algorithm-manager :algorithms="algorithms"
                            :assign_base_url="actionURL + '/assign-version'"
                            :versions_route="versionsRoute"
                            :algorithms_accesses_route="actionURL + '/accesses'"></algorithm-manager>
        </template>
    </basic-modal>
</div>
</template>

<script>
import BasicModal from "./basic/BasicModal.vue"
import ActionButton from "./basic/ActionButton.vue"
import DeviceManager from "./DeviceManager.vue"
import AlgorithmManager from "./AlgorithmManager.vue"
import HealthFacilityIndex from "./resources/HealthFacilityIndex.vue"

export default {
    name: "HealthFacilities",

    components: {
        "BasicModal": BasicModal,
        "ActionButton": ActionButton,
        "DeviceManager": DeviceManager,
        "AlgorithmManager": AlgorithmManager,
        "HealthFacilityIndex": HealthFacilityIndex,
    },

    created(){
        this.healthFacilities = this.health_facilities
    },

    mounted() {
    },


    data() {
        return {
            versionsRoute : this.health_facilities_route + "/versions",
            showDevices :false,
            showAlgorithms: false,
            devices: [],
            algorithms: [],
            unassignedDevices: [],
            healthFacilities: [],
            selectedHealthFacility: {},
            assignmentURL: "",
            unassignmentURL: "",
            custom_actions : [
                 {
                    label: "Devices",
                    event: "devices",
                    color: "green",
                },
                {
                    label: "Algorithms",
                    event: 'algorithms',
                    color: 'dark',
                },
            ],
            default_actions : [
                ['view','edit','delete']               
            ],
        }
    },

    props : {
        health_facilities_route : String,
        devices_route: String,
        health_facilities: Array,
    },


    methods : {
        

        manageDevices: function(id){
            
            var url = this.health_facilities_route + "/" + id + "/manage-devices"
            axios.get(url)
              .then((response) => {
                this.actionURL = this.health_facilities_route + "/" + id
                this.selectedHealthFacility = response.data.healthFacility
                this.devices = response.data.devices
                this.unassignedDevices = response.data.unassignedDevices
                this.showDevices = true
              })
              .catch((error) => {
                this.$toasted.global.error_notification("Error:" + error)
              });
        },

        manageAlgorithms: function(id){
            var url = this.health_facilities_route + "/" + id + "/manage-algorithms"
            axios.get(url)
              .then((response) => {
                console.log(response)
                this.actionURL = this.health_facilities_route + "/" + id
                this.selectedHealthFacility = response.data.healthFacility
                this.algorithms = response.data.algorithms
                this.showAlgorithms = true
              })
              .catch((error) => {
                this.$toasted.global.error_notification("Error:" + error)
              });
        }
    }
}
</script>

<style>

</style>