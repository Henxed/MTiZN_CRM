<template></template>

<script>

export default {
    watch: {
        '$page.props.flash': {
            handler() {
                this.toast()
            }
        },
    },
    methods: {
        toast() {
            if(this.$page.props.flash.success){
                    this.$toast.open({message: this.$page.props.flash.success, type: 'success'})
            }else if(this.$page.props.flash.error || Object.keys(this.$page.props.errors).length > 0) {

                let text = this.$page.props.flash.error;

                if(Object.keys(this.$page.props.errors).length > 0){
                    text = "Заполните или выберите данные!"
                }
                this.$toast.open({message: text, type: 'error'})
            }
        }
    }
}
</script>

<style>
.m-message--description {
    color: #d0d0d0;
}
.m-message {
    color: #d0d0d0;
    background: #2d2d2d !important;
}
</style>
