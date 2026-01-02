<template>
    <LoadingComponent :props="loading" />
    <div class="db-card db-tab-div active">
        <div class="db-card-header border-none">
            <h3 class="db-card-title">{{ $t("label.address") }}</h3>
            <div class="db-card-filter">
                <TableLimitComponent :method="list" :search="address.search" :page="paginationPage" />
                <DeliveryBoyAddressCreateComponent :props="address" />
            </div>
        </div>

        <div class="db-table-responsive">
            <table class="db-table stripe">
                <thead class="db-table-head">
                    <tr class="db-table-head-tr">
                        <th class="db-table-head-th">{{ $t("label.label") }}</th>
                        <th class="db-table-head-th">{{ $t("label.full_address") }}</th>
                        <th class="db-table-head-th">{{ $t("label.governorate") }} / {{ $t("label.city") }}</th>
                        <th class="db-table-head-th">{{ $t("label.action") }}</th>
                    </tr>
                </thead>
                <tbody class="db-table-body" v-if="addresses.length > 0">
                    <tr class="db-table-body-tr" v-for="address in addresses" :key="address">
                        <td class="db-table-body-td">{{ address.label }}</td>
                        <td class="db-table-body-td">{{ address.full_address }}</td>
                        <td class="db-table-body-td text-sm text-gray-600">{{ address.governorate }} - {{ address.city }}</td>
                        <td class="db-table-body-td">
                            <div class="flex justify-start items-center sm:items-start sm:justify-start gap-1.5"> 
                                <button @click="edit(address)" class="db-table-action edit">
                                    <i class="lab lab-line-edit"></i>
                                    <span class="db-tooltip">{{ $t('button.edit') }}</span>
                                </button>
                                <SmIconDeleteComponent @click="destroy(address.id)" />
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
import alertService from "../../../../services/alertService";
import LoadingComponent from "../../components/LoadingComponent.vue";
import TableLimitComponent from "../../components/TableLimitComponent.vue";
import DeliveryBoyAddressCreateComponent from "./DeliveryBoyAddressCreateComponent.vue";
import appService from "../../../../services/appService";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent.vue";
import SmIconSidebarModalEditComponent from "../../components/buttons/SmIconSidebarModalEditComponent.vue";
import PaginationTextComponent from "../../components/pagination/PaginationTextComponent.vue";
import PaginationBox from "../../components/pagination/PaginationBox.vue";
import PaginationSMBox from "../../components/pagination/PaginationSMBox.vue";
import composables from "../../../../composables/composables";
import labelEnum from "../../../../enums/modules/labelEnum";

export default {
    name: "DeliveryBoyAddressList",
    components: {
        LoadingComponent,
        DeliveryBoyAddressCreateComponent,
        TableLimitComponent,
        SmIconDeleteComponent,
        SmIconSidebarModalEditComponent,
        PaginationSMBox,
        PaginationBox,
        PaginationTextComponent
    },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            address: {
                form: { governorate: "", city: "", street: "", building_number: "", apartment: "", label: "" },
                search: { paginate: 1, page: 1, per_page: 10, order_column: "id", order_type: "desc" },
                status: false,
                switchLabel: "",
            },
        }
    },
    mounted() {
        this.list();
    },
    computed: {
        addresses: function () {
            return this.$store.getters["deliveryBoyAddress/lists"];
        },
        pagination: function () {
            return this.$store.getters["deliveryBoyAddress/pagination"];
        },
        paginationPage: function () {
            return this.$store.getters["deliveryBoyAddress/page"];
        },
    },
    methods: {
        list: function (page = 1) {
            this.loading.isActive = true;
            this.address.search.page = page;
            this.$store.dispatch("deliveryBoyAddress/lists", {
                id: this.props,
                search: this.address.search
            }).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        edit: function (address) {
            composables.openModal('modal');
            this.loading.isActive = true;
            this.$store.dispatch("deliveryBoyAddress/edit", address.id).then((res) => {
                this.loading.isActive = false;
                this.address.form = { governorate: address.governorate, city: address.city, street: address.street, building_number: address.building_number, apartment: address.apartment, label: address.label };
                if (this.address.form.label === this.$t("label.home")) { this.address.status = false; this.address.switchLabel = labelEnum.HOME; }
                else if (this.address.form.label === this.$t("label.work")) { this.address.status = false; this.address.switchLabel = labelEnum.WORK; }
                else { this.address.status = true; this.address.switchLabel = labelEnum.OTHER; }
            }).catch((err) => { alertService.error(err.response.data.message); });
        },
        destroy: function (addressId) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("deliveryBoyAddress/destroy", {
                        id: this.props,
                        addressId: addressId,
                        search: this.address.search
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t("label.address"));
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
    }

}
</script>
