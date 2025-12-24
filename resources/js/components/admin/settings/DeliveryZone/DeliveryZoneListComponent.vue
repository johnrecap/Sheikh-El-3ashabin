<template>
    <LoadingComponent :props="loading" />

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t("menu.delivery_zones") }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                <DeliveryZoneCreateComponent :props="props" />
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t("label.name") }}</th>
                        <th class="db-table-head-th">
                            {{ $t("label.status") }}
                        </th>
                        <th class="db-table-head-th">
                            {{ $t("label.action") }}
                        </th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="deliveryZones.length > 0">
                    <tr class="db-table-body-tr" v-for="deliveryZone in deliveryZones" :key="deliveryZone">
                        <td class="db-table-body-td">
                            {{ deliveryZone.name }}
                        </td>
                        <td class="db-table-body-td">
                            <span :class="statusClass(deliveryZone.status)">
                                {{ enums.statusEnumArray[deliveryZone.status] }}
                            </span>
                        </td>
                        <td class="db-table-body-td">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <SmViewComponent :link="'admin.settings.deliveryZone.show'" :id="deliveryZone.id" />
                                <SmModalEditComponent @click="edit(deliveryZone)" />
                                <SmDeleteComponent @click="destroy(deliveryZone.id)" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-6">
            <PaginationSMBox :pagination="pagination" :method="list" />
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <PaginationTextComponent :props="{ page: paginationPage }" />
                <PaginationBox :pagination="pagination" :method="list" />
            </div>
        </div>
    </div>
</template>
<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import DeliveryZoneCreateComponent from "./DeliveryZoneCreateComponent.vue";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../../components/pagination/PaginationSMBox.vue";
import appService from "../../../../services/appService";
import statusEnum from "../../../../enums/modules/statusEnum";
import TableLimitComponent from "../../components/TableLimitComponent.vue";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent.vue";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent.vue";
import SmViewComponent from "../../components/buttons/SmViewComponent.vue";
import composables from "../../../../composables/composables";

export default {
    name: "DeliveryZoneListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        DeliveryZoneCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
        SmViewComponent,
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
            props: {
                form: {
                    name: "",
                    governorate_name: "",
                    email: "",
                    phone: "",
                    latitude: "",
                    longitude: "",
                    delivery_radius_kilometer: "",
                    delivery_charge_per_kilo: "",
                    delivery_fee: "",
                    minimum_order_amount: "",
                    address: "",
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                },
            },
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        deliveryZones: function () {
            return this.$store.getters["deliveryZone/lists"];
        },
        pagination: function () {
            return this.$store.getters["deliveryZone/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["deliveryZone/page"];
        },
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("deliveryZone/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (deliveryZone) {
            composables.openModal('modal');
            this.loading.isActive = true;
            this.$store.dispatch("deliveryZone/edit", deliveryZone.id);
            this.props.form = {
                name: deliveryZone.name,
                governorate_name: deliveryZone.governorate_name,
                email: deliveryZone.email,
                phone: deliveryZone.phone,
                latitude: deliveryZone.latitude,
                longitude: deliveryZone.longitude,
                delivery_radius_kilometer: deliveryZone.delivery_radius_kilometer,
                delivery_charge_per_kilo: deliveryZone.delivery_charge_per_kilo,
                delivery_fee: deliveryZone.delivery_fee,
                minimum_order_amount: deliveryZone.minimum_order_amount,
                address: deliveryZone.address,
                status: deliveryZone.status,
            };
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService
                .destroyConfirmation()
                .then((res) => {
                    try {
                        this.loading.isActive = true;
                        this.$store
                            .dispatch("deliveryZone/destroy", {
                                id: id,
                                search: this.props.search,
                            })
                            .then((res) => {
                                this.loading.isActive = false;
                                alertService.successFlip(null, this.$t("menu.delivery_zones"));
                            })
                            .catch((err) => {
                                this.loading.isActive = false;
                                alertService.error(err.response.data.message);
                            });
                    } catch (err) {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    }
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        },
    },
};
</script>