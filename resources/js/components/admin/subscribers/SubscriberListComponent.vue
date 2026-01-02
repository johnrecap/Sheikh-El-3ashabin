<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t("menu.subscribers") }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent @click.prevent="handlePaper" :method="list" :search="props.search"
                        :page="paginationPage" />
                    <FilterComponent @click.prevent="handleSlide('subscriber-filter')" />
                    <div class="paper-group">
                        <ExportComponent @click.prevent="handlePaper" />
                        <div
                            class="paper-content absolute top-9 right-1/2 translate-x-1/2 z-30 min-w-[80px] w-fit rounded-md shadow-paper bg-white">
                            <PrintComponent :props="printObj" />
                            <ExcelComponent :method="xls" />
                        </div>
                    </div>
                    <SubscriberMailComponent :props="props" v-if="permissionChecker('subscribers')" />
                </div>
            </div>
            <div class="table-filter-div" id="subscriber-filter">
                <form class="p-4 sm:p-5 mb-5 w-full d-block" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchEmail" class="db-field-title after:hidden">{{ $t('label.email') }}</label>
                            <input id="searchEmail" v-model="props.search.email" type="text" class="db-field-control">
                        </div>
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchStartDate" class="db-field-title after:hidden">{{
                                $t('label.date')
                            }}</label>
                            <Datepicker hideInputIcon autoApply :enableTimePicker="false" utc="false" 
                                @update:modelValue="handleDate" v-model="modelValue" :range="true" >
                            </Datepicker>
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
                            <th class="db-table-head-th">{{ $t("label.email") }}</th>
                            <th class="db-table-head-th">{{ $t("label.date") }}</th>
                            <th class="db-table-head-th hidden-print" v-if="permissionChecker('subscribers')">
                                {{ $t("label.action") }}</th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="subscribers.length > 0">
                        <tr class="db-table-body-tr" v-for="subscriber in subscribers" :key="subscriber">
                            <td class="db-table-body-td">{{ subscriber.email }}</td>
                            <td class="db-table-body-td">{{ subscriber.date_time }}</td>
                            <td class="db-table-body-td hidden-print" v-if="permissionChecker('subscribers')">
                                <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                    <SmIconDeleteComponent @click="destroy(subscriber.id)"
                                        v-if="permissionChecker('subscribers')" />
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
    </div>
</template>
<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import SubscriberMailComponent from "./SubscriberMailComponent.vue";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../components/pagination/PaginationSMBox.vue";
import appService from "../../../services/appService";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent.vue";
import FilterComponent from "../components/buttons/collapse/FilterComponent.vue";
import ExportComponent from "../components/buttons/export/ExportComponent.vue";
import print from 'vue3-print-nb';
import PrintComponent from "../components/buttons/export/PrintComponent.vue";
import ExcelComponent from "../components/buttons/export/ExcelComponent.vue";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import composables from "../../../composables/composables";
export default {
    name: "SubscriberListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        SubscriberMailComponent,
        LoadingComponent,
        SmIconDeleteComponent,
        ExportComponent,
        FilterComponent,
        PrintComponent,
        ExcelComponent,
        Datepicker,
        SmIconViewComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t('menu.subscribers')
            },
            props: {
                form: {
                    subject: "",
                    message: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                    email: "",
                    from_date: "",
                    to_date: "",
                },
            },
            modelValue: null,
            handlePaper: composables.handlePaper,
            handleSlide: composables.handleSlide,
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        subscribers: function () {
            return this.$store.getters["subscriber/lists"];
        },
        pagination: function () {
            return this.$store.getters["subscriber/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["subscriber/page"];
        },
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        search: function () {
            this.list();
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.email = "";
            this.props.search.from_date = "";
            this.props.search.to_date = "";
            this.modelValue = null;
            this.list();
        },
        handleDate: function (e) {
            if (e) {
                this.props.search.from_date = e[0];
                this.props.search.to_date = e[1];
            } else {
                this.props.search.from_date = null;
                this.props.search.to_date = null;

            }
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("subscriber/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        destroy: function (id) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("subscriber/destroy", {
                        id: id,
                        search: this.props.search,
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            null,
                            this.$t("menu.subscribers")
                        );
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },

        xls: function () {
            this.loading.isActive = true;
            this.$store.dispatch('subscriber/export', this.props.search).then(res => {
                this.loading.isActive = false;
                const blob = new Blob([res.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = this.$t("menu.subscribers");
                link.click();
                URL.revokeObjectURL(link.href);
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        }
    },
};

</script>
<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>
