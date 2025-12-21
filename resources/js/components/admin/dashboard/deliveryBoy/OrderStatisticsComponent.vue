<template>
    <LoadingComponent :props="loading" />
    <div class="flex items-center justify-between gap-3 mb-4">
        <h4 class="font-semibold text-xl capitalize text-heading">{{ $t('menu.order_statistics') }}</h4>
        <div class="relative cursor-pointer">
            <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false"
                :placeholder="$t('label.select_date_range')" @update:modelValue="handleDate" v-model="modelValue"
                :range="true">
            </Datepicker>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="bg-admin-green/10 w-12 h-12 rounded-full flex items-center justify-center">
                    <i class="lab-fill-box text-admin-green text-2xl lab-font-size-24"></i>
                </div>
                <div>
                    <h3 class="font-medium text-sm capitalize tracking-wide mb-1">{{ $t('label.total_orders') }}
                    </h3>
                    <h4 class="font-bold text-lg text-secondary">{{ total_order }}</h4>
                </div>
            </div>
        </div>

        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="bg-admin-purple/10 w-12 h-12 rounded-full flex items-center justify-center">
                    <i class="lab-fill-box-tick text-admin-purple text-2xl lab-font-size-24"></i>
                </div>
                <div>
                    <h3 class="font-medium text-sm capitalize tracking-wide mb-1">{{ $t('label.delivered') }}</h3>
                    <h4 class="font-bold text-lg text-secondary">{{ delivered }}</h4>
                </div>
            </div>
        </div>

        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="bg-admin-blue/10 w-12 h-12 rounded-full flex items-center justify-center">
                    <i class="lab-fill-box-rotate text-admin-blue text-2xl lab-font-size-24"></i>
                </div>
                <div>
                    <h3 class="font-medium text-sm capitalize tracking-wide mb-1">{{ $t('label.returned') }}</h3>
                    <h4 class="font-bold text-lg text-secondary">{{ returned }}</h4>
                </div>
            </div>
        </div>
        <div class="col-12 sm:col-6 md:col-4 lg:col-6 xl:col-3">
            <div class="flex items-center gap-4 p-4 rounded-lg shadow-xs bg-white">
                <div class="bg-admin-sky/10 w-12 h-12 rounded-full flex items-center justify-center">
                    <i class="lab-fill-shipping text-admin-sky text-2xl lab-font-size-24"></i>
                </div>
                <div>
                    <h3 class="font-medium text-sm capitalize tracking-wide mb-1">
                        {{ $t('label.ongoing') }}
                    </h3>
                    <h4 class="font-bold text-lg text-secondary">{{ out_for_delivery }}</h4>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
export default {
    name: "OrderStatisticsComponent",
    components: { LoadingComponent, Datepicker },
    data() {
        return {
            loading: {
                isActive: false,
            },
            first_date: null,
            last_date: null,
            total_order: null,
            delivered: null,
            returned: null,
            out_for_delivery: null,
            modelValue: null
        };
    },
    mounted() {
        this.orderStatistic();
    },
    methods: {
        handleDate: function (e) {
            if (e) {
                this.first_date = e[0];
                this.last_date = e[1];

                this.loading.isActive = true;
                this.$store.dispatch("deliveryBoyDashboard/orderStatistics", {
                    first_date: this.first_date,
                    last_date: this.last_date,
                }).then((res) => {
                    this.total_order = res.data.data.total_order;
                    this.delivered = res.data.data.delivered;
                    this.returned = res.data.data.returned;
                    this.out_for_delivery = res.data.data.out_for_delivery;
                    this.loading.isActive = false;
                }).catch((err) => {
                    this.loading.isActive = false;
                });
            } else {
                this.date = null;
                this.first_date = null;
                this.last_date = null;
                this.orderStatistic();
            }
        },
        orderStatistic: function () {
            this.loading.isActive = true;
            this.$store.dispatch("deliveryBoyDashboard/orderStatistics").then((res) => {
                this.total_order = res.data.data.total_order;
                this.delivered = res.data.data.delivered;
                this.returned = res.data.data.returned;
                this.out_for_delivery = res.data.data.out_for_delivery;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    }
}
</script>