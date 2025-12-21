<template>
    <LoadingComponent :props="loading" />
    <div class="row mb-9">
        <div class="col-12">
            <h4 class="font-semibold text-xl mb-3 capitalize text-heading">{{ $t("menu.active_orders") }}</h4>
            <div class="db-card rounded-lg">
                <div class="db-table-responsive rounded-b-lg">
                    <table class="db-table stripe" id="print">
                        <thead class="db-table-head border-t-0">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th pt-6 pb-6">{{ $t('label.order_id') }}</th>
                                <th class="db-table-head-th">{{ $t('label.order_type') }}</th>
                                <th class="db-table-head-th">{{ $t('label.customer') }}</th>
                                <th class="db-table-head-th">{{ $t('label.amount') }}</th>
                                <th class="db-table-head-th">{{ $t('label.date') }}</th>
                                <th class="db-table-head-th">{{ $t('label.status') }}</th>
                                <th class="db-table-head-th hidden-print">
                                    {{ $t('label.action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body" v-if="orders.length > 0">
                            <tr class="db-table-body-tr" v-for="order in orders" :key="order">
                                <td class="db-table-body-td">
                                    {{ order.order_serial_no }}

                                </td>
                                <td class="db-table-body-td">
                                    <span :class="statusClass(order.order_type)">
                                        {{ enums.orderTypeEnumArray[order.order_type] }}
                                    </span>
                                </td>

                                <td class="db-table-body-td">
                                    {{ textShortener(order.user.name, 20) }}
                                </td>
                                <td class="db-table-body-td">{{ order.total_amount_price }}</td>
                                <td class="db-table-body-td">
                                    {{ order.order_datetime }}
                                </td>
                                <td class="db-table-body-td">
                                    <span class="db-table-badge" :class="orderStatusClass(order.status)">
                                        {{ enums.orderStatusEnumArray[order.status] }}
                                    </span>
                                </td>
                                <td class="db-table-body-td hidden-print">
                                    <div
                                        class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                        <SmIconViewComponent :link="'admin.dashboard.activeOrder.show'"
                                            :id="order.id" />
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../../components/pagination/PaginationSMBox.vue";
import orderStatusEnum from "../../../../enums/modules/orderStatusEnum";
import orderTypeEnum from "../../../../enums/modules/orderTypeEnum";
import appService from "../../../../services/appService";
import SmIconViewComponent from "../../components/buttons/SmIconViewComponent.vue";
export default {
    name: "OrderListComponent",
    components: { LoadingComponent, PaginationTextComponent, PaginationBox, PaginationSMBox, SmIconViewComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            enums: {
                orderStatusEnum: orderStatusEnum,
                orderTypeEnum: orderTypeEnum,
                orderStatusEnumArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.pending"),
                    [orderStatusEnum.CONFIRMED]: this.$t("label.confirmed"),
                    [orderStatusEnum.ON_THE_WAY]: this.$t("label.on_the_way"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                    [orderStatusEnum.CANCELED]: this.$t("label.canceled"),
                    [orderStatusEnum.REJECTED]: this.$t("label.rejected"),
                    [orderStatusEnum.RETURNED]: this.$t("label.returned"),
                },
                orderTypeEnumArray: {
                    [orderTypeEnum.DELIVERY]: this.$t("label.delivery"),
                    [orderTypeEnum.PICK_UP]: this.$t("label.pick_up")
                }
            },
            props: {
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 3,
                    order_column: 'id',
                    order_by: "desc",
                    excepts: orderStatusEnum.DELIVERED + '|' + orderStatusEnum.RETURNED,
                }
            },
        };
    },
    computed: {
        orders: function () {
            return this.$store.getters['deliveryBoyDashboard/lists'];
        },
        pagination: function () {
            return this.$store.getters['deliveryBoyDashboard/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['deliveryBoyDashboard/page'];
        }
    },
    mounted() {
        this.list();
    },
    methods: {
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('deliveryBoyDashboard/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
    },
}
</script>