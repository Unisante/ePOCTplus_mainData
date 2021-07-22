<template>
<div>
    <select-input label="Choose Algorithm"
                  :options="algorithmOptions"
                  :value="chosenAlgorithmID"
                  @update:value="updateVersionsSelect"></select-input>
    <select-input v-if="showVersionsSelect" label="Choose a version"
      :options="versionsOptions"
      :value.sync="chosenVersionID"></select-input>
    <action-button classTitle="btn btn-primary"
                   title="Assign"
                   @clicked="assign"></action-button>
</div>
</template>

<script>
import SelectInput from "./basic/SelectInput.vue"
import ActionButton from "./basic/ActionButton.vue"
export default {
    components : {
        "SelectInput": SelectInput,
        "ActionButton": ActionButton,
    },
    created(){
        this.algorithms.forEach((alg) =>{
            this.algorithmOptions.push({
                label: alg.name,
                value: alg.id,
            })
        })
    },
    data(){
        return {
            algorithmOptions : [],
            versionsOptions : [],
            chosenAlgorithmID: "",
            chosenVersionID: "",
            showVersionsSelect: false
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
            this.showVersionsSelect = false
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
                this.showVersionsSelect = true
              })
              .catch((error) => {
                this.$toasted.global.error_notification("Error:" + error)
              });
        },

        assign(){
            var url = this.assign_base_url + "/" + this.chosenVersionID
            this.showVersionsSelect = false
            axios.post(url)
              .then((response) => {
                console.log(response);
                this.$emit('assign-success',response)
              })
              .catch((error) => {
                this.$emit('assign-error',error)
              });
        }
    }
}
</script>

<style>
</style>