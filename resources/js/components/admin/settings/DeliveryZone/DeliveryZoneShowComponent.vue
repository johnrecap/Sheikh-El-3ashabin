<template>
    <LoadingComponent :props="loading" />

    <div class="db-card">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ $t("label.delivery_zone") }}</h3>
        </div>
        <div class="db-card-body">
            <div class="row">
                <div class="col-12">
                    <ul class="db-list single">
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.name")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.name
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.latitude")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.latitude
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.longitude")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.longitude
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.email")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.email
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.phone")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.phone
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.delivery_radius_kilometer")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.delivery_radius_kilometer
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.delivery_charge_per_kilo")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.delivery_charge_per_kilo
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.minimum_order_amount")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.minimum_order_amount
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.address")
                                }}</span>
                            <span class="db-list-item-text">{{
                                deliveryZone.address
                                }}</span>
                        </li>
                        <li class="db-list-item">
                            <span class="db-list-item-title">{{
                                $t("label.status")
                                }}</span>
                            <span class="db-list-item-text">
                                <span :class="statusClass(deliveryZone.status)" class="db-badge">{{
                                    enums.statusEnumArray[deliveryZone.status]
                                    }}</span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import statusEnum from "../../../../enums/modules/statusEnum";
import appService from "../../../../services/appService";

export default {
    name: "DeliveryZoneShowComponent",
    components: {
        LoadingComponent,
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                statusEnum: statusEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
            },
        };
    },
    computed: {
        deliveryZone: function () {
            return this.$store.getters["deliveryZone/show"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store
            .dispatch("deliveryZone/show", this.$route.params.id)
            .then((res) => {
                this.loading.isActive = false;
            })
            .catch((error) => {
                this.loading.isActive = false;
            });
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
    },
};
</script>