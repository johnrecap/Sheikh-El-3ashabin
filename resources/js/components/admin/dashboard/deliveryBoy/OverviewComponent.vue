<template>
    <LoadingComponent :props="loading" />
    <div class="mb-9">
        <h4 class="font-semibold text-xl mb-3 capitalize text-heading">{{ $t("menu.overview") }}</h4>
        <div class="row">
            <div class="col-12 sm:col-6 xl:col-4">
                <div class="bg-admin-pink p-4 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <i class="lab-fill-box text-admin-pink text-2xl lab-font-size-24"></i>
                    </div>
                    <div>
                        <h3 class="font-medium tracking-wide capitalize text-white">{{ $t('label.total_order') }}
                        </h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ total_order }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-4">
                <div class="bg-primary p-4 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <i class="lab-fill-box-tick text-primary text-2xl lab-font-size-24"></i>
                    </div>
                    <div>
                        <h3 class="font-medium tracking-wide capitalize text-white">{{ $t('label.total_delivered') }}
                        </h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ total_delivered }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-12 sm:col-6 xl:col-4">
                <div class="bg-admin-purple p-4 rounded-lg flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center bg-white">
                        <i class="lab-fill-box-rotate text-admin-purple text-2xl lab-font-size-24"></i>
                    </div>
                    <div>
                        <h3 class="font-medium tracking-wide capitalize text-white">{{ $t('label.total_returned') }}
                        </h3>
                        <h4 class="font-semibold text-[22px] leading-[34px] text-white">{{ total_returned }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
export default {
    name: "OverviewComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            total_order: null,
            total_delivered: null,
            total_returned: null,
        };
    },
    mounted() {
        this.orderOverview();
    },
    methods: {
        orderOverview: function () {
            this.loading.isActive = true;
            this.$store.dispatch("deliveryBoyDashboard/orderOverview").then((res) => {
                this.total_order = res.data.data.total_order;
                this.total_delivered = res.data.data.total_delivered;
                this.total_returned = res.data.data.total_returned;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    },
}
</script>