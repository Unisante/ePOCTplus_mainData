<template>
<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                Assign algorithm version
            </h3>
        </div>
        <div class="card-body">
            <select-input label="Choose Algorithm"
                          :options="algorithmOptions"
                          :value="chosenAlgorithmID"
                          @update:value="updateVersionsSelect"></select-input>
            <select-input  label="Choose a version"
              :options="versionsOptions"
              :value.sync="chosenVersionID"></select-input>
            <div class="d-flex-inline p-3">
                <div class="container">
                    <div class="row p-2">
                        <div class="col">
                            <action-button classTitle="btn btn-primary"
                               title="Assign"
                               @clicked="assign"></action-button>
                        </div>
                        <div class="col">
                            <div v-if="loading" class="spinner-border text-success" role="status">
                              
                            </div>
                             
                        </div>
                    </div>
                </div>
                
               
            </div>
            
            <div class="card">
                <div class="container">
                    <div class="row p-2 gy-2">
                        <div class="col">
                            <h5 class="card-title">Current version:</h5>
                        </div>
                        <div class="col">
                            <banner v-if="this.currentAccess != null" color="green" :text="this.currentAccess.version_name"></banner>
                            <p v-if="this.currentAccess == null">No Version is assigned yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                Archived Versions
            </h5>
        </div>
        <div class="card-body">
            <index :actions="[]"
            :columns="accessColumns"
            :data="archivedAccesses"></index>
        </div>
    </div>
    
    
</div>
</template>

<script>
import SelectInput from "./basic/SelectInput.vue"
import ActionButton from "./basic/ActionButton.vue"
import Banner from "./basic/Banner.vue"
import Index from "./basic/Index.vue"
export default {
    components : {
        "SelectInput": SelectInput,
        "ActionButton": ActionButton,
        "Index": Index,
        "Banner": Banner,
    },
    created(){
        this.getAccesses()
        this.algorithms.forEach((alg) =>{
            this.algorithmOptions.push({
                label: alg.name,
                value: alg.id,
            })
        })
    },
    data(){
        return {
            loading : false,
            algorithmOptions : [],
            versionsOptions : [],
            chosenAlgorithmID: "",
            chosenVersionID: "",
            archivedAccesses : [],
            currentAccess : {},
            accessColumns: [
                {
                    label: "Version Name",
                    keyword: "version_name"
                },
                {
                    label: "Start Date",
                    keyword: "created_at",
                },
                {
                    label: "End Date",
                    keyword: "end_date"
                },
                {
                    label: "Reader Version",
                    keyword: "medal_r_json_version",
                }

            ],
        }
    },
    props: {
        algorithms : Array,
        assign_base_url: String,
        algorithms_accesses_route: String,
        versions_route: String,
    },
    methods : {
        updateVersionsSelect(newVal){
            this.chosenAlgorithmID = newVal
            this.versionsOptions = []
            var url = this.versions_route + "/" + newVal
            axios.get(url)
              .then((response) => {
                response.data.forEach((version) => {
                    this.versionsOptions.push({
                        label: version.name,
                        value: version.id,
                    });
                })
              })
              .catch((error) => {
                this.$toasted.global.error_notification("Error:" + error)
              });
        },

        assign(){
            this.loading = true
            var url = this.assign_base_url + "/" + this.chosenVersionID
            axios.post(url)
              .then((response) => {
                this.$emit('assign-success',response)
                this.getAccesses()
                this.$toasted.global.success_notification("Version Successfully Assigned")
                this.loading = false
              })
              .catch((error) => {
                this.$emit('assign-error',error)
                this.$toasted.global.error_notification("Error when assigning version:" + error)
                this.loading = false
              });
        },

        getAccesses(){
            var url = this.algorithms_accesses_route
            axios.get(url)
              .then((response) => {
                this.currentAccess = response.data.currentAccess
                this.archivedAccesses = response.data.archivedAccesses
                this.loading = false
              })
              .catch((error) => {
                  this.$toasted.global.error_notification("Error Loading Accesses:",error)
                  this.loading = false
              });
        }
    }
}
</script>

<style>
</style>