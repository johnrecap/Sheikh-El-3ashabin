<template>
    <LoadingComponent :props="loading" />
    <div class="db-card">
        <div class="db-card-header">
            <h3 class="font-semibold text-lg capitalize text-heading">{{ $t('label.next_delivery') }}</h3>
        </div>
        <div class="db-card-body" v-if="order.order_serial_no">
            <div class="flex items-center gap-1 mb-1.5">
                <p class="text-base font-medium whitespace-nowrap">{{ $t('label.order_id') }}:</p><span
                    class="text-base font-medium whitespace-nowrap text-[#008BBA]">#{{ order.order_serial_no }}</span>
            </div>
            <p class="text-xs text-paragraph mb-1.5">{{ order.order_datetime }} </p>
            <div class="flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-1"><i class="lab-fill-map-2 text-paragraph flex-shrink-0"></i><span
                        class="text-sm font-medium"><span v-if="orderAddress.address">
                            {{ orderAddress.apartment ? orderAddress.apartment + ', '
                                : '' }}
                            {{ orderAddress.address }}</span></span></div>

                <router-link :to="{ name: 'admin.dashboard.activeOrder.show', params: { id: orderId } }"
                    class="flex items-center gap-0.5 text-[#01BE5F]">
                    <span class="text-xs font-semibold">{{ $t("label.see_order_details") }}</span><i
                        class="lab-line-chevron-right"></i>
                </router-link>
            </div>
        </div>
        <div class="db-card-body" v-else>
            <div class="flex justify-center items-center w-full h-16 my-[5px]">
                <p class="text-md text-paragraph">{{ $t('message.no_delivery_order_found') }}</p>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
export default {
    name: "NextDeliveryComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            orderId: 0
        };
    },
    computed: {
        order: function () {
            return this.$store.getters['deliveryBoyDashboard/nextDeliveryOrder'];
        },
        orderAddress: function () {
            return this.$store.getters['deliveryBoyDashboard/orderAddress'];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('deliveryBoyDashboard/nextDeliveryOrder').then(res => {
            this.orderId = this.order.id;
            this.loading.isActive = false;
        }).catch((error) => {
            this.loading.isActive = false;
        });
    },
    methods: {
    },
}
</script>