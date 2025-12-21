<template>
    <LoadingComponent :props="loading" />
    <div class="row">
        <div class="col-12">
            <BreadcrumbComponent />
        </div>

        <div class="col-12">
            <div class="db-card db-tab-div active">
                <div class="db-card-header border-none">
                    <h3 class="db-card-title">{{ $t('menu.credit_balance_report') }}</h3>
                    <div class="db-card-filter">
                    <TableLimitComponent @click.prevent="handlePaper" :method="list" :search="props.search"
                        :page="paginationPage" />
                    <FilterComponent @click.prevent="handleSlide('credit-balance-filter')" />
                    <div class="paper-group">
                        <ExportComponent @click.prevent="handlePaper" />
                        <div
                            class="paper-content absolute top-9 right-1/2 translate-x-1/2 z-30 min-w-[80px] w-fit rounded-md shadow-paper bg-white">
                                <PrintComponent :props="printObj" />
                                <ExcelComponent :method="xls" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-filter-div" id="credit-balance-filter">
                    <form class="p-4 sm:p-5 mb-5 w-full d-block" @submit.prevent="search">
                        <div class="row">
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label for="searchName" class="db-field-title after:hidden">{{
                                    $t("label.name")
                                }}</label>
                                <input id="searchName" v-model="props.search.name" type="text" class="db-field-control" />
                            </div>
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label for="searchEmail" class="db-field-title after:hidden">{{
                                    $t("label.email")
                                }}</label>
                                <input id="searchEmail" v-model="props.search.email" type="text" class="db-field-control" />
                            </div>
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label for="searchPhone" class="db-field-title after:hidden">{{
                                    $t("label.phone")
                                }}</label>
                                <input id="searchPhone" v-model="props.search.phone" v-on:keypress="phoneNumber($event)"
                                    type="text" class="db-field-control" />
                            </div>
                            <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                                <label for="role_id" class="db-field-title">{{
                                    $t("label.role")
                                }}</label>

                                <vue-select class="db-field-control f-b-custom-select" id="role_id"
                                    v-model="props.search.role_id" :options="roles" label-by="name" value-by="id"
                                    :closeOnSelect="true" :searchable="true" :clearOnClose="true" placeholder="--"
                                    search-placeholder="--" />
                            </div>
                            <div class="col-12">
                                <div class="flex flex-wrap gap-3 mt-4">
                                    <button class="db-btn py-2 text-white bg-primary">
                                        <i class="lab lab-line-search lab-font-size-16"></i>
                                        <span>{{ $t('button.search') }}</span>
                                    </button>
                                    <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                        <i class="lab lab-line-cross lab-font-size-22"></i>
                                        <span>{{ $t('button.clear') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="db-table-responsive">
                    <table class="db-table stripe" id="print">
                        <thead class="db-table-head">
                            <tr class="db-table-head-tr">
                                <th class="db-table-head-th">{{ $t('label.name') }}</th>
                                <th class="db-table-head-th">{{ $t('label.email') }}</th>
                                <th class="db-table-head-th">{{ $t('label.phone') }}</th>
                                <th class="db-table-head-th">{{ $t('label.balance') }}</th>
                            </tr>
                        </thead>
                        <tbody class="db-table-body" v-if="creditBalanceReports.length > 0">
                            <tr class="db-table-body-tr" v-for="user in creditBalanceReports" :key="user">
                                <td class="db-table-body-td">{{ user.name }}</td>
                                <td class="db-table-body-td">{{ user.email }}</td>
                                <td class="db-table-body-td">{{ user.country_code + '' + user.phone }}</td>
                                <td class="db-table-body-td">{{ user.balance }}</td>

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
        </div>
    </div>
</template>

<script>
import BreadcrumbComponent from "../components/BreadcrumbComponent.vue";
import LoadingComponent from "../components/LoadingComponent.vue";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../components/pagination/PaginationSMBox.vue";
import appService from "../../../services/appService";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import FilterComponent from "../components/buttons/collapse/FilterComponent.vue";
import ExportComponent from "../components/buttons/export/ExportComponent.vue";
import print from 'vue3-print-nb';
import PrintComponent from "../components/buttons/export/PrintComponent.vue";
import ExcelComponent from "../components/buttons/export/ExcelComponent.vue";
import "@vuepic/vue-datepicker/dist/main.css";
import composables from "../../../composables/composables";
export default {
    name: "CreditBalanceReportComponent",
    components: {
        BreadcrumbComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        LoadingComponent,
        ExportComponent,
        FilterComponent,
        PrintComponent,
        ExcelComponent,
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t('menu.credit_balance_report')
            },
            props: {
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: 'id',
                    name: "",
                    email: "",
                    phone: "",
                    role_id: null,
                }
            },
            handlePaper: composables.handlePaper,
            handleSlide: composables.handleSlide,
        }
    },
    mounted() {
        this.list();
        this.$store.dispatch("role/lists", {
            order_column: "id",
            order_type: "asc",
        });
    },
    computed: {
        creditBalanceReports: function () {
            return this.$store.getters['creditBalanceReport/lists'];
        },
        pagination: function () {
            return this.$store.getters['creditBalanceReport/pagination'];
        },
        paginationPage: function () {
            return this.$store.getters['creditBalanceReport/page'];
        },
        roles: function () {
            return this.$store.getters["role/lists"];
        },
    },
    methods: {
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        search: function () {
            this.list();
        },

        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.props.search.email = "";
            this.props.search.phone = "";
            this.props.search.role_id = null;
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('creditBalanceReport/lists', this.props.search).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },

        xls: function () {
            this.loading.isActive = true;
            this.$store.dispatch('creditBalanceReport/export', this.props.search).then(res => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = this.$t("menu.credit_balance_report");
                link.click();
                URL.revokeObjectURL(link.href);
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        }
    }
}
</script>

<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
