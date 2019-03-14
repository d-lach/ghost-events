<template>
    <div class="wrapper">
        <div class="abstract">
            <div class="card-header">{{ event.name }}</div>
            <div class="card-body">
                {{ event.description }}
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

    .abstract {
        max-width: 450px;
        width: 25vh;
        height: 65vh;
    }
</style>
