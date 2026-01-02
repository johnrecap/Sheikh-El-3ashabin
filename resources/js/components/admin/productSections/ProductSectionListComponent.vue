<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card db-tab-div active">
            <div class="db-card-header border-none">
                <h3 class="db-card-title">{{ $t("menu.product_sections") }}</h3>
                <div class="db-card-filter">
                    <TableLimitComponent @click.prevent="handlePaper" :method="list" :search="props.search"
                        :page="paginationPage" />
                    <FilterComponent @click.prevent="handleSlide('product-section-filter')" />
                    <div class="paper-group">
                        <ExportComponent @click.prevent="handlePaper" />
                        <div
                            class="paper-content absolute top-9 right-1/2 translate-x-1/2 z-30 min-w-[80px] w-fit rounded-md shadow-paper bg-white">
                            <PrintComponent :props="printObj" />
                            <ExcelComponent :method="xls" />
                        </div>
                    </div>
                    <ProductSectionCreateComponent :props="props" v-if="permissionChecker('product-sections_create')" />
                </div>
            </div>
            <div class="table-filter-div" id="product-section-filter">
                <form class="p-4 sm:p-5 mb-5 d-block w-full" @submit.prevent="search">
                    <div class="row">
                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchName" class="db-field-title after:hidden">{{
                                $t("label.name")
                            }}</label>
                            <input id="searchName" v-model="props.search.name" type="text" class="db-field-control" />
                        </div>

                        <div class="col-12 sm:col-6 md:col-4 xl:col-3">
                            <label for="searchStatus" class="db-field-title after:hidden">{{
                                $t("label.status")
                            }}</label>
                            <vue-select class="db-field-control f-b-custom-select" id="searchStatus"
                                v-model="props.search.status" :options="[
                                    { id: enums.statusEnum.ACTIVE, name: $t('label.active') },
                                    { id: enums.statusEnum.INACTIVE, name: $t('label.inactive') },
                                ]" label-by="name" value-by="id" :closeOnSelect="true" :searchable="true"
                                :clearOnClose="true" placeholder="--" search-placeholder="--" />
                        </div>
                        <div class="col-12">
                            <div class="flex flex-wrap gap-3 mt-4">
                                <button class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-line-search lab-font-size-16"></i>
                                    <span>{{ $t("button.search") }}</span>
                                </button>
                                <button class="db-btn py-2 text-white bg-gray-600" @click="clear">
                                    <i class="lab lab-line-cross lab-font-size-22"></i>
                                    <span>{{ $t("button.clear") }}</span>
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
                            <th class="db-table-head-th">{{ $t("label.name") }}</th>
                            <th class="db-table-head-th">{{ $t("label.status") }}</th>
                            <th class="db-table-head-th hidden-print"
                                v-if="permissionChecker('product-sections_show') || permissionChecker('product-sections_edit') || permissionChecker('product-sections_delete')">
                                {{ $t("label.action") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="db-table-body" v-if="productSections.length > 0">
                        <tr class="db-table-body-tr" v-for="productSection in productSections" :key="productSection">
                            <td class="db-table-body-td">
                                <div v-if="productSection.name.length < 40">{{ productSection.name }}</div>
                                <div v-else>{{ productSection.name.substring(0, 40) + ".." }}</div>
                            </td>
                            <td class="db-table-body-td">
                                <span :class="statusClass(productSection.status)">
                                    {{ enums.statusEnumArray[productSection.status] }}
                                </span>
                            </td>
                            <td class="db-table-body-td hidden-print"
                                v-if="permissionChecker('product-sections_show') || permissionChecker('product-sections_edit') || permissionChecker('product-sections_delete')">
                                <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                    <SmIconViewComponent :link="'admin.product-sections.show'" :id="productSection.id"
                                        v-if="permissionChecker('product-sections_show')" />
                                    <SmIconSidebarModalEditComponent @click="edit(productSection)"
                                        v-if="permissionChecker('product-sections_edit')" />
                                    <SmIconDeleteComponent @click="destroy(productSection.id)"
                                        v-if="permissionChecker('product-sections_delete')" />
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
import ProductSectionCreateComponent from "./ProductSectionCreateComponent.vue";
import alertService from "../../../services/alertService";
import PaginationTextComponent from "../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../components/pagination/PaginationSMBox.vue";
import appService from "../../../services/appService";
import statusEnum from "../../../enums/modules/statusEnum";
import TableLimitComponent from "../components/TableLimitComponent.vue";
import SmIconDeleteComponent from "../components/buttons/SmIconDeleteComponent.vue";
import SmIconSidebarModalEditComponent from "../components/buttons/SmIconSidebarModalEditComponent.vue";
import SmViewComponent from "../components/buttons/SmViewComponent.vue";
import SmSidebarModalEditComponent from "../components/buttons/SmSidebarModalEditComponent.vue";
import FilterComponent from "../components/buttons/collapse/FilterComponent.vue";
import ExportComponent from "../components/buttons/export/ExportComponent.vue";
import print from "vue3-print-nb";
import PrintComponent from "../components/buttons/export/PrintComponent.vue";
import ExcelComponent from "../components/buttons/export/ExcelComponent.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import SmIconViewComponent from "../components/buttons/SmIconViewComponent.vue";
import composables from "../../../composables/composables";

export default {
    name: "ProductSectionListComponent",
    components: {
        SmSidebarModalEditComponent,
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        ProductSectionCreateComponent,
        LoadingComponent,
        SmIconDeleteComponent,
        SmViewComponent,
        SmIconSidebarModalEditComponent,
        ExportComponent,
        FilterComponent,
        PrintComponent,
        ExcelComponent,
        Datepicker,
        SmIconViewComponent,
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
            printLoading: true,
            printObj: {
                id: "print",
                popTitle: this.$t("menu.product_sections"),
            },
            props: {
                form: {
                    name: "",
                    status: statusEnum.ACTIVE,
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                    name: "",
                    status: null,
                },
            },
            handlePaper: composables.handlePaper,
                handleSlide: composables.handleSlide,
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        productSections: function () {
            return this.$store.getters["productSection/lists"];
        },
        pagination: function () {
            return this.$store.getters["productSection/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["productSection/page"];
        },
    },
    methods: {
        permissionChecker(e) {
            return appService.permissionChecker(e);
        },
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        search: function () {
            this.list();
        },
        clear: function () {
            this.props.search.paginate = 1;
            this.props.search.page = 1;
            this.props.search.name = "";
            this.props.search.status = null;
            this.list();
        },
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch("productSection/lists", this.props.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (productSection) {
            appService.sideDrawerShow();
            this.loading.isActive = true;
            this.$store
                .dispatch("productSection/edit", productSection.id)
                .then((res) => {
                    this.loading.isActive = false;
                    this.props.errors = {};
                    this.props.form = {
                        name: productSection.name,
                        status: productSection.status,
                    };
                })
                .catch((err) => {
                    alertService.error(err.response.data.message);
                });
        },
        destroy: function (id) {
            appService
                .destroyConfirmation()
                .then((res) => {
                    try {
                        this.loading.isActive = true;
                        this.$store
                            .dispatch("productSection/destroy", { id: id, search: this.props.search })
                            .then((res) => {
                                this.loading.isActive = false;
                                alertService.successFlip(null, this.$t("menu.product_sections"));
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

        xls: function () {
            this.loading.isActive = true;
            this.$store
                .dispatch("productSection/export", this.props.search)
                .then((res) => {
                    this.loading.isActive = false;
                    const blob = new Blob([res.data], {
                        type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
                    });
                    const link = document.createElement("a");
                    link.href = URL.createObjectURL(blob);
                    link.download = this.$t("menu.product_sections");
                    link.click();
                    URL.revokeObjectURL(link.href);
                })
                .catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                });
        },
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
