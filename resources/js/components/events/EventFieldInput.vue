<template>

    <div class="form-group">
        <label :for="field">{{ name }}</label>
        <input :type="type" class="form-control" :name="field" :id="field" v-model="value"/>
        <div v-if="error" class="text-danger">{{ error }}</div>
    </div>
</template>

<script>
    export default {
        props: ['name', 'field', 'value', 'error', 'type'],
        computed: {
        },
        mounted() {
        },
        methods: {}
    }
</script>

<style scoped>
</style>
