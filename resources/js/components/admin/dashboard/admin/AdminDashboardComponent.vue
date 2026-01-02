<template>
    <LoadingComponent :props="loading" />

    <!--========OVERVIEW START=============-->
    <OverviewComponent />
    <!--========OVERVIEW END=============-->

    <!--========ORDER STATISTIC START=============-->
    <OrderStatisticsComponent />
    <!--========ORDER STATISTIC END=============-->
    <div class="row">
        <!--========SALES SUMMARY START=============-->
        <SalesSummaryComponent />
        <!--========SALES SUMMARY END=============-->

        <!--========ORDERS SUMMARY START=============-->
        <OrderSummaryComponent />
        <!--========ORDERS SUMMARY END=============-->

        <!--========CUSTOMER STATS START=============-->
        <CustomerStatsComponent />
        <!--========CUSTOMER STATS END=============-->

        <!--========TOP CUSTOMERS START=============-->
        <TopCustomersComponent />
        <!--========TOP CUSTOMERS END=============-->

        <!--========MOST POPULAR ITEMS START=============-->
        <TopProductsComponent />
        <!--========MOST POPULAR ITEMS END=============-->
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import OverviewComponent from "./OverviewComponent.vue";
import OrderStatisticsComponent from "./OrderStatisticsComponent.vue";
import SalesSummaryComponent from "./SalesSummaryComponent.vue";
import OrderSummaryComponent from "./OrderSummaryComponent.vue";
import CustomerStatsComponent from "./CustomerStatsComponent.vue";
import TopCustomersComponent from "./TopCustomersComponent.vue";
import TopProductsComponent from "./TopProductsComponent.vue";

export default {
    name: "AdminDashboardComponent",
    components: {
        LoadingComponent,
        OverviewComponent,
        OrderStatisticsComponent,
        SalesSummaryComponent,
        OrderSummaryComponent,
        CustomerStatsComponent,
        TopCustomersComponent,
        TopProductsComponent,
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