<template>
    <LoadingComponent :props="loading" />
    <div class="col-12 lg:col-6">
        <div class="row">
            <div class="col-12">
                <NextDeliveryComponent />
            </div>
            <div class="col-12">
                <div class="db-card">
                    <div class="db-card-header">
                        <h3 class="font-semibold text-lg capitalize text-heading">{{ $t('label.orders_summary') }}</h3>
                        <div id="order-range" class="cursor-pointer flex items-center gap-3">
                            <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false"
                                :placeholder="$t('label.select_date_range')" @update:modelValue="orderSummary"
                                v-model="modelValue" :range="true">
                            </Datepicker>
                        </div>
                    </div>
                    <div class="db-card-body">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1">
                            <apexchart height="180" class="mobile:w-fit mobile:mx-auto" v-if="options"
                                :options="options" :series="options.series">
                            </apexchart>

                            <ul class="flex flex-col gap-6">
                                <li class="w-full flex items-center justify-start mobile:justify-center gap-3">
                                    <div class="w-4 h-4 bg-primary rounded-[4px]">

                                    </div>
                                    <span class="block capitalize text-sm">
                                        {{ $t("label.total_delivered") }} ({{ delivered }}%)
                                    </span>
                                </li>
                                <li class="w-full flex items-center justify-start mobile:justify-center gap-3">
                                    <div class="w-4 h-4 bg-[#A953FF] rounded-[4px]">

                                    </div>
                                    <span class="block capitalize text-sm">
                                        {{ $t("label.total_returned") }} ({{ returned }}%)
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import NextDeliveryComponent from "./NextDeliveryComponent.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
export default {
    name: "OrderSummaryComponent",
    components: { LoadingComponent, Datepicker, NextDeliveryComponent },
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
        this.orderSummary();
    },
    methods: {
        orderSummary: function (e) {
            let date = {
                first_date: '',
                last_date: '',
            };
            if (e) {
                this.first_date = e[0];
                this.last_date = e[1];
                date.first_date = e[0];
                date.last_date = e[1];
            }
            this.loading.isActive = true;
            this.$store.dispatch("deliveryBoyDashboard/orderSummary", date).then((res) => {
                this.totalOrder = parseInt(res.data.data.delivered) + parseInt(res.data.data.returned);
                this.delivered = ((res.data.data.delivered / this.totalOrder) * 100).toFixed(1) === "NaN" ? 0 : ((res.data.data.delivered / this.totalOrder) * 100).toFixed(1);
                this.returned = ((res.data.data.returned / this.totalOrder) * 100).toFixed(1) === "NaN" ? 0 : ((res.data.data.returned / this.totalOrder) * 100).toFixed(1);


                const rawData = [this.delivered, this.returned];
                const hasData = rawData.some((value) => value > 0);
                const data = [parseInt(res.data.data.delivered), parseInt(res.data.data.returned)];

                this.options = {
                    labels: hasData ? ["Delivered", "Returned"] : ["No Data"],
                    series: hasData ? data : [1],
                    chart: {
                        height: 180,
                        type: 'donut',
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '50%'
                            }
                        },
                    },
                    fill: {
                        type: 'gradient',
                    },
                    legend: {
                        show: false,
                    },
                    tooltip: {
                        enabled: true,
                        y: {
                            formatter: function (value, opts) {
                                if (!hasData) return "0";
                                return `${value}`;
                            },
                        },
                    },
                    states: {
                        hover: {
                            filter: {
                                type: 'none', 
                                value: 0.2, 
                            },
                        },
                    },
                    colors: hasData ? ['#01BE5F', '#A953FF'] : ["#d3d3d3"],
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 180,
                                height: 180
                            },
                            legend: {
                                show: false
                            }
                        }
                    }]
                };
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
}
</script>