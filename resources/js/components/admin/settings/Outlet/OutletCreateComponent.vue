<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.outlets") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="reset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            <label for="name" class="db-field-title required">{{
                                $t("label.name")
                            }}</label>
                            <input v-model="props.form.name" v-bind:class="errors.name ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.name">{{
                                errors.name[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="latitude">{{ $t("label.latitude") }}/{{
                                $t("label.longitude")
                            }}</label>
                            <div class="db-multiple-field">
                                <input v-model="props.form.latitude" v-bind:class="errors.latitude ? 'invalid' : ''
                                    " type="text" id="latitude" />
                                <input v-model="props.form.longitude" v-bind:class="errors.longitude ? 'invalid' : ''
                                    " type="text" id="longitude" />
                                <button @click="add" v-on:click="isMap = true" type="button"
                                    class="fa-solid fa-map-location-dot" data-modal="#outletMap"></button>
                            </div>

                            <small class="db-field-alert" v-if="errors.latitude">{{ errors.latitude[0] }}</small>
                            <small class="db-field-alert" v-if="errors.longitude">{{ errors.longitude[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="email" class="db-field-title">{{
                                $t("label.email")
                            }}</label>
                            <input v-model="props.form.email" v-bind:class="errors.email ? 'invalid' : ''" type="email"
                                id="email" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.email">{{
                                errors.email[0]
                            }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="phone" class="db-field-title">{{ $t("label.phone") }}</label>
                            <div :class="errors.phone ? 'invalid' : ''" class="db-field-control flex items-center">
                                <div class="w-fit flex-shrink-0 dropdown-group">
                                    <button type="button" class="flex items-center gap-1 dropdown-btn">
                                        {{ flag }}
                                        <span class="whitespace-nowrap flex-shrink-0 text-xs">
                                            {{ props.form.country_code }}
                                        </span>
                                        <input type="hidden" v-model="props.form.country_code">
                                    </button>
                                </div>
                                <input @input="props.form.phone = props.form.phone.replace(/\D/g, '').slice(0, 15)" v-model="props.form.phone" v-on:keypress="phoneNumber($event)" v-bind:class="errors.phone
                                    ? 'invalid' : ''" type="text" id="phone" class="pl-2 text-sm w-full h-full" />
                            </div>

                            <small class="db-field-alert" v-if="errors.phone">
                                {{ errors.phone[0] }}
                            </small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="delivery_zone_id" class="db-field-title required">
                                {{ $t("label.delivery_zone") }}
                            </label>
                            <vue-select class="db-field-control f-b-custom-select" id="delivery_zone_id" v-bind:class="errors.delivery_zone_id ? 'invalid' : ''"
                            v-model="props.form.delivery_zone_id" :options="deliveryZones" label-by="name"
                                value-by="id" :closeOnSelect="true" :searchable="true" :clearOnClose="true"
                                placeholder="--" search-placeholder="--" />
                            <small class="db-field-alert" v-if="errors.delivery_zone_id">
                                {{ errors.delivery_zone_id[0] }}
                            </small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required" for="active">{{ $t("label.status") }}</label>
                            <div class="db-field-radio-group">
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.ACTIVE" v-model="props.form.status" id="active"
                                            type="radio" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="active" class="db-field-label">{{ $t("label.active") }}</label>
                                </div>
                                <div class="db-field-radio">
                                    <div class="custom-radio">
                                        <input :value="enums.statusEnum.INACTIVE" v-model="props.form.status"
                                            type="radio" id="inactive" class="custom-radio-field" />
                                        <span class="custom-radio-span"></span>
                                    </div>
                                    <label for="inactive" class="db-field-label">{{ $t("label.inactive") }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-col-12">
                            <label for="address" class="db-field-title required">{{ $t("label.address") }}</label>
                            <textarea v-model="props.form.address" v-bind:class="errors.address ? 'invalid' : ''"
                                id="address" class="db-field-control"></textarea>
                            <small class="db-field-alert" v-if="errors.address">{{ errors.address[0] }}</small>
                        </div>

                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click="reset">
                                    <i class="lab lab-fill-close-circle"></i>
                                    <span>{{ $t("button.close") }}</span>
                                </button>

                                <button type="submit" class="db-btn py-2 text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="outletMap" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.address") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click="mapReset"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="save">
                    <div class="form-row">
                        <div class="form-col-12 map-height">
                            <MapComponent v-if="isMap"
                                :location="{ lat: props.form.latitude, lng: props.form.longitude }"
                                :position="location" />
                        </div>

                        <div class="form-col-12">
                            <label for="apartment" class="db-field-title font-medium text-sm my-0">
                                {{ address }}
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent.vue";
import LoadingComponent from "../../components/LoadingComponent.vue";
import statusEnum from "../../../../enums/modules/statusEnum";
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import composables from "../../../../composables/composables";
import MapComponent from "../../../admin/components/MapComponent.vue";

export default {
    name: "OutletCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent, MapComponent },
    props: ["props"],
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
            search: {
                paginate: 0,
                order_column: "id",
                order_type: "desc",
                status: statusEnum.ACTIVE,
            },
            errors: {},
            flag: "",
            country_code: "",
            isMap: false,
            address: "",
        };
    },
    mounted() {
        this.countryCodeList();
        this.deliveryZoneList();
    },
    computed: {
        addButton: function () {
            return { title: this.$t("button.add_outlet") }
        },
        deliveryZones: function () {
            return this.$store.getters["deliveryZone/lists"];
        },
    },
    methods: {
        add: function () {
            composables.openModal('outletMap');
        },
        location: function (e) {
            this.address = e.address;
            this.$props.props.form.latitude = e.location.lat;
            this.$props.props.form.longitude = e.location.lng;
            this.$props.props.form.address = e.address;
        },
        toggleDropdown() {
            this.isDropdownVisible = !this.isDropdownVisible;
        },
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        countryCodeList: function () {
            this.$store.dispatch('company/lists').then(companyRes => {
                this.$store.dispatch('countryCode/show', companyRes.data.data.company_country_code).then(res => {

                    if (this.props.form.country_code === "") {
                        this.props.form.country_code = res.data.data.calling_code;
                        this.country_code = res.data.data.calling_code;
                    }
                    this.flag = res.data.data.flag_emoji;
                    this.loading.isActive = false;

                }).catch((err) => {
                    this.loading.isActive = false;

                });
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        deliveryZoneList: function () {
            this.loading.isActive = true;
            this.$store.dispatch("deliveryZone/lists", this.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        mapReset: function () {
            this.isMap = false;
            composables.closeModal('outletMap');

        },
        reset: function () {
            composables.closeModal('modal');
            this.$store.dispatch("outlet/reset").then().catch();
            this.errors = {};
            this.$props.props.form = {
                name: "",
                email: "",
                country_code: this.country_code,
                phone: "",
                latitude: "",
                longitude: "",
                delivery_zone_id: null,
                address: "",
                status: statusEnum.ACTIVE,
            };
            this.isMap = false;
        },
        save: function () {
            try {
                const tempId = this.$store.getters["outlet/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("outlet/save", this.props).then((res) => {
                    composables.closeModal('modal');
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("menu.outlets")
                    );
                    this.props.form = {
                        name: "",
                        email: "",
                        country_code: "",
                        phone: "",
                        latitude: "",
                        longitude: "",
                        delivery_zone_id: null,
                        address: "",
                        status: statusEnum.ACTIVE,
                    };
                    this.isMap = false;
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
    },
};
</script>