<template>
    <LoadingComponent :props="loading" />

    <!--========OVERVIEW START=============-->
    <OverviewComponent />
    <!--========OVERVIEW END=============-->

    <!--========ORDER STATISTICS START=============-->
    <OrderStatisticsComponent />
    <!--========ORDER STATISTICS END=============-->

    <div class="row mb-3">
        <!--========ACTIVE DELIVERIES START=============-->
        <ActiveDeliveriesMapComponent />
        <!--========ACTIVE DELIVERIES END=============-->

        <!--========ORDER SUMMARY START=============-->
        <OrderSummaryComponent />
        <!--========ORDER SUMMARY END=============-->
    </div>

    <!--========ORDER START=============-->
    <OrderListComponent />
    <!--========ORDER END=============-->
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import OverviewComponent from "./OverviewComponent.vue";
import OrderListComponent from "./OrderListComponent.vue";
import OrderStatisticsComponent from "./OrderStatisticsComponent.vue";
import ActiveDeliveriesMapComponent from "./ActiveDeliveriesMapComponent.vue";
import OrderSummaryComponent from "./OrderSummaryComponent.vue";

export default {
    name: "DeliveryBoyDashboardComponent",
    components: {
        LoadingComponent, OverviewComponent, OrderListComponent, OrderStatisticsComponent, ActiveDeliveriesMapComponent, OrderSummaryComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
        };
    },
}
</script>