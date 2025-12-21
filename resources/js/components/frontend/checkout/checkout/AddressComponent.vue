<template>
    <LoadingComponent :props="loading" />
    <div v-if="show" class="mb-6 rounded-2xl shadow-card">
        <div class="flex flex-wrap items-center justify-between gap-3 p-4 border-b border-gray-100">
            <h4 class="font-bold capitalize">{{ $t('label.delivery_information') }}</h4>
            <div class="flex flex-wrap items-center gap-4">
                <button v-if="Object.keys(selectedAddress).length > 0" type="button"
                    @click.prevent="edit(selectedAddress)"
                    class="px-3 h-8 leading-8 rounded-full flex items-center gap-2 bg-[#E6FFF0] text-success">
                    <i class="lab-fill-edit"></i>
                    <span class="text-sm font-medium capitalize whitespace-nowrap">{{ $t('button.edit') }}</span>
                </button>
                <button data-modal="#checkoutAddress" @click="add" v-on:click="this.address.isMap = true" type="button"
                    class="px-3 h-8 leading-8 rounded-full flex items-center gap-2 bg-primary/10 text-primary">
                    <i class="lab-fill-circle-plus"></i>
                    <span class="text-sm font-medium capitalize whitespace-nowrap">{{ $t('button.add_new') }}</span>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4">

            <div :class="Object.keys(selectedAddress).length > 0 && address.id === selectedAddress.id ? 'border-primary/50 bg-primary/5' : 'border-[#F7F7F7] bg-[#F7F7F7]'"
                @click.prevent="activeAddress(address)" v-for="address in addresses" :key="address"
                class="py-3 px-4 rounded-lg cursor-pointer border transition-all duration-300">
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-sm font-medium capitalize whitespace-nowrap overflow-hidden text-ellipsis">
                        {{ address.label }} </h3>
                </div>
                <p class="text-sm leading-6">
                    {{ address.apartment ? address.apartment + ', ' : '' }}
                    {{ address.address }}
                </p>
            </div>
        </div>
    </div>

    <div id="checkoutAddress" class="modal address ff-modal">
        <div class="modal-dialog">
            <div class="modal-header border-none pb-0">
                <h3 class="capitalize font-medium">{{ $t('label.your_address') }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <MapComponent :key="mapKey" v-if="address.isMap"
                        :location="{ lat: address.form.latitude, lng: address.form.longitude }" :position="location" />
                    <div class="flex items-center gap-2 mb-3">
                        <i class="lab lab-fill-location text-xl text-primary"></i>
                        <span class="text-sm text-heading">{{ address.form.address }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="apartment" class="text-xs leading-6 capitalize mb-1 text-heading">{{
                            $t('label.apartment_and_flat')
                            }}</label>
                        <textarea id="apartment" v-model="address.form.apartment"
                            class="h-12 w-full rounded-lg border py-1.5 px-2 placeholder:text-[10px] placeholder:text-[#6E7191] border-[#D9DBE9]"></textarea>
                    </div>
                    <div class="mb-6">
                        <h3 class="capitalize font-medium mb-2">{{ $t('label.add_label') }}</h3>
                        <nav class="flex flex-wrap gap-3 active-group">
                            <button @click="changeSwitchLabel(labelEnum.HOME)"
                                :class="address.switchLabel === labelEnum.HOME ? 'active' : ''"
                                v-on:click="this.address.status = false; this.address.form.label = $t('label.home')"
                                :value="labelEnum.HOME" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-fill-home text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">{{
                                    $t('label.home')
                                    }}</span>
                            </button>
                            <button @click="changeSwitchLabel(labelEnum.WORK)"
                                :class="address.switchLabel === labelEnum.WORK ? 'active' : ''"
                                v-on:click="this.address.status = false; this.address.form.label = $t('label.work')"
                                :value="labelEnum.WORK" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-fill-briefcase text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">
                                    {{ $t('label.work') }}
                                </span>
                            </button>
                            <button @click="changeSwitchLabel(labelEnum.OTHER)"
                                :class="address.switchLabel === labelEnum.OTHER ? 'active' : ''"
                                v-on:click="this.address.status = true; this.address.form.label = ''; this.errors.label = ''"
                                :value="labelEnum.OTHER" type="button"
                                class="flex items-center gap-2 rounded-lg p-4 border bg-[#F7F7FC] border-[#F7F7FC]">
                                <i class="lab lab-more-square text-base leading-none"></i>
                                <span class="text-sm capitalize font-medium leading-none text-heading">{{
                                    $t('label.other')
                                    }}</span>
                            </button>
                        </nav>
                        <small class="db-field-alert" v-if="errors.label && address.switchLabel !== labelEnum.OTHER">{{
                            errors.label[0]
                            }}</small>
                        <div v-if="address.status" :class="!address.status ? 'h-0' : ''"
                            class="overflow-hidden transition">
                            <input type="text" :placeholder="$t('label.type_label_name')" v-model="address.form.label"
                                v-bind:class="errors.label ? 'invalid' : ''"
                                class="h-10 w-full rounded-lg border mt-5 py-1.5 px-4 placeholder:text-xs border-[#D9DBE9]">
                            <small class="db-field-alert" v-if="errors.label">{{ errors.label[0] }}</small>
                        </div>
                    </div>
                    <button type="submit"
                        class="rounded-3xl text-base py-3 px-3 font-medium w-full text-white bg-primary">
                        {{ $t('button.confirm_location') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
import orderTypeEnum from "../../../../enums/modules/orderTypeEnum";
import labelEnum from "../../../../enums/modules/labelEnum";
import appService from "../../../../services/appService";
import alertService from "../../../../services/alertService";
import LoadingComponent from "../../components/LoadingComponent.vue";
import MapComponent from "../../components/MapComponent.vue";


export default {
    name: "AddressComponent",
    components: { LoadingComponent, MapComponent },
    props: {
        "show": { type: Boolean, Default: false },
        "selectedAddress": { type: Object },
        "method": { type: Function }
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            mapKey: "create-update",
            orderTypeEnum: orderTypeEnum,
            labelEnum: labelEnum,
            address: {
                form: {
                    address: "",
                    apartment: "",
                    latitude: "",
                    longitude: "",
                    label: "",
                },
                search: {
                    paginate: 0,
                    order_column: "id",
                    order_type: "asc",

                },
                status: false,
                switchLabel: "",
                isMap: false,
            },
            activeAddressId: null,
            errors: {},
        }
    },
    computed: {
        addresses: function () {
            return this.$store.getters["frontendAddress/lists"];
        },
        countryCodes: function () {
            return this.$store.getters['frontendCountryCode/lists'];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendAddress/lists", {
            search: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
            }
        }).then((res) => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        add: function () {
            this.address.isMap = true;
            appService.modalShow("#checkoutAddress");
        },
        changeSwitchLabel: function (id) {
            this.address.switchLabel = id;
        },
        reset: function () {
            appService.modalHide('#checkoutAddress');
            this.$store.dispatch("frontendAddress/reset").then().catch();
            this.errors = {};
            this.address.form = {
                address: "",
                apartment: "",
                latitude: "",
                longitude: "",
                label: "",
            };
            this.address.status = false;
            this.address.switchLabel = "";
            this.address.isMap = false;
        },
        location: function (e) {
            this.address.form.latitude = e.location.lat;
            this.address.form.longitude = e.location.lng;
            this.address.form.address = e.address;
        },
        activeAddress: function (address) {
            this.activeAddressId = address.id;
            this.method(address);
        },
        save: function () {
            try {
                const tempId = this.$store.getters["frontendAddress/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("frontendAddress/save", this.address).then((res) => {
                    appService.modalHide('#checkoutAddress');
                    this.loading.isActive = false;
                    alertService.successFlip(tempId === null ? 0 : 1, this.$t("label.address"));
                    this.address.form = {
                        address: "",
                        apartment: "",
                        latitude: "",
                        longitude: "",
                        label: "",
                    };
                    this.errors = {};
                    this.activeAddress(res.data.data);
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        edit: function (address) {
            appService.modalShow("#checkoutAddress");
            this.loading.isActive = true;
            this.$store.dispatch("frontendAddress/edit", address.id).then((res) => {
                this.loading.isActive = false;
                this.address.isMap = true;
                this.address.form = {
                    address: address.address,
                    apartment: address.apartment,
                    latitude: address.latitude,
                    longitude: address.longitude,
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
    }
}
</script>