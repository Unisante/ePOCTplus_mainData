<template>
    <div>
        <action-button :title="title" @clicked="assign"></action-button>
        <select class="form-select" id="device-selector">
            <option disabled selected value> -- select an option -- </option>
            <option v-for="device in devices" :key="device" value="device.id">
                {{device.name}}
            </option>
        </select>
       
    </div>
    
</template>

<script>
    import ActionButton from "./ActionButton.vue"
    export default {
        name: "AssignDevice",
        data(){
            return {
                "title": "Assign Device"
            }
        },
        mounted() {
            console.log('device assignor selector')
        },
        components: {
            'ActionButton': ActionButton,
        },

        props: {
            health_facility_id : String,
            base_url: String,
            devices: Array,
        },

        methods: {
            assign: function(device_id){
                axios.post(base_url + "/" + this.health_facility_id + "/" + device_id)
            },
        }
    }
</script>


<style scoped></style>