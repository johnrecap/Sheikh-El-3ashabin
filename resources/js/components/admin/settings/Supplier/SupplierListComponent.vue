<template>
    <LoadingComponent :props="loading" />

    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t("menu.suppliers") }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="props.search" :page="paginationPage" />
                <SupplierCreateComponent :props="props" />
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t("label.company") }}</th>
                        <th class="db-table-head-th">{{ $t("label.name") }}</th>
                        <th class="db-table-head-th">{{ $t("label.email") }}</th>
                        <th class="db-table-head-th">{{ $t("label.phone") }}</th>
                        <th class="db-table-head-th">
                            {{ $t("label.action") }}
                        </th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="suppliers.length > 0">
                    <tr class="db-table-body-tr" v-for="supplier in suppliers" :key="supplier">
                        <td class="db-table-body-td">
                            {{ supplier.company }}
                        </td>
                        <td class="db-table-body-td">
                            {{ supplier.name }}
                        </td>
                        <td class="db-table-body-td">
                            {{ supplier.email }}
                        </td>
                        <td class="db-table-body-td">
                            {{ supplier.phone }}
                        </td>
                        <td class="db-table-body-td">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5">
                                <SmViewComponent :link="'admin.settings.supplier.show'" :id="supplier.id" />
                                <SmModalEditComponent @click="edit(supplier)" />
                                <SmDeleteComponent @click="destroy(supplier.id)" />
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
import SupplierCreateComponent from "./SupplierCreateComponent.vue";
import alertService from "../../../../services/alertService";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../../components/pagination/PaginationSMBox.vue";
import appService from "../../../../services/appService";
import TableLimitComponent from "../../components/TableLimitComponent.vue";
import SmDeleteComponent from "../../components/buttons/SmDeleteComponent.vue";
import SmModalEditComponent from "../../components/buttons/SmModalEditComponent.vue";
import SmViewComponent from "../../components/buttons/SmViewComponent.vue";
import composables from "../../../../composables/composables";

export default {
    name: "SupplierListComponent",
    components: {
        TableLimitComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent,
        SupplierCreateComponent,
        LoadingComponent,
        SmDeleteComponent,
        SmModalEditComponent,
        SmViewComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                form: {
                    company: "",
                    name: "",
                    email: "",
                    phone: "",
                    country_code: "",
                    address: "",
                },
                search: {
                    paginate: 1,
                    page: 1,
                    per_page: 10,
                    order_column: "id",
                    order_type: "desc",
                },
                flag: "",
            },
        };
    },
    mounted() {
        this.list();
    },
    computed: {
        suppliers: function () {
            return this.$store.getters["supplier/lists"];
        },
        pagination: function () {
            return this.$store.getters["supplier/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["supplier/page"];
        },
    },
    methods: {
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store
                .dispatch("supplier/lists", this.props.search)
                .then((res) => {
                    this.loading.isActive = false;
                })
                .catch((err) => {
                    this.loading.isActive = false;
                });
        },
        edit: async function (supplier) { 
            composables.openModal('modal');
            this.loading.isActive = true;
            this.$store.dispatch("supplier/edit", supplier.id);


            setTimeout(() => {
                this.props.form = {
                    company: supplier.company,
                    name: supplier.name,
                    email: supplier.email,
                    phone: supplier.phone,
                    country_code: supplier.country_code,
                    address: supplier.address,
                };

            }, 200);

            this.$store.dispatch('countryCode/callingCode', supplier.country_code).then(res => {
                this.props.flag = res.data.data.flag_emoji;
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
            this.loading.isActive = false;
        },
        destroy: function (id) {
            appService
                .destroyConfirmation()
                .then((res) => {
                    try {
                        this.loading.isActive = true;
                        this.$store
                            .dispatch("supplier/destroy", {
                                id: id,
                                search: this.props.search,
                            })
                            .then((res) => {
                                this.loading.isActive = false;
                                alertService.successFlip(
                                    null,
                                    this.$t("menu.suppliers")
                                );
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
