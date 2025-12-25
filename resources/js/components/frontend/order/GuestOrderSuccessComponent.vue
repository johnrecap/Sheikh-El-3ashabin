<template>
    <LoadingComponent :props="loading" />
    <section class="py-8 sm:py-12 lg:py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto text-center">
                <!-- Success Icon -->
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="lab-fill-tick-circle text-5xl text-green-500"></i>
                </div>

                <!-- Success Message -->
                <h1 class="text-2xl sm:text-3xl font-bold text-heading mb-4">
                    {{ $t('message.order_success_title') }}
                </h1>
                <p class="text-paragraph mb-8">
                    {{ $t('message.order_success_message') }}
                </p>

                <!-- Order Details Card -->
                <div v-if="order" class="bg-white rounded-2xl shadow-card p-6 mb-8 text-right">
                    <h2 class="font-bold text-lg mb-4 border-b pb-2">{{ $t('label.order_details') }}</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="font-medium">{{ order.order_serial_no }}</span>
                            <span class="text-paragraph">{{ $t('label.order_number') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">{{ order.total_currency_price }}</span>
                            <span class="text-paragraph">{{ $t('label.total') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">{{ order.payment_method_name }}</span>
                            <span class="text-paragraph">{{ $t('label.payment_method') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-yellow-600">{{ order.status_name }}</span>
                            <span class="text-paragraph">{{ $t('label.status') }}</span>
                        </div>
                    </div>

                    <!-- Guest Info -->
                    <div v-if="order.is_guest_order" class="mt-6 pt-4 border-t">
                        <h3 class="font-medium mb-3">{{ $t('label.guest_info') }}</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="text-paragraph">{{ $t('label.name') }}:</span> {{ order.guest_name }}</p>
                            <p><span class="text-paragraph">{{ $t('label.email') }}:</span> {{ order.guest_email }}</p>
                            <p><span class="text-paragraph">{{ $t('label.phone') }}:</span> {{ order.guest_phone }}</p>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div v-if="order.order_address" class="mt-6 pt-4 border-t">
                        <h3 class="font-medium mb-3">{{ $t('label.delivery_address') }}</h3>
                        <p class="text-sm text-paragraph">{{ order.order_address.full_address }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <router-link :to="{ name: 'frontend.home' }"
                        class="field-button font-semibold tracking-wide normal-case">
                        {{ $t('button.continue_shopping') }}
                    </router-link>
                </div>

                <!-- Note for Guest -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <i class="lab-fill-info-circle ml-1"></i>
                        {{ $t('message.guest_order_note') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import axios from "axios";

export default {
    name: "GuestOrderSuccessComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false
            },
            order: null
        }
    },
    mounted() {
        this.fetchOrder();
    },
    methods: {
        fetchOrder: function() {
            const orderId = this.$route.params.id;
            if (orderId) {
                this.loading.isActive = true;
                axios.get(`/api/frontend/order/show/${orderId}`)
                    .then(res => {
                        this.order = res.data.data;
                        this.loading.isActive = false;
                    })
                    .catch(err => {
                        this.loading.isActive = false;
                        // If can't fetch, still show success message
                    });
            }
        }
    }
}
</script>
