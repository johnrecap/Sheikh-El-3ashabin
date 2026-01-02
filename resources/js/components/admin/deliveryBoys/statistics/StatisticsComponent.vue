<template>
    <LoadingComponent :props="loading" />
    <div class="row mb-3">
        <!--========ORDER DELIVERIES START=============-->
        <OrderDeliveriesMapComponent :id="parseInt(props)" />
        <!--========ORDER DELIVERIES END=============-->

        <!--========ORDER SUMMARY START=============-->
        <OrderSummaryComponent :props="props" />
        <!--========ORDER SUMMARY END=============-->
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import OrderDeliveriesMapComponent from "./OrderDeliveriesMapComponent.vue";
import OrderSummaryComponent from "./OrderSummaryComponent.vue";
export default {
    name: "StatisticsComponent",
    components: { LoadingComponent, OrderDeliveriesMapComponent, OrderSummaryComponent },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            modelValue: null,
            delivered: null,
            returned: null,
            totalOrder: null,
            options: null,
        };
    },
    mounted() {
    },
    methods: {
    },
}
</script>