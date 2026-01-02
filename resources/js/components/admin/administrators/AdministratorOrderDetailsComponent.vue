<template>
    <LoadingComponent :props="loading" />
    <OrderDetailsComponent :order="order" :orderProducts="orderProducts" :orderAddress="orderAddress"
        :outletAddress="outletAddress" :orderUser="orderUser" />
</template>
<script>

import LoadingComponent from "../components/LoadingComponent.vue";
import OrderDetailsComponent from "../components/OrderDetailsComponent.vue";
export default {
    name: "AdministratorOrderDetailsComponent",
    components: { LoadingComponent, OrderDetailsComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
        };
    },
    computed: {
        order: function () {
            return this.$store.getters['myOrderDetails/orderDetails'];
        },
        orderProducts: function () {
            return this.$store.getters['myOrderDetails/orderProducts'];
        },
        orderAddress: function () {
            return this.$store.getters['myOrderDetails/orderAddress'];
        },
        orderUser: function () {
            return this.$store.getters['myOrderDetails/orderUser'];
        },
        outletAddress: function () {
            return this.$store.getters['myOrderDetails/outletAddress'];
        },
    },
    mounted() {
        if (this.$route.params.id) {
            this.loading.isActive = true;
            this.$store.dispatch("myOrderDetails/orderDetails", {
                id: this.$route.params.id,
                orderId: this.$route.params.orderId
            }).then((res) => {
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;
            });
        }
    }
}
</script>