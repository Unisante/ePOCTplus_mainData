<template>
<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Assign New Medical Staff</h5>
        </div>
        <div class="card-body">
            <select-input :options="options"
                          :value.sync="medical_staff_id"
                          :label="label"></select-input>
            <action-button classTitle="btn btn-primary"
                           title="Assign"
                           @clicked="assignMedicalStaff"></action-button>
        </div>
    </div>
    <medical-staff-index :resources_data.sync="assigned_medical_staff"
                         :resources_route="medical_staff_route"
                         :has_create_button="false"
                         :custom_actions="custom_actions"
                         :default_actions="default_actions"
                         @unassign="unassignMedicalStaff"></medical-staff-index>
</div>

</template>

<script>
import MedicalStaffIndex from "./resources/MedicalStaffIndex.vue"
import ActionButton from "./basic/ActionButton.vue"
import SelectInput from "./basic/SelectInput.vue"

export default {


    components : {
        "MedicalStaffIndex" : MedicalStaffIndex,
        "ActionButton" : ActionButton,
        "SelectInput" : SelectInput,
    },

    created(){
        this.unassigned_medical_staff.forEach((ms) => {
            this.options.push({
                label: ms.first_name + " " + ms.last_name,
                value: ms.id,
            })
            this.all_medical_staff.set(ms.id, ms)
        })
        this.medical_staff.forEach((ms) => {
            this.assigned_medical_staff.push(ms)
            this.all_medical_staff.set(ms.id, ms)
        })
    },

    data(){
        return {
            options : [],
            label: "Medical Staff Name:",
            medical_staff_id: "",
            assigned_medical_staff: [],
            all_medical_staff : new Map(),
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
        medical_staff: Array,
        medical_staff_route: String,
        assign_base_url: String,
        unassigned_medical_staff: Array,
    },

    methods: {
        assignMedicalStaff() {
            axios.post(this.assign_base_url + "/assign-medical-staff/" + this.medical_staff_id.toString()).then((response) => {
                this.options = this.options.filter((opt) => {
                    return opt.value != this.medical_staff_id
                })
                var medical_per = response.data
                this.assigned_medical_staff.push(medical_per)
                this.$emit("assign-success", response)
                this.$toasted.global.success_notification("Medical Staff Successfully Assigned to Health Facility")
              })
              .catch((error) => {
                this.$emit("assign-failure", error)
                this.$toasted.global.error_notification("Something went wrong...")
              });
        },

        unassignMedicalStaff(id) {
            axios.post(this.assign_base_url + "/unassign-medical-staff/" + id.toString()).then((response) =>{
                var ms = response.data
                this.options.push({
                    label: ms.first_name + " " + ms.last_name,
                    value: ms.id,
                })
                this.assigned_medical_staff = this.assigned_medical_staff.filter((ms) => {
                   return ms.id != id
                })
                this.$emit("unassign-success",response)
                this.$toasted.global.success_notification("Medical Staff Successfully Unassigned from Health Facility")
            }).catch((error) =>{
                this.$emit("unassign-failure",error)
                console.log(error)
                this.$toasted.global.error_notification("Something went wrong...")
            })
        }

    }
}
</script>

<style>

</style>