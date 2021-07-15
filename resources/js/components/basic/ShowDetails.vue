<template>
<div class="d-flex flex-wrap p-2 justify-content-around">
    <div class="card" v-for="input in inputs" :key="input.keyword">
        <div class="card-body">
            <h5 class="card-title">{{input.label}}</h5>
            <p class="card-text">{{data[input.keyword]}}</p>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: "ShowDetails",
    props: {
        data : {
            type: Object,
            default() {
                return {
                    name: "Adrien",
                    region: "Switzerland",
                    isHuman: true,
                }
            }
        },
        inputs : {
            type: Array,
            default() {
                return [
                    {
                        label: "Name",
                        keyword: "name",
                    },
                    {
                        label: "Region",
                        keyword: "region"
                    },
                    {
                        label: "Human",
                        keyword: "isHuman"
                    }
                ]
            } 
        }
    }
}
</script>

<style>

</style>