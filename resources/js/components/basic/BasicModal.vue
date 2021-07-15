<template>
    <div v-if="show" class="modal" style="display: block;">
        <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header"> 
                        <h4 class="modal-title">
                            <slot name="header"></slot>
                        </h4>
                        <button type="button" @click="hide" class="close"><span >&times;</span></button>
                    </div>
                    <div class="modal-body">
                            <slot name="body"></slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                    </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    name: "BasicModal",
    props : {
        show: {
            type: Boolean,
            default: false,
        }
    },

    methods : {
        hide : function() {
            this.$emit('update:show',false)
        }

    }
}
</script>
