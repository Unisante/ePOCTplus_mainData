<template>
<div>
    <div class="card" v-for="input in inputs" :key="input.keyword">
        <div class="card-body">
            <div class="container">
                <div class="row p-3">
                    <div class="col">
                        <h5 class="card-title">{{input.label}}</h5>
                    </div>
                    <div class="col">
                         <p v-if="input.type == 'text'" class="card-text">{{data[input.keyword]}}</p>
                         <code-block v-if="input.type == 'code'" :text="data[input.keyword]"></code-block>
                    </div>
                </div>
            </div>
            
           
        </div>
    </div>
</div>
</template>

<script>
import CodeBlock from "./CodeBlock.vue"
export default {
    name: "ShowDetails",

    components: {
        "CodeBlock" : CodeBlock
    },

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
                        type: "text"
                    },
                    {
                        label: "Region",
                        keyword: "region",
                        type: "text",
                    },
                    {
                        label: "Human",
                        keyword: "isHuman",
                        type: "text",
                    }
                ]
            } 
        }
    }
}
</script>

<style>

</style>