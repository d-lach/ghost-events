<template>

    <div class="card" style=";">
        <div class="card-header">{{ event.name }}</div>
        <div class="card-body">
            {{ event.description }}

        </div>
    </div>
</template>

<script>
    export default {
        props: ['event'],
        mounted() {
        }
    }
</script>

<style scoped>

    .card {
        width: 18rem;
        height: 15em;
        /*max-width: 20em;*/
        margin: 1em;
    }

    .card-body {
        overflow: auto;
    }
</style>
