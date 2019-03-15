<template>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col">
                    {{ event.name }}
                </div>
                <div class="col text-right">
                    <span class="number-of-guests">{{ event.numberOfGuests }}</span> /
                    <span class="max-number-of-guests">{{ event.maxGuests }} </span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <span v-if="daysToStart > 1">Starts in {{ daysToStart }} days</span>
                    <span v-else-if="daysToStart === 1"> Starts tomorrow</span>
                    <span v-else> {{ timeToStart }} remains</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ event.description }}
        </div>
    </div>
</template>

<script>
    export default {
        props: ['event'],
        computed: {
            daysToStart() {
                return Math.ceil(this.msToStart / (1000 * 3600 * 24));
            },
            timeToStart() {
                return new Date(this.msToStart).toISOString().slice(18, -1);
            },
            msToStart() {
                return Math.abs(Date.now() - Date.parse(this.event.starts_at));
            }
        },
        mounted() {
        },
        methods: {}
    }
</script>

<style scoped>

    .card {
        width: 22rem;
        height: 17em;
        /*max-width: 20em;*/
        margin: 1em;
        /*overflow: hidden;*/
    }

    .card-body{
        overflow: auto;
    }
</style>
