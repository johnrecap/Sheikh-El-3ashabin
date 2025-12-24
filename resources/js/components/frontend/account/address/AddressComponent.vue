<template>
    <h2 class="capitalize text-2xl font-bold mb-7 text-primary">{{ $t('menu.addresses') }}</h2>
    <div class="p-6 rounded-2xl shadow-card bg-white">
        <div class="row">

            <div class="col-12 sm:col-6 lg:col-4" v-if="addresses.length > 0" v-for="address in addresses" :key="address">
                <div class="py-3 px-4 rounded-2xl bg-[#F7F7F7]">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-sm font-medium capitalize whitespace-nowrap overflow-hidden text-ellipsis">
                            {{ address.label }} </h3>
                        <div class="group relative"><button type="button"
                                class="lab-line-circle-more text-primary"></button>
                            <nav
                                class="w-20 rounded-lg shadow-paper absolute top-5 right-0 z-10 origin-top scale-y-0 transition-all duration-300 group-hover:scale-y-100 bg-white">
                                <button data-modal="#address" type="button" @click="edit(address)"
                                    class="text-sm font-semibold capitalize py-2 px-3 text-left w-full block border-b last:border-b-0 border-gray-200">
                                    {{ $t("button.edit") }}</button>
                                <button type="button" @click="destroy(address.id)"
                                    class="text-sm font-semibold capitalize py-2 px-3 text-left w-full block border-b last:border-b-0 border-gray-200">
                                    {{ $t("button.delete") }}</button>
                            </nav>
                        </div>
                    </div>
                    <p class="text-sm leading-6 text-gray-700">
                        {{ address.full_address }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ address.governorate }} - {{ address.city }}
                    </p>
                </div>
            </div>
            <div class="col-12 sm:col-6 lg:col-4">
                <AddressCreateComponent :getLocation="updateAddress" :props="address" />
            </div>
        </div>
    </div>
</template>


<script>
import alertService from "../../../../services/alertService";
import AddressCreateComponent from "./AddressCreateComponent.vue";
import appService from "../../../../services/appService";
import labelEnum from "../../../../enums/modules/labelEnum";
import LoadingComponent from "../../components/LoadingComponent.vue";

export default {
    name: "AddressComponent",
    components: {
        LoadingComponent,
        AddressCreateComponent,
    },
    props: ["props"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            localAddress: {},
            address: {
                form: {
                    governorate: "",
                    city: "",
                    street: "",
                    building_number: "",
                    apartment: "",
                    label: "",
                },
                search: {
                    paginate: 0,
                    order_column: "id",
                    order_type: "asc",
                },
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
            return this.$store.getters["frontendAddress/lists"];
        },
    },
    methods: {
        list: function () {
            this.loading.isActive = true;
            this.$store.dispatch("frontendAddress/lists", {
                search: this.address.search
            }).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        updateAddress: function (address) {
            this.localAddress = address;
        },
        edit: function (address) {
            appService.modalShow("#address");
            this.loading.isActive = true;
            this.$store.dispatch("frontendAddress/edit", address.id).then((res) => {
                this.loading.isActive = false;
                this.address.form = {
                    governorate: address.governorate,
                    city: address.city,
                    street: address.street,
                    building_number: address.building_number,
                    apartment: address.apartment,
                    label: address.label,
                };
                if (this.address.form.label === this.$t("label.home")) {
                    this.address.status = false;
                    this.address.switchLabel = labelEnum.HOME;
                } else if (this.address.form.label === this.$t("label.work")) {
                    this.address.status = false;
                    this.address.switchLabel = labelEnum.WORK;
                } else {
                    this.address.status = true;
                    this.address.switchLabel = labelEnum.OTHER;
                }
            }).catch((err) => {
                alertService.error(err.response.data.message);
            });
        },
        destroy: function (addressId) {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("frontendAddress/destroy", {
                        id: addressId,
                        search: this.address.search,
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
