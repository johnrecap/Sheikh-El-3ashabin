<template>
    <div class="mb-6 rounded-2xl shadow-card">
        <h4 class="font-bold capitalize p-4 border-b border-gray-100">
            {{ $t('label.guest_info') }}
        </h4>
        
        <div class="p-4 space-y-4">
            <!-- Guest Name -->
            <div>
                <label class="text-xs leading-6 capitalize mb-1 text-heading block">
                    {{ $t('label.full_name') }} *
                </label>
                <input type="text" v-model="guestInfo.name"
                    :placeholder="$t('label.enter_full_name')"
                    :class="errors.guest_name ? 'border-red-500' : ''"
                    class="h-12 w-full rounded-lg border py-2 px-3 placeholder:text-xs border-[#D9DBE9]">
                <small class="text-red-500" v-if="errors.guest_name">{{ errors.guest_name[0] }}</small>
            </div>

            <!-- Guest Phone -->
            <div>
                <label class="text-xs leading-6 capitalize mb-1 text-heading block">
                    {{ $t('label.phone') }} *
                </label>
                <input type="tel" v-model="guestInfo.phone"
                    :placeholder="$t('label.enter_phone')"
                    :class="errors.guest_phone ? 'border-red-500' : ''"
                    class="h-12 w-full rounded-lg border py-2 px-3 placeholder:text-xs border-[#D9DBE9]">
                <small class="text-red-500" v-if="errors.guest_phone">{{ errors.guest_phone[0] }}</small>
            </div>
        </div>
    </div>

    <!-- Guest Delivery Address -->
    <div class="mb-6 rounded-2xl shadow-card">
        <h4 class="font-bold capitalize p-4 border-b border-gray-100">
            {{ $t('label.delivery_address') }}
        </h4>

        <div class="p-4 space-y-4">
            <!-- Governorate -->
            <div>
                <label class="text-xs leading-6 capitalize mb-1 text-heading block">
                    {{ $t('label.governorate') }} *
                </label>
                <select v-model="guestAddress.governorate"
                    @change="onGovernorateChange"
                    :class="errors.governorate ? 'border-red-500' : ''"
                    class="h-12 w-full rounded-lg border py-2 px-3 border-[#D9DBE9]">
                    <option value="">{{ $t('label.select_governorate') }}</option>
                    <option v-for="gov in governorates" :key="gov" :value="gov">{{ gov }}</option>
                </select>
                <small class="text-red-500" v-if="errors.governorate">{{ errors.governorate[0] }}</small>
            </div>

            <!-- City -->
            <div>
                <label class="text-xs leading-6 capitalize mb-1 text-heading block">
                    {{ $t('label.city') }} *
                </label>
                <input type="text" v-model="guestAddress.city"
                    :placeholder="$t('label.enter_city')"
                    :class="errors.city ? 'border-red-500' : ''"
                    class="h-12 w-full rounded-lg border py-2 px-3 placeholder:text-xs border-[#D9DBE9]">
                <small class="text-red-500" v-if="errors.city">{{ errors.city[0] }}</small>
            </div>

            <!-- Street -->
            <div>
                <label class="text-xs leading-6 capitalize mb-1 text-heading block">
                    {{ $t('label.street') }}
                </label>
                <input type="text" v-model="guestAddress.street"
                    :placeholder="$t('label.enter_street')"
                    class="h-12 w-full rounded-lg border py-2 px-3 placeholder:text-xs border-[#D9DBE9]">
            </div>

            <!-- Building Number -->
            <div>
                <label class="text-xs leading-6 capitalize mb-1 text-heading block">
                    {{ $t('label.building_number') }}
                </label>
                <input type="text" v-model="guestAddress.building_number"
                    :placeholder="$t('label.enter_building_number')"
                    class="h-12 w-full rounded-lg border py-2 px-3 placeholder:text-xs border-[#D9DBE9]">
            </div>

            <!-- Apartment -->
            <div>
                <label class="text-xs leading-6 capitalize mb-1 text-heading block">
                    {{ $t('label.apartment_and_flat') }}
                </label>
                <input type="text" v-model="guestAddress.apartment"
                    :placeholder="$t('label.enter_apartment')"
                    class="h-12 w-full rounded-lg border py-2 px-3 placeholder:text-xs border-[#D9DBE9]">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "GuestInfoComponent",
    props: {
        errors: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            guestInfo: {
                name: '',
                phone: ''
            },
            guestAddress: {
                governorate: '',
                city: '',
                street: '',
                building_number: '',
                apartment: ''
            },
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
        }
    },
    watch: {
        guestInfo: {
            deep: true,
            handler(newVal) {
                this.$store.dispatch('frontendCart/setGuestInfo', newVal);
            }
        },
        guestAddress: {
            deep: true,
            handler(newVal) {
                this.$store.dispatch('frontendCart/setGuestAddress', newVal);
            }
        }
    },
    methods: {
        onGovernorateChange() {
            if (!this.guestAddress.governorate) {
                this.$store.dispatch('frontendCart/deliveryZone', {});
                return;
            }
            
            // Check delivery zone availability when governorate changes
            this.$store.dispatch('frontendDeliveryZone/checkByGovernorate', this.guestAddress.governorate)
                .then(res => {
                    if (res.data && res.data.data) {
                        this.$store.dispatch('frontendCart/deliveryZone', res.data.data);
                    } else {
                        this.$store.dispatch('frontendCart/deliveryZone', {});
                    }
                })
                .catch(err => {
                    this.$store.dispatch('frontendCart/deliveryZone', {});
                    // Optionally show message that this governorate is not covered
                });
        }
    },
    mounted() {
        // Initialize from store if exists
        const storedGuestInfo = this.$store.getters['frontendCart/guestInfo'];
        const storedGuestAddress = this.$store.getters['frontendCart/guestAddress'];
        
        if (storedGuestInfo && Object.keys(storedGuestInfo).length > 0) {
            this.guestInfo = { ...storedGuestInfo };
        }
        if (storedGuestAddress && Object.keys(storedGuestAddress).length > 0) {
            this.guestAddress = { ...storedGuestAddress };
        }
    }
}
</script>
