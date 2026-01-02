<template>
    <LoadingComponent :props="loading" />
    <div class="row">
        <div class="col-12 lg:col-8">
            <div class="flex items-center rounded-2xl w-fit mb-6 text-focus bg-[#EAF6FF]">
                <div class="relative cursor-pointer">
                    <input @change="changeOrderType(orderTypeEnum.DELIVERY)" id="checkout-delivery"
                        :checked="orderType === orderTypeEnum.DELIVERY" :value="orderTypeEnum.DELIVERY"
                        class="cart-switch w-full h-full absolute top-0 left-0 opacity-0 cursor-pointer" type="radio">
                    <label class="py-1.5 px-3.5 rounded-2xl text-sm font-semibold capitalize transition cursor-pointer"
                        for="checkout-delivery">{{ $t('label.delivery') }}</label>
                </div>
                <div v-if="setting.site_pick_up == enums.activityEnum.ENABLE" class="relative cursor-pointer">
                    <input @change="changeOrderType(orderTypeEnum.PICK_UP)" id="checkout-delivery"
                        :checked="orderType === orderTypeEnum.PICK_UP" :value="orderTypeEnum.PICK_UP"
                        class="cart-switch w-full h-full absolute top-0 left-0 opacity-0 cursor-pointer" type="radio">
                    <label class="py-1.5 px-3.5 rounded-2xl text-sm font-semibold capitalize transition cursor-pointer"
                        for="checkout-delivery">{{ $t('label.pick_up') }}</label>
                </div>
            </div>

            <div v-if="orderType === orderTypeEnum.PICK_UP" class="mb-6 pb-4 rounded-2xl shadow-card">
                <h4 class="font-bold capitalize p-4 border-b border-gray-100">{{ $t('label.store_location') }}</h4>

                <div v-if="outlets.length > 0" v-for="outlet in outlets" class="px-4 pt-4">
                    <div class="flex p-2 border transition-all rounded-lg"
                        :class="outlet.id === modelOutlet.id ? 'border-primary/50 bg-[#E7FFF3]' : 'border-[#F7F7F7] bg-[#F7F7F7]'">
                        <input type="radio" @change="outletAddress($event)" :id="outlet.name" :name="outlet.name"
                            :value="outlet" :key="outlet" v-model="modelOutlet">
                        <label :for="outlet.name" class="px-2 text-sm capitalize cursor-pointer ">
                            <span class="font-semibold">{{ outlet.name }}</span> - {{ outlet.address }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- Guest Info Component (for non-logged-in users) -->
            <GuestInfoComponent v-if="!isLoggedIn && orderType === orderTypeEnum.DELIVERY" />

            <!-- Address Component (for logged-in users) -->
            <AddressComponent v-if="isLoggedIn && orderType === orderTypeEnum.DELIVERY" :show="true"
                :selectedAddress="getDeliveryAddress" :method="deliveryAddress" />

            <div class="mb-6 rounded-2xl shadow-card" v-if="file_attachment">
                <div class="flex flex-wrap items-center gap-1 p-4">
                    <h4 class="font-bold capitalize">{{ $t('label.file_attachment') }}</h4>
                    <p class="text-sm">{{ $t('label.up_to_image') }}</p>
                </div>
                <div class="px-6 pb-6 grid grid-cols-1 sm:grid-cols-2 gap-4 items-start">
                    <div v-if="prescriptions.length > 0" v-for="prescription in prescriptions"
                        class="p-4 flex items-center gap-4 text-center rounded-lg cursor-pointer border border-dashed border-[#D9DBE9]">
                        <img :src="prescription.image" alt="avatar"
                            class="w-24 aspect-square rounded-lg object-cover flex-shrink-0">
                        <div class="flex-auto text-left overflow-hidden">
                            <h3 class="text-sm font-medium whitespace-nowrap text-ellipsis overflow-hidden mb-1">{{
                                prescription.name }}</h3>
                            <p class="text-[10px] leading-none font-medium text-paragraph mb-4">{{ prescription.size }}
                            </p>
                            <button @click="handleDelete(prescription, prescriptions)" type="button"
                                class="flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-danger bg-danger/10">
                                <i class="lab-line-trash text-sm flex-shrink-0 -mt-0.5"></i>
                                <span class="text-xs font-semibold capitalize">{{ $t('label.delete_image') }}</span>
                            </button>
                        </div>
                    </div>
                    <label v-if="prescriptions.length < 5" for="prescription"
                        class="p-6  block text-center rounded-lg cursor-pointer border border-dashed border-[#D9DBE9] transition-all duration-300 hover:bg-primary/5 hover:border-primary/20">
                        <input type="file" id="prescription" @change="handleFileUrl($event, prescriptions)" hidden
                            accept="image/png, image/jpeg, image/jpg">
                        <i class="lab-fill-gallery-export text-2xl leading-none text-primary mb-4"></i>
                        <h4 class="text-sm font-semibold mb-1">{{ $t('label.drop_prescription_image') }} <span
                                class="text-primary">{{ $t('label.browse') }}</span></h4>
                        <p class="text-xs text-[#6E7191] mb-4">{{ $t('label.support_image_type') }}</p>
                    </label>
                </div>
            </div>

            <div class="max-lg:hidden flex items-center justify-between gap-5 mt-10">
                <router-link :to="{ name: 'frontend.checkout.cartList' }"
                    class="field-button w-fit font-semibold tracking-wide normal-case text-secondary bg-[#F7F7FC]">
                    {{ $t('button.back_to_cart') }}
                </router-link>

                <button @click.prevent="selectAddress"
                    class="field-button w-fit font-semibold tracking-wide normal-case"
                    :class="parseFloat(getDeliveryZone.minimum_order_amount) > 0 && parseFloat(getDeliveryZone.minimum_order_amount) > subtotal && orderType === orderTypeEnum.DELIVERY ? 'bg-primary/50 pointer-events-disable text-sm' : ''">
                    {{ $t('button.save_and_pay') }}
                    <strong
                        v-if="parseFloat(getDeliveryZone.minimum_order_amount) > 0 && parseFloat(getDeliveryZone.minimum_order_amount) > subtotal && orderType === orderTypeEnum.DELIVERY"
                        class="text-red-400">
                        ({{ $t('label.min_order') + ': ' + getDeliveryZone.currency_minimum_order_amount }})
                    </strong>
                </button>
            </div>

        </div>

        <div class="col-12 lg:col-4">
            <CouponComponent />
            <SummeryComponent />

            <div class="max-lg:flex hidden flex-col-reverse sm:flex-row items-center justify-between gap-5 mt-10">
                <router-link :to="{ name: 'frontend.checkout.cartList' }"
                    class="field-button font-semibold tracking-wide normal-case text-secondary bg-[#F7F7FC]">
                    {{ $t('button.back_to_cart') }}
                </router-link>

                <button @click.prevent="selectAddress" class="field-button font-semibold tracking-wide normal-case"
                    :class="parseFloat(getDeliveryZone.minimum_order_amount) > 0 && parseFloat(getDeliveryZone.minimum_order_amount) > subtotal && orderType === orderTypeEnum.DELIVERY ? 'bg-primary/50 pointer-events-disable text-sm' : ''">
                    {{ $t('button.save_and_pay') }}
                    <strong
                        v-if="parseFloat(getDeliveryZone.minimum_order_amount) > 0 && parseFloat(getDeliveryZone.minimum_order_amount) > subtotal && orderType === orderTypeEnum.DELIVERY"
                        class="text-red-400">
                        ({{ $t('label.min_order') + ': ' + getDeliveryZone.currency_minimum_order_amount }})
                    </strong>
                </button>

            </div>
        </div>
    </div>
</template>

<script>
import orderTypeEnum from "../../../../enums/modules/orderTypeEnum";
import AddressComponent from "./AddressComponent.vue";
import GuestInfoComponent from "./GuestInfoComponent.vue";
import SummeryComponent from "../SummeryComponent.vue";
import CouponComponent from "../CouponComponent.vue";
import router from "../../../../router";
import alertService from "../../../../services/alertService";
import LoadingComponent from "../../components/LoadingComponent.vue";
import statusEnum from "../../../../enums/modules/statusEnum";
import activityEnum from "../../../../enums/modules/activityEnum"


export default {
    name: "CheckoutComponent",
    components: { CouponComponent, SummeryComponent, AddressComponent, GuestInfoComponent, LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false
            },
            enums: {
                statusEnum: statusEnum,
                activityEnum: activityEnum,
            },
            orderTypeEnum: orderTypeEnum,
            prescriptions: [],
            modelOutlet: 0,
            file_attachment: false
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        orderType: function () {
            return this.$store.getters['frontendCart/orderType'];
        },
        getDeliveryAddress: function () {
            return this.$store.getters['frontendCart/deliveryAddress'];
        },
        getOutletAddress: function () {
            return this.$store.getters['frontendCart/outletAddress'];
        },
        getDeliveryZone: function () {
            return this.$store.getters['frontendCart/deliveryZone'];
        },
        outlets: function () {
            return this.$store.getters['frontendOutlet/lists'];
        },
        subtotal: function () {
            return this.$store.getters['frontendCart/subtotal'];
        },
        images: function () {
            return this.$store.getters['frontendCart/images'];
        },
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        },
        isLoggedIn: function () {
            return this.$store.getters.authStatus;
        }
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('frontendOrderArea/lists').then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });

        this.loading.isActive = true;
        this.$store.dispatch('frontendOutlet/lists', {
            status: this.enums.statusEnum.ACTIVE
        }).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
        if (this.images.length > 0) {
            this.prescriptions = this.images;
        }
        if (this.carts.some(item => item.file_attachment === this.enums.activityEnum.ENABLE)) {
            this.file_attachment = true;
        }
    },
    methods: {
        changeOrderType: function (e) {
            this.$store.dispatch('frontendCart/updateOrderType', e)
        },
        deliveryAddress: function (e) {
            this.$store.dispatch('frontendDeliveryZone/selectDeliveryZone', e).then(res => {
                this.$store.dispatch('frontendCart/deliveryZone', res.data.data).then().catch();
                this.$store.dispatch('frontendCart/deliveryAddress', e).then().catch();
            }).catch((err) => {
                this.$store.dispatch('frontendCart/deliveryZone', {}).then().catch();
                this.$store.dispatch('frontendCart/deliveryAddress', {}).then().catch();
                alertService.error(err.response.data.message);
            });
        },
        outletAddress: function (e) {
            setTimeout(() => {
                this.$store.dispatch('frontendCart/outletAddress', this.modelOutlet).then().catch();
            }, 100);
        },
        selectAddress: function () {
            // For guests, validate guest info
            if (!this.isLoggedIn) {
                const guestInfo = this.$store.getters['frontendCart/guestInfo'];
                const guestAddress = this.$store.getters['frontendCart/guestAddress'];
                
                if (!guestInfo.name || !guestInfo.phone) {
                    alertService.error(this.$t("message.required_guest_info"));
                    return;
                }
                
                if (this.orderType === orderTypeEnum.DELIVERY) {
                    if (!guestAddress.governorate || !guestAddress.city) {
                        alertService.error(this.$t("message.required_delivery_address"));
                        return;
                    }
                }
            } else {
                // For logged-in users, check saved address
                if (this.orderType === orderTypeEnum.DELIVERY && Object.keys(this.getDeliveryAddress).length === 0) {
                    alertService.error(this.$t("message.required_delivery_address"));
                    return;
                }
            }
            
            if (this.orderType === orderTypeEnum.PICK_UP && Object.keys(this.getOutletAddress).length === 0) {
                alertService.error(this.$t("message.required_outlet_address"));
                return;
            }
            
            if (this.carts.some(item => item.file_attachment === this.enums.activityEnum.ENABLE && this.images.length === 0)) {
                alertService.error(this.$t("message.file_attachment_required"));
                return;
            }
            
            router.push({ name: "frontend.checkout.payment" });
        },
        handleFileUrl: function (changeEvent, storeVariable) {
            const file = changeEvent.target.files[0];
            const fileSizeMB = file.size / (1024 * 1024);
            if (fileSizeMB > 2) {
                alertService.error(this.$t("message.file_size_not_exceed"));
                return;
            }
            const fileSize = fileSizeMB.toFixed(2) + " mb";

            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const base64Image = event.target.result;
                        storeVariable.push({
                            id: storeVariable.length + 1,
                            name: file.name,
                            size: fileSize,
                            image: base64Image,
                        });
                        this.$store.dispatch('frontendCart/images', this.prescriptions).then().catch();
                    };

                    reader.readAsDataURL(file);
                }
            }
        },
        handleDelete: function (obj, arr) {
            const selectIndex = arr.findIndex(item => item.id == obj.id);
            if (selectIndex !== -1) arr.splice(selectIndex, 1);
            this.$store.dispatch('frontendCart/images', this.prescriptions).then().catch();

        }
    },
    watch: {
        carts: {
            deep: true,
            handler(newCarts) {
                const hasFileAttachment = newCarts.some(item => item.file_attachment === this.enums.activityEnum.ENABLE);
                this.file_attachment = hasFileAttachment;
                if (!this.file_attachment) {
                    this.$store.dispatch('frontendCart/images', []).then().catch();
                }
            },
        },
    },
}
</script>