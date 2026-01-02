<template>
    <LoadingComponent :props="loading" />
    <SmModalCreateComponent :props="addButton" />

    <div id="modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("menu.delivery_zones") }}</h3>
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
                            <label for="governorate_name" class="db-field-title required">
                                {{ $t("label.governorate") }}
                            </label>
                            <select v-model="props.form.governorate_name" v-bind:class="errors.governorate_name ? 'invalid' : ''"
                                id="governorate_name" class="db-field-control">
                                <option value="">{{ $t("label.select_governorate") }}</option>
                                <option v-for="gov in governorates" :key="gov" :value="gov">{{ gov }}</option>
                            </select>
                            <small class="db-field-alert" v-if="errors.governorate_name">
                                {{ errors.governorate_name[0] }}
                            </small>
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
                            <label for="phone" class="db-field-title">{{
                                $t("label.phone")
                                }}</label>
                            <input @input="props.form.phone = props.form.phone.replace(/\D/g, '').slice(0, 15)" v-model="props.form.phone" v-on:keypress="phoneNumber($event)" v-bind:class="errors.phone ? 'invalid' : ''" type="text"
                                id="phone" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.phone">{{
                                errors.phone[0]
                                }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="delivery_fee" class="db-field-title required">{{
                                $t("label.delivery_fee") }}</label>
                            <input v-model="props.form.delivery_fee"
                                v-bind:class="errors.delivery_fee ? 'invalid' : ''" type="text"
                                id="delivery_fee" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.delivery_fee">{{
                                errors.delivery_fee[0] }}</small>
                        </div>

                        <div class="form-col-12 sm:form-col-6">
                            <label for="minimum_order_amount" class="db-field-title required">{{
                                $t("label.minimum_order_amount") }}</label>
                            <input v-model="props.form.minimum_order_amount"
                                v-bind:class="errors.minimum_order_amount ? 'invalid' : ''" type="text"
                                id="minimum_order_amount" class="db-field-control" />
                            <small class="db-field-alert" v-if="errors.minimum_order_amount">{{
                                errors.minimum_order_amount[0] }}</small>
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
                            <label for="address" class="db-field-title">{{ $t("label.address") }}</label>
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
</template>
<script>
import SmModalCreateComponent from "../../components/buttons/SmModalCreateComponent.vue";
import LoadingComponent from "../../components/LoadingComponent.vue";
import statusEnum from "../../../../enums/modules/statusEnum";
import alertService from "../../../../services/alertService";
import composables from "../../../../composables/composables";
import appService from "../../../../services/appService";

export default {
    name: "DeliveryZoneCreateComponent",
    components: { SmModalCreateComponent, LoadingComponent },
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
            errors: {},
             governorates: [
                'القاهرة',
                'الجيزة',
                'الإسكندرية',
                'الدقهلية',
                'البحيرة',
                'الفيوم',
                'الغربية',
                'الإسماعيلية',
                'المنوفية',
                'المنيا',
                'القليوبية',
                'الوادي الجديد',
                'الشرقية',
                'سوهاج',
                'أسوان',
                'أسيوط',
                'بني سويف',
                'بورسعيد',
                'دمياط',
                'الأقصر',
                'قنا',
                'شمال سيناء',
                'جنوب سيناء',
                'كفر الشيخ',
                'مطروح',
                'البحر الأحمر'
            ]
        };
    },
    computed: {
        addButton: function () {
            return { title: this.$t('button.add_delivery_zone') };
        }
    },
    methods: {
        reset: function () {
            composables.closeModal('modal');
            this.$store.dispatch("deliveryZone/reset").then().catch();
            this.errors = {};
            if (this.$props && this.$props.props) {
                this.$props.props.form = {
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
                };
            }
        },
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        save: function () {
            try {
                const tempId = this.$store.getters["deliveryZone/temp"].temp_id;
                this.loading.isActive = true;
                this.$store.dispatch("deliveryZone/save", this.props).then((res) => {
                    composables.closeModal('modal');
                    this.loading.isActive = false;
                    alertService.successFlip(
                        tempId === null ? 0 : 1,
                        this.$t("menu.delivery_zones")
                    );
                    if (this.props && this.props.form) {
                        this.props.form = {
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
                        };
                    }
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