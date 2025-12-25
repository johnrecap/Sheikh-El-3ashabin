<template>
    <LoadingComponent :props="loading" />
    <div class="col-12">
        <div class="db-card p-4">
            <div class="flex flex-wrap gap-y-5 items-end justify-between">
                <div>
                    <div class="flex flex-wrap items-start gap-y-2 gap-x-6 mb-5">
                        <p class="text-2xl font-medium">{{ $t('label.order_id') }}:
                            <span class="text-heading">
                                #{{ order.order_serial_no }}
                            </span>
                        </p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <span
                                :class="'text-sm capitalize h-5 leading-5 px-2 rounded-3xl text-[#FB4E4E] bg-[#FFDADA]' + statusClass(order.payment_status)">
                                {{ enums.paymentStatusEnumArray[order.payment_status] }}
                            </span>
                            <span :class="'text-sm capitalize px-2 rounded-3xl ' + orderStatusClass(order.status)">
                                {{ enums.orderStatusEnumArray[order.status] }}
                            </span>
                            <span v-if="order.edited_date"
                                :class="'text-sm capitalize h-5 leading-5 px-2 rounded-3xl text-[#FB4E4E] bg-[#FFDADA]'">
                                {{ $t('label.edited') }}
                            </span>
                            <span v-if="order.is_guest_order"
                                class="text-sm capitalize h-5 leading-5 px-2 rounded-3xl text-[#8B5CF6] bg-[#EDE9FE]">
                                {{ $t('label.guest_order') }}
                            </span>
                        </div>
                    </div>
                    <ul class="flex flex-col gap-2">
                        <li class="flex items-center gap-2">
                            <i class="lab lab-line-calendar lab-font-size-16"></i>
                            <span class="text-xs">{{ order.order_datetime }}</span>
                        </li>
                        <li class="text-xs">
                            {{ $t('label.payment_type') }}:
                            <span class="text-heading">
                                {{ order.payment_method_name }}
                            </span>
                        </li>
                        <li class="text-xs">
                            {{ $t('label.order_type') }}:
                            <span class="text-heading">
                                {{ enums.orderTypeEnumArray[order.order_type] }}
                            </span>
                        </li>
                        <li class="text-xs">
                            {{ $t('label.delivery_zone') }}:
                            <span class="text-heading">
                                {{ order.delivery_zone_name }}
                            </span>
                        </li>
                        <li class="text-xs" v-if="order.delivery_boy_name">
                            {{ $t('label.delivery_boy') }}:
                            <span class="text-heading">
                                {{ order.delivery_boy_name }}
                            </span>
                        </li>
                        <li v-if="order.transaction && order.edited_amount !== 0" class="text-xs">
                            {{ $t('label.note') }}:
                            <span v-if="order.edited_amount >= 0" class="font-bold text-blue-500">
                                {{ $t('label.due_amount') }} {{ order.edited_currency_amount }}
                            </span>
                            <span v-else class="font-bold text-blue-500">
                                {{ $t('label.return_amount') }} {{ order.edited_currency_amount }}
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="flex flex-wrap gap-3" v-if="order.status === enums.orderStatusEnum.PENDING">
                    <OnlineOrderReasonComponent />
                    <button type="button" @click="changeStatus(enums.orderStatusEnum.CONFIRMED)"
                        class="flex items-center justify-center text-white gap-2 px-4 h-[38px] rounded shadow-db-card bg-[#2AC769]">
                        <i class="lab lab-fill-save"></i>
                        <span class="text-sm capitalize text-white">{{ $t('button.accept') }}</span>
                    </button>
                </div>

                <div class="flex flex-wrap gap-3"
                    v-else-if="order.status !== enums.orderStatusEnum.REJECTED && order.status !== enums.orderStatusEnum.CANCELED">
                    <div class="relative"
                        v-if="order.order_type === enums.orderTypeEnum.DELIVERY && order.status !== enums.orderStatusEnum.DELIVERED && order.status !== enums.orderStatusEnum.RETURNED">
                        <select v-model="delivery_boy" @change="selectDeliveryBoy($event)"
                            class="text-sm capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary">
                            <option value="0" disabled selected hidden>{{ $t('label.select_delivery_boy') }}</option>
                            <option v-for="deliveryBoy in deliveryBoys" :value="deliveryBoy.id">
                                {{ deliveryBoy.name }}
                            </option>
                        </select>
                        <i
                            class="lab lab-line-chevron-down lab-font-size-16 absolute top-1/2 right-3.5 -translate-y-1/2 text-primary"></i>
                    </div>
                    <div v-if="order.transaction === null" class="relative">
                        <select v-model="payment_status" @change="changePaymentStatus($event)"
                            class="text-sm capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary">
                            <option v-for="paymentStatus in enums.paymentStatusObject" :value="paymentStatus.value">
                                {{ paymentStatus.name }}
                            </option>
                        </select>
                        <i
                            class="lab lab-line-chevron-down lab-font-size-16 absolute top-1/2 right-3.5 -translate-y-1/2 text-primary"></i>
                    </div>

                    <div class="relative" v-if="order.order_type === enums.orderTypeEnum.DELIVERY">
                        <select v-model="order_status" @change="orderStatus($event)"
                            class="text-sm capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary">
                            <option v-for="orderStatus in enums.orderStatusObject" :value="orderStatus.value">
                                {{ orderStatus.name }}
                            </option>
                        </select>
                        <i
                            class="lab lab-line-chevron-down lab-font-size-16 absolute top-1/2 right-3.5 -translate-y-1/2 text-primary"></i>
                    </div>

                    <div class="relative" v-if="order.order_type === enums.orderTypeEnum.PICK_UP">
                        <select v-model="order_status" @change="orderStatus($event)"
                            class="text-sm capitalize appearance-none pl-4 pr-10 h-[38px] rounded border border-primary bg-white text-primary">
                            <option v-for="orderStatus in enums.orderStatusPickupObject" :value="orderStatus.value">
                                {{ orderStatus.name }}
                            </option>
                        </select>
                        <i
                            class="lab lab-line-chevron-down lab-font-size-16 absolute top-1/2 right-3.5 -translate-y-1/2 text-primary"></i>
                    </div>

                    <OnlineOrderReceiptComponent :order="order" :orderProducts="orderProducts" :orderUser="orderUser"
                        :orderAddress="orderAddress" />
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 sm:col-6">
        <div class="row">
            <div class="col-12">
                <div class="db-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">{{ $t('label.order_details') }}</h3>
                    </div>
                    <div class="db-card-body">
                        <div class="pl-3">
                            <div class="flex justify-between items-start mb-3 pb-3 border-b last:mb-0 last:pb-0 last:border-b-0 border-gray-2"
                                v-if="orderProducts.length > 0" v-for="product in orderProducts" :key="product">
                                <div class="flex items-center gap-3 relative">
                                    <h3
                                        class="absolute top-5 ltr:-left-3 rtl:-right-3 text-sm w-[26px] h-[26px] leading-[26px] text-center rounded-full text-white bg-heading">
                                        {{ product.quantity }}</h3>
                                    <img class="w-16 h-16 rounded-lg flex-shrink-0" :src="product.product_image"
                                        alt="thumbnail">
                                    <div class="flex-auto overflow-hidden">
                                        <h4 :class="!product.variation_names ? 'mb-4' : ''"
                                            class="w-20 sm:w-full text-sm capitalize whitespace-nowrap overflow-hidden text-ellipsis truncate">
                                            {{ product.product_name }} </h4>
                                        <p class="text-sm overflow-hidden">{{ product.variation_names }}</p>
                                        <div class="flex flex-wrap items-center justify-between gap-4">
                                            <div class="flex items-center gap-8">
                                                <span class="text-sm font-semibold">{{
                                                    product.subtotal_currency_price
                                                    }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="order.status === enums.orderStatusEnum.PENDING"
                                    class="flex shrink items-end flex-col gap-2">
                                    <div class="flex shrink items-start gap-2">
                                        <button v-if="form.product_id != product.product_id"
                                            @click="toggleEdit(product)"
                                            class="flex items-center justify-center gap-1.5 w-7 h-7 rounded-full bg-[#01be5f0d] text-[#2AC769] transition-all duration-300 hover:bg-[#2AC769] hover:text-white">
                                            <i class="lab lab-line-edit text-sm"></i>
                                        </button>
                                        <button v-else @click="updateQuantity(product)"
                                            class="flex items-center justify-center gap-1.5 w-7 h-7 rounded-full bg-[#01be5f0d] text-[#2AC769] transition-all duration-300 hover:bg-[#2AC769] hover:text-white">
                                            <i class="lab lab-fill-save text-sm"></i>
                                        </button>
                                        <button @click.prevent="removeProduct(product)"
                                            class="flex items-center justify-center gap-1.5 w-7 h-7 rounded-full bg-[#FFF4F4] text-[#E93C3C] transition-all duration-300 hover:bg-[#E93C3C] hover:text-white">
                                            <i class="lab-line-trash text-sm"></i>
                                        </button>
                                    </div>

                                    <div v-if="form.product_id == product.product_id"
                                        class="flex items-end justify-between gap-3">
                                        <div
                                            class="flex items-center gap-1 max-w-28 w-full p-1 rounded-full bg-[#F7F7FC]">
                                            <button @click.prevent="quantityDecrement(index, product)" type="button"
                                                :class="form.quantity === 1 ? 'cursor-not-allowed' : ''"
                                                class="lab-fill-circle-minus text-lg leading-none transition-all duration-300 hover:text-primary"></button>

                                            <input v-if="product.sell_by_fraction == enums.askEnum.NO"
                                                v-on:keypress="onlyNumber($event)"
                                                v-on:keyup="quantityUp(index, product, $event)" type="number"
                                                v-model="form.quantity"
                                                class="text-center w-full h-5 text-sm font-medium">

                                            <input v-else v-model="form.quantity" v-on:keypress="floatNumber($event)"
                                                v-on:keyup="quantityUp(index, product, $event)"
                                                class="text-center w-full h-5 text-sm font-medium">

                                            <button
                                                :class="product.quantity >= product.stock ? 'cursor-not-allowed' : ''"
                                                @click.prevent="quantityIncrement(index, product)" type="button"
                                                class="lab-fill-circle-plus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" v-if="order.status === enums.orderStatusEnum.REJECTED">
                <div class="db-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">{{ $t('label.reason') }}</h3>
                    </div>
                    <div class="db-card-body">
                        <p>{{ order.reason }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12" v-if="order.images?.length > 0">
                <div class="db-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">{{ $t('label.attachments') }}</h3>
                    </div>
                    <div class="db-card-body">
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <div class="w-full rounded-lg flex-shrink-0 border border-gray-500 hover:cursor-pointer overflow-hidden group"
                                v-for="image in order.images">
                                <img class="w-full h-28 rounded-lg group-hover:scale-125 transition-all duration-300"
                                    :src="image" alt="thumbnail" @click="previewFile(image)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 sm:col-6">
        <div class="row">
            <div class="col-12">
                <div class="db-card p-1">
                    <ul class="flex flex-col gap-2 p-3 border-b border-dashed border-[#EFF0F6]">
                        <li class="flex items-center justify-between text-heading">
                            <span class="text-sm leading-6 capitalize">{{ $t('label.subtotal') }}</span>
                            <span class="text-sm leading-6 capitalize">{{ order.subtotal_currency_price }}</span>
                        </li>
                        <li class="flex items-center justify-between text-heading">
                            <span class="text-sm leading-6 capitalize">{{ $t('label.tax_fee') }}</span>
                            <span class="text-sm leading-6 capitalize">{{ order.tax_currency_price }}</span>
                        </li>
                        <li class="flex items-center justify-between text-heading">
                            <span class="text-sm leading-6 capitalize">{{ $t('label.discount') }}</span>
                            <span class="text-sm leading-6 capitalize">{{ order.discount_currency_price }}</span>
                        </li>
                        <li class="flex items-center justify-between text-heading">
                            <span class="text-sm leading-6 capitalize">{{ $t('label.delivery_charge') }}</span>
                            <span class="text-sm leading-6 capitalize font-semibold text-[#1AB759]">
                                {{ order.delivery_charge_currency_price }}
                            </span>
                        </li>
                    </ul>
                    <div class="flex items-center justify-between p-3">
                        <h4 class="text-sm leading-6 font-bold capitalize">{{ $t('label.total') }}</h4>
                        <h5 class="text-sm leading-6 font-bold capitalize">
                            {{ order.total_currency_price }}
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-12" v-if="order.order_type === enums.orderTypeEnum.DELIVERY">
                <div class="db-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">
                            {{ $t('label.delivery_address') }}
                        </h3>
                    </div>
                    <div class="db-card-body">
                        <!-- Guest Order Info -->
                        <template v-if="order.is_guest_order">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-[#8B5CF6] flex items-center justify-center">
                                    <i class="lab lab-line-user text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sm capitalize text-[#374151]">
                                        {{ order.guest_name }}
                                    </h4>
                                    <span class="text-xs text-[#8B5CF6]">{{ $t('label.guest_order') }}</span>
                                </div>
                            </div>
                            <ul class="flex flex-col gap-3 py-4 border-t border-[#EFF0F6]">
                                <li v-if="order.guest_email" class="flex items-center gap-2.5">
                                    <i class="lab lab-line-mail lab-font-size-14"></i>
                                    <span class="text-xs">{{ order.guest_email }}</span>
                                </li>
                                <li class="flex items-center gap-2.5" v-if="order.guest_phone">
                                    <i class="lab lab-line-call-calling lab-font-size-14"></i>
                                    <span class="text-xs font-semibold text-primary">{{ order.guest_phone }}</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="lab lab-line-location lab-font-size-14"></i>
                                    <span class="text-xs">
                                        {{ order.guest_apartment ? order.guest_apartment + ', ' : '' }}
                                        {{ order.guest_building_number ? order.guest_building_number + ', ' : '' }}
                                        {{ order.guest_street ? order.guest_street + ', ' : '' }}
                                        {{ order.guest_city ? order.guest_city + ', ' : '' }}
                                        {{ order.guest_governorate || '' }}
                                    </span>
                                </li>
                            </ul>
                        </template>
                        <!-- Registered User Order Info -->
                        <template v-else-if="orderAddress">
                            <div class="flex items-center gap-3 mb-4">
                                <img class="w-8 rounded-full" alt="avatar" :src="orderUser.image">
                                <h4 class="font-semibold text-sm capitalize text-[#374151]">
                                    {{ textShortener(orderUser.name, 20) }}
                                </h4>
                            </div>
                            <ul class="flex flex-col gap-3 py-4 border-t border-[#EFF0F6]">
                                <li v-if="orderUser.email" class="flex items-center gap-2.5">
                                    <i class="lab lab-line-mail lab-font-size-14"></i>
                                    <span class="text-xs">{{ orderUser.email }}</span>
                                </li>
                                <li class="flex items-center gap-2.5" v-if="orderUser.phone">
                                    <i class="lab lab-line-call-calling lab-font-size-14"></i>
                                    <span class="text-xs">{{ orderAddress.phone || (orderUser.country_code + '' + orderUser.phone) }}</span>
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i class="lab lab-line-location lab-font-size-14"></i>
                                    <span class="text-xs">
                                        <!-- Use full_address if available (manual entry), otherwise use address field -->
                                        <span v-if="orderAddress.full_address">
                                            {{ orderAddress.full_address }}
                                        </span>
                                        <span v-else-if="orderAddress.governorate || orderAddress.city">
                                            {{ orderAddress.apartment ? 'شقة ' + orderAddress.apartment + '، ' : '' }}
                                            {{ orderAddress.building_number ? 'عقار ' + orderAddress.building_number + '، ' : '' }}
                                            {{ orderAddress.street ? orderAddress.street + '، ' : '' }}
                                            {{ orderAddress.city ? orderAddress.city + '، ' : '' }}
                                            {{ orderAddress.governorate || '' }}
                                        </span>
                                        <span v-else-if="orderAddress.address">
                                            {{ orderAddress.apartment ? orderAddress.apartment + '، ' : '' }}
                                            {{ orderAddress.address }}
                                        </span>
                                    </span>
                                </li>
                            </ul>
                        </template>
                    </div>
                </div>
            </div>

            <div class="col-12" v-else-if="order.order_type === enums.orderTypeEnum.PICK_UP">
                <div class="db-card">
                    <div class="db-card-header">
                        <h3 class="db-card-title">
                            {{ $t('label.pick_up_address') }}
                        </h3>
                    </div>

                    <div class="db-card-body">
                        <div class="flex items-center gap-3 mb-4">
                            <img class="w-8 rounded-full" alt="avatar" :src="orderUser.image">
                            <h4 class="font-semibold text-sm capitalize text-[#374151]">
                                {{ textShortener(orderUser.name, 20) }}
                            </h4>
                        </div>
                        <ul class="flex flex-col gap-3 py-4 border-t border-[#EFF0F6]">
                            <li v-if="orderUser.email" class="flex items-center gap-2.5">
                                <i class="lab lab-line-mail lab-font-size-14"></i>
                                <span class="text-xs">{{ orderUser.email }}</span>
                            </li>
                            <li v-if="orderUser.phone" class="flex items-center gap-2.5">
                                <i class="lab lab-line-call-calling lab-font-size-14"></i>
                                <span class="text-xs">{{ orderUser.country_code + '' + orderUser.phone }}</span>
                            </li>
                            <li class="flex items-center gap-2.5">
                                <i class="lab lab-line-location lab-font-size-14"></i>
                                <span class="text-xs">
                                    <span v-if="outletAddress.address">{{ outletAddress.address }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="returnOrderNoteModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.reason") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click.prevent="resetModal"></button>
            </div>
            <div class="modal-body">
                <form @submit.prevent="returnOrder" class="w-full d-block">
                    <div class="form-row">
                        <div class="form-col-12">
                            <label for="name" class="db-field-title required">
                                {{ $t("label.reason") }}
                                <span class="db-table-action">
                                    <i class="lab lab-fill-info !m-0 !p-0"></i>
                                    <span class="db-tooltip">{{ $t("message.return_order_reason") }}</span>
                                </span>
                            </label>
                            <textarea v-model="returnForm.reason" v-bind:class="error ? 'invalid' : ''" type="text"
                                id="name" class="db-field-control"></textarea>
                            <small class="db-field-alert !m-0" v-if="error">
                                {{ error }}
                            </small>
                        </div>
                        <div class="form-col-12">
                            <div class="modal-btns">
                                <button type="button" class="modal-btn-outline modal-close" @click.prevent="resetModal">
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
    <div id="attachmentModal" class="modal">
        <div class="modal-dialog max-w-[600px]">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t("label.attachments") }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500"
                    @click.prevent="resetAttachmentModal"></button>
            </div>
            <div class="modal-body ">
                <div class="row py-1">
                    <img class="" :src="previewImage" alt="attachment-image">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import appService from "../../../services/appService";
import paymentStatusEnum from "../../../enums/modules/paymentStatusEnum";
import addressTypeEnum from "../../../enums/modules/addressTypeEnum";
import orderStatusEnum from "../../../enums/modules/orderStatusEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import alertService from "../../../services/alertService";
import OnlineOrderReasonComponent from "./OnlineOrderReasonComponent.vue";
import OnlineOrderReceiptComponent from "./OnlineOrderReceiptComponent.vue";
import askEnum from "../../../enums/modules/askEnum";
import composables from "../../../composables/composables";

export default {
    name: "OnlineOrderShowComponent",
    components: {
        OnlineOrderReceiptComponent,
        LoadingComponent,
        OnlineOrderReasonComponent
    },
    data() {
        return {
            loading: {
                isActive: false
            },
            payment_status: 0,
            delivery_boy: 0,
            order_status: 0,
            enums: {
                paymentStatusEnum: paymentStatusEnum,
                addressTypeEnum: addressTypeEnum,
                statusEnum: statusEnum,
                askEnum: askEnum,
                paymentStatusEnumArray: {
                    [paymentStatusEnum.PAID]: this.$t("label.paid"),
                    [paymentStatusEnum.UNPAID]: this.$t("label.unpaid")
                },
                paymentStatusObject: [
                    {
                        name: this.$t("label.paid"),
                        value: paymentStatusEnum.PAID
                    },
                    {
                        name: this.$t("label.unpaid"),
                        value: paymentStatusEnum.UNPAID,
                    },
                ],
                orderStatusEnum: orderStatusEnum,
                orderStatusEnumArray: {
                    [orderStatusEnum.PENDING]: this.$t("label.pending"),
                    [orderStatusEnum.CONFIRMED]: this.$t("label.confirmed"),
                    [orderStatusEnum.ON_THE_WAY]: this.$t("label.on_the_way"),
                    [orderStatusEnum.DELIVERED]: this.$t("label.delivered"),
                    [orderStatusEnum.CANCELED]: this.$t("label.canceled"),
                    [orderStatusEnum.REJECTED]: this.$t("label.rejected"),
                    [orderStatusEnum.RETURNED]: this.$t("label.returned"),
                },
                orderStatusObject: [
                    {
                        name: this.$t("label.confirmed"),
                        value: orderStatusEnum.CONFIRMED,
                    },
                    {
                        name: this.$t("label.on_the_way"),
                        value: orderStatusEnum.ON_THE_WAY,
                    },
                    {
                        name: this.$t("label.delivered"),
                        value: orderStatusEnum.DELIVERED,
                    },
                    {
                        name: this.$t("label.returned"),
                        value: orderStatusEnum.RETURNED,
                    },
                ],
                orderStatusPickupObject: [
                    {
                        name: this.$t("label.confirmed"),
                        value: orderStatusEnum.CONFIRMED,
                    },
                    {
                        name: this.$t("label.delivered"),
                        value: orderStatusEnum.DELIVERED,
                    },
                ],
                orderTypeEnum: orderTypeEnum,
                orderTypeEnumArray: {
                    [orderTypeEnum.DELIVERY]: this.$t("label.delivery"),
                    [orderTypeEnum.PICK_UP]: this.$t("label.pick_up")
                },
            },
            form: {
                order_id: null,
                stock_id: null,
                product_id: null,
                quantity: null
            },
            returnForm: {
                reason: ""
            },
            error: "",
            previewImage: "",
        }
    },
    computed: {
        order: function () {
            return this.$store.getters['onlineOrder/show'];
        },
        orderProducts: function () {
            return this.$store.getters['onlineOrder/orderProducts'];
        },
        orderUser: function () {
            return this.$store.getters['onlineOrder/orderUser'];
        },
        orderAddress: function () {
            return this.$store.getters['onlineOrder/orderAddress'];
        },
        outletAddress: function () {
            return this.$store.getters['onlineOrder/outletAddress'];
        },
        deliveryBoys: function () {
            return this.$store.getters["deliveryBoy/lists"];
        },
    },
    mounted() {
        this.onlineOrderShow();
    },
    methods: {
        statusClass: function (status) {
            return appService.statusClass(status);
        },
        orderStatusClass: function (status) {
            return appService.orderStatusClass(status);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        resetModal: function () {
            composables.closeModal('returnOrderNoteModal');
            this.form.reason = '';
            this.error = "";
            this.onlineOrderShow();
        },
        previewFile: function (image) {
            composables.openModal('attachmentModal');
            this.previewImage = image;
        },
        resetAttachmentModal: function () {
            composables.closeModal('attachmentModal');
            setTimeout(() => {
                this.previewImage = "";
            }, 300);
        },
        onlineOrderShow: function () {
            this.loading.isActive = true;
            this.$store.dispatch('onlineOrder/show', this.$route.params.id).then(res => {
                this.payment_status = res.data.data.payment_status;
                this.delivery_boy = res.data.data.delivery_boy ? res.data.data.delivery_boy.id : 0;
                this.order_status = res.data.data.status;
                this.loading.isActive = false;
                this.deliveryBoyList();
            }).catch((error) => {
                this.loading.isActive = false;
            });
        },
        deliveryBoyList: function () {
            this.$store.dispatch('deliveryBoy/lists', {
                order_column: 'id',
                order_type: 'asc',
                delivery_zone_id: this.order.delivery_zone_id,
                status: statusEnum.ACTIVE
            });
        },
        changeStatus: function (status) {
            appService.acceptOrder().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("onlineOrder/changeStatus", {
                        id: this.$route.params.id,
                        status: status,
                    }).then((res) => {
                        this.order_status = res.data.data.status;
                        this.loading.isActive = false;
                        alertService.successFlip(
                            1,
                            this.$t("label.status")
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
        selectDeliveryBoy: function (e) {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("onlineOrder/selectDeliveryBoy", {
                    id: this.$route.params.id,
                    delivery_boy_id: e.target.value,
                }).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(
                        1,
                        this.$t("message.delivery_boy_add")
                    );
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        changePaymentStatus: function (e) {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("onlineOrder/changePaymentStatus", {
                    id: this.$route.params.id,
                    payment_status: e.target.value,
                }).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(
                        1,
                        this.$t("label.status")
                    );
                }).catch((err) => {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        orderStatus: function (e) {
            try {
                if (parseInt(e.target.value) === orderStatusEnum.RETURNED) {
                    composables.openModal('returnOrderNoteModal');
                } else {
                    this.loading.isActive = true;
                    this.$store.dispatch("onlineOrder/changeStatus", {
                        id: this.$route.params.id,
                        status: e.target.value,
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(
                            1,
                            this.$t("label.status")
                        );
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    });
                }

            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        quantityDecrement: function (id, product) {
            this.form.quantity--;
            if (this.form.quantity <= 0) {
                this.form.quantity = 1;
            }
        },
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        quantityUp: function (id, product, e) {
            let quantity = e.target.value;
            if (quantity === 0 || quantity < 0) {
                quantity = 1;
            }

            this.form.quantity = quantity;

        },
        floatNumber: function (e) {
            return appService.floatNumber(e);
        },
        quantityIncrement: function (i, product) {
            this.form.quantity++;
            if (this.form.quantity <= 0) {
                this.form.quantity = 1;
            }
        },
        removeProduct: function (product) {
            appService.destroyConfirmation().then((res) => {
                let info = {
                    order_id: product.order_id,
                    product_id: product.product_id,
                    stock_id: product.stock_id,
                    quantity: product.quantity
                }
                this.$store.dispatch('onlineOrder/deleteProduct', info)
                    .then((res) => {
                        if (res?.data?.message) {
                            alertService.success(res?.data?.message);
                        }
                    })
                    .catch((err) => {
                        if (err?.response?.data?.message) {
                            alertService.error(err?.response?.data?.message);
                        }
                    });
            }).catch((err) => {
                this.loading.isActive = false;
            })
        },
        toggleEdit(stock) {
            this.form.stock_id = stock.id
            this.form.product_id = stock.product_id;
            this.form.quantity = stock.quantity;
        },
        updateQuantity(index) {
            const currentProduct = index.quantity;
            if (currentProduct !== this.form.quantity) {
                this.form.order_id = this.order.id;
                this.$store.dispatch('onlineOrder/updateProductQuantity', this.form)
                    .then((res) => {
                        if (res?.data?.message) {
                            alertService.success(res?.data?.message);
                        }
                        this.form = {
                            product_id: null,
                            quantity: null,
                            order_id: null
                        }
                    })
                    .catch((err) => {
                        if (err?.response?.data?.message) {
                            alertService.error(err?.response?.data?.message);
                        }
                    });
            }
            else {
                this.form.product_id = null;
            }
        },
        returnOrder: function (e) {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("onlineOrder/changeStatus", {
                    id: this.$route.params.id,
                    status: orderStatusEnum.RETURNED,
                    reason: this.returnForm.reason,
                }).then((res) => {
                    this.loading.isActive = false;
                    composables.closeModal('returnOrderNoteModal');
                    this.returnForm = {
                        reason: "",
                    };
                    this.error = "";
                    alertService.successFlip(
                        1,
                        this.$t("label.status")
                    );
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.error = err.response.data.message;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
    }
}
</script>