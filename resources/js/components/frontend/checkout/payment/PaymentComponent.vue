<template>
    <LoadingComponent :props="loading" />
    <div class="row">
        <div class="col-12 lg:col-8">
            <div class="mb-6 rounded-2xl shadow-card">
                <h4 class="font-bold capitalize p-4 border-b border-gray-100">
                    {{ $t('label.select_payment_method') }}
                </h4>

                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 p-4">
                    <div v-if="Object.keys(cashOnDelivery).length > 0 && setting.site_cash_on_delivery === ActivityEnum.ENABLE"
                        @click.prevent="selectPaymentMethod(cashOnDelivery)"
                        :class="Object.keys(paymentMethod).length > 0 && cashOnDelivery.id === paymentMethod.id ? 'border-primary/50 bg-[#E7FFF3]' : 'border-white bg-white'"
                        class="flex flex-col items-center justify-center gap-2.5 py-4 rounded-lg shadow-xs cursor-pointer border">
                        <img class="h-6" :src="cashOnDelivery.image" alt="payment" />
                        <span class="text-xs font-medium">{{ cashOnDelivery.name }}</span>
                    </div>

                    <div v-if="profile.balance >= total" @click.prevent="selectPaymentMethod(credit)"
                        :class="Object.keys(paymentMethod).length > 0 && credit.id === paymentMethod.id ? 'border-primary/50 bg-[#E7FFF3]' : 'border-white bg-white'"
                        class="flex flex-col items-center justify-center gap-2.5 py-4 rounded-lg shadow-xs cursor-pointer border">
                        <img class="h-6" :src="credit.image" alt="payment" />
                        <span class="text-xs font-medium">{{ credit.name }} ({{ profile.balance }})</span>
                    </div>

                    <div v-if="setting.site_online_payment_gateway === ActivityEnum.ENABLE"
                        v-for="paymentGateway in paymentGateways" @click.prevent="selectPaymentMethod(paymentGateway)"
                        :class="Object.keys(paymentMethod).length > 0 && paymentGateway.id === paymentMethod.id ? 'border-primary/50 bg-[#E7FFF3]' : 'border-white bg-white'"
                        class="flex flex-col items-center justify-center gap-2.5 py-4 rounded-lg shadow-xs cursor-pointer border">
                        <img class="h-6" :src="paymentGateway.image" alt="payment" />
                        <span class="text-xs font-medium">{{ paymentGateway.name }}</span>
                    </div>
                </div>
            </div>

            <div class="max-lg:hidden flex items-center justify-between gap-5 mt-10">
                <router-link :to="{ name: 'frontend.checkout.checkout' }"
                    class="field-button w-fit font-semibold tracking-wide normal-case text-secondary bg-[#F7F7FC]">
                    {{ $t('button.back_to_checkout') }}
                </router-link>

                <button @click.prevent="confirmOrder"
                    class="field-button w-fit font-semibold tracking-wide normal-case">
                    {{ $t('button.confirm_order') }}
                </button>
            </div>
        </div>

        <div class="col-12 lg:col-4">
            <CouponComponent />
            <SummeryComponent />

            <div class="max-lg:flex hidden flex-col-reverse sm:flex-row items-center justify-between gap-5 mt-10">
                <router-link :to="{ name: 'frontend.checkout.checkout' }"
                    class="field-button font-semibold tracking-wide normal-case text-secondary bg-[#F7F7FC]">
                    {{ $t('button.back_to_checkout') }}
                </router-link>

                <button @click.prevent="confirmOrder($event)"
                    class="field-button font-semibold tracking-wide normal-case">
                    {{ $t('button.confirm_order') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import statusEnum from "../../../../enums/modules/statusEnum";
import SummeryComponent from "../SummeryComponent.vue";
import CouponComponent from "../CouponComponent.vue";
import LoadingComponent from "../../components/LoadingComponent.vue";
import _ from "lodash";
import axios from "axios";
import alertService from "../../../../services/alertService";
import sourceEnum from "../../../../enums/modules/sourceEnum";
import ENV from "../../../../config/env";
import ActivityEnum from "../../../../enums/modules/activityEnum";

export default {
    name: "PaymentComponent",
    components: { CouponComponent, SummeryComponent, LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false
            },
            paymentGateways: [],
            credit: {},
            cashOnDelivery: {},
            statusEnum: statusEnum,
            sourceEnum: sourceEnum,
            ActivityEnum: ActivityEnum,
            form: {}
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        profile: function () {
            return this.$store.getters.authInfo;
        },
        paymentMethod: function () {
            return this.$store.getters['frontendCart/paymentMethod'];
        },
        subtotal: function () {
            return this.$store.getters['frontendCart/subtotal'];
        },
        discount: function () {
            return this.$store.getters['frontendCart/discount'];
        },
        total: function () {
            return this.$store.getters['frontendCart/total'];
        },
        orderType: function () {
            return this.$store.getters['frontendCart/orderType'];
        },
        getDeliveryAddress: function () {
            return this.$store.getters['frontendCart/deliveryAddress'];
        },
        getDeliveryZone: function () {
            return this.$store.getters['frontendCart/deliveryZone'];
        },
        getBillingAddress: function () {
            return this.$store.getters['frontendCart/billingAddress'];
        },
        getOutletAddress: function () {
            return this.$store.getters['frontendCart/outletAddress'];
        },
        cartCoupon: function () {
            return this.$store.getters['frontendCart/coupon'];
        },
        products: function () {
            return this.$store.getters['frontendCart/lists'];
        },
        images: function () {
            return this.$store.getters['frontendCart/images'];
        },
        deliveryCharge: function () {
            return this.$store.getters['frontendCart/deliveryCharge']
        },
        totalTax: function () {
            return this.$store.getters['frontendCart/totalTax'];
        },
        isLoggedIn: function () {
            return this.$store.getters.authStatus;
        },
        guestInfo: function () {
            return this.$store.getters['frontendCart/guestInfo'];
        },
        guestAddress: function () {
            return this.$store.getters['frontendCart/guestAddress'];
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('frontendPaymentGateway/lists', { status: this.statusEnum.ACTIVE }).then(res => {
            if (res.data.data.length > 0) {
                _.forEach(res.data.data, (gateway) => {
                    if (gateway.slug === "credit") {
                        this.credit = gateway;
                    } else if (gateway.slug === "cashondelivery") {
                        this.cashOnDelivery = gateway;
                        if (this.setting.site_cash_on_delivery === this.ActivityEnum.ENABLE) {
                            this.selectPaymentMethod(this.cashOnDelivery);
                        }

                    } else {
                        this.paymentGateways.push(gateway);
                    }
                });
            }
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        selectPaymentMethod: function (paymentMethod) {
            this.$store.dispatch("frontendCart/paymentMethod", paymentMethod);
        },
        confirmOrder: function (e) {
            e.target.disabled = true;
            
            // IMPORTANT: Check payment method BEFORE making API call
            let paymentSlug = Object.keys(this.paymentMethod).length > 0 ? this.paymentMethod.slug : '';
            if (!paymentSlug) {
                alertService.error(this.$t('message.payment_method_required'));
                e.target.disabled = false;
                return;
            }
            
            this.loading.isActive = true;

            const fd = new FormData();
            fd.append("subtotal", this.subtotal);
            fd.append("discount", this.discount);
            fd.append("tax", this.totalTax);
            fd.append("delivery_charge", this.deliveryCharge);
            fd.append("total", this.total);
            fd.append("order_type", this.orderType);
            fd.append("delivery_zone_id", Object.keys(this.getDeliveryZone).length > 0 ? this.getDeliveryZone.id : 0);
            fd.append("outlet_id", Object.keys(this.getOutletAddress).length > 0 ? this.getOutletAddress.id : 0);
            fd.append("coupon_id", Object.keys(this.cartCoupon).length > 0 ? this.cartCoupon.id : 0);
            fd.append("source", sourceEnum.WEB);
            fd.append("payment_method", Object.keys(this.paymentMethod).length > 0 ? this.paymentMethod.id : 0);
            fd.append("products", JSON.stringify(this.products));

            if (this.images.length > 0) {
                this.images.forEach((image, index) => {
                    const blob = this.base64ToBlob(image.image);
                    fd.append(`images[${index}]`, blob, image.name);
                });
            }

            // Guest checkout flow
            if (!this.isLoggedIn) {
                fd.append("guest_name", this.guestInfo.name);
                fd.append("guest_email", ""); // Email is optional
                fd.append("guest_phone", this.guestInfo.phone);
                fd.append("guest_governorate", this.guestAddress.governorate);
                fd.append("guest_city", this.guestAddress.city);
                fd.append("guest_street", this.guestAddress.street || '');
                fd.append("guest_building_number", this.guestAddress.building_number || '');
                fd.append("guest_apartment", this.guestAddress.apartment || '');

                axios.post('frontend/guest-order', fd).then(orderResponse => {
                    this.loading.isActive = false;
                    // Reset cart and redirect to payment
                    this.$store.dispatch('frontendCart/resetCart');
                    window.location.href = ENV.API_URL + "/payment/" + paymentSlug + "/pay/" + orderResponse.data.data.id;
                }).catch((err) => {
                    this.loading.isActive = false;
                    e.target.disabled = false;
                    if (err.response && err.response.data && typeof err.response.data.errors === 'object') {
                        _.forEach(err.response.data.errors, (error) => {
                            alertService.error(error[0]);
                        });
                    } else if (err.response && err.response.data && err.response.data.message) {
                        alertService.error(err.response.data.message);
                    }
                });
            } else {
                // Logged-in user flow
                fd.append("address_id", Object.keys(this.getDeliveryAddress).length > 0 ? this.getDeliveryAddress.id : 0);

                this.$store.dispatch('frontendOrder/save', fd).then(orderResponse => {
                    this.loading.isActive = false;
                    let paymentSlug = Object.keys(this.paymentMethod).length > 0 ? this.paymentMethod.slug : '';
                    if (paymentSlug) {
                        window.location.href = ENV.API_URL + "/payment/" + paymentSlug + "/pay/" + orderResponse.data.data.id;
                    } else {
                        alertService.error(this.$t('message.payment_method_required'));
                        e.target.disabled = false;
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                    e.target.disabled = false;
                    if (typeof err.response.data.errors === 'object') {
                        _.forEach(err.response.data.errors, (error) => {
                            alertService.error(error[0]);
                        });
                    }
                });
            }
        },
        base64ToBlob: function (base64String) {
            let byteCharacters = atob(base64String.split(',')[1]);
            let byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            let byteArray = new Uint8Array(byteNumbers);
            return new Blob([byteArray], { type: "image/png" });
        }
    }
}
</script>