<template>
    <div>
        <pagination
                :show-disabled="false"
                :data="data"
                @pagination-change-page="loadNewPage">
        </pagination>
    </div>
</template>

<script>
    import Pagination from "laravel-vue-pagination";
    import EventsService from "~/Services/EventsService";

    export default {
        props: ['data'],
        data() {
            return {
            }
        },
        mounted() {
        },
        methods: {
            loadNewPage(page) {
                EventsService.getEventsFromSoonest(page).then((response) => {
//                    console.log("response", response);
                    if (response.error) {
                        return;
                    }
                    this.data = response; // it's not going to work with data as props
                    this.$root.$emit('new-events', response.data);
                });

            }
        },
        components: {
            Pagination
        }
    }
</script>

<style scoped>

</style>
