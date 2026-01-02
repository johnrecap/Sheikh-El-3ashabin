<template>
    <LoadingComponent :props="loading" />
    <OrderDetailsComponent :order="order" :orderProducts="orderProducts" :orderAddress="orderAddress"
        :outletAddress="outletAddress" :orderUser="orderUser" />
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import appService from "../../../../services/appService";
import OrderDetailsComponent from "../../components/OrderDetailsComponent.vue";

export default {
    name: "DeliveredOrderShowComponent",
    components: {
        LoadingComponent,
        OrderDetailsComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
        }
    },
    mounted() {
        if (this.$route.params.id) {
            this.loading.isActive = true;
            this.$store.dispatch("deliveryBoyOrder/deliveredOrderDetails", {
                id: this.$route.params.id,
                orderId: this.$route.params.orderId
            }).then((res) => {
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;

            });
        }
    },
    computed: {
        order: function () {
            return this.$store.getters['deliveryBoyOrder/deliveredOrderDetails'];
        },
        orderProducts: function () {
            return this.$store.getters['deliveryBoyOrder/orderProducts'];
        },
        orderAddress: function () {
            return this.$store.getters['deliveryBoyOrder/orderAddress'];
        },
        orderUser: function () {
            return this.$store.getters['deliveryBoyOrder/orderUser'];
        },
        outletAddress: function () {
            return this.$store.getters['deliveryBoyOrder/outletAddress'];
        },
    },
    methods: {
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
    }
}
</script>