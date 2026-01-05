<template>
    <LoadingComponent :props="loading" />

    <div class="mb-8">
        <h3 class="capitalize font-bold text-2xl text-primary mb-1.5">{{ visitorMessage() }}</h3>
        <h4 class="capitalize font-medium text-xl text-secondary">{{ authInfo.name }}</h4>
    </div>

    <DeliveryBoyDashboardComponent v-if="authInfo.role_id === enums.roleEnum.DELIVERY_BOY" />

    <AdminDashboardComponent v-else />

</template>

<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import ENV from "../../../config/env";
import AdminDashboardComponent from "./admin/AdminDashboardComponent.vue";
import DeliveryBoyDashboardComponent from "./deliveryBoy/DeliveryBoyDashboardComponent.vue";
import roleEnum from "../../../enums/modules/roleEnum";

export default {
    name: "DashboardComponent",
    components: { LoadingComponent, AdminDashboardComponent, DeliveryBoyDashboardComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                roleEnum: roleEnum,
            }
        };
    },
    computed: {
        authInfo: function () {
            return this.$store.getters.authInfo;
        }
    },
    methods: {
        visitorMessage: function () {
            let greet;
            let myDate = new Date();
            let hrs = myDate.getHours();
            if (hrs < 12) {
                greet = this.$t('message.good_morning');
            } else if (hrs >= 12 && hrs <= 17) {
                greet = this.$t('message.good_afternoon');
            } else if (hrs >= 17 && hrs <= 24) {
                greet = this.$t('message.good_evening');
            }
            return greet;
        }
    }
}
</script>