<template>
    <div class="wrapper">
        <div class="card">
            <div class="card-header">{{ event.name }}</div>
            <div class="card-body">
                <div class="description">
                    {{ event.description }}
                </div>
            </div>
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
    .wrapper {
        position: absolute;
        z-index: 1000;
        background: #fff;
        border: #000 solid 1px;
        margin: 1em;
    }

    .card {
        max-width: 700px;
        width: 50vh;
        height: 65vh;
    }

    .card-body {

    }
</style>
