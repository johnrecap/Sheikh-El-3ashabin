<template>
    <div v-if="!isSectional && products.length > 0" v-for="product in products"
        class="sm:p-2 rounded-xl sm:rounded-2xl flex flex-col sm:border sm:border-gray-100 transition-all duration-300 sm:hover:shadow-hover group">
        <figure class="relative overflow-hidden rounded-xl flex-shrink-0 isolate cursor-pointer"
            @click.prevent="productDetails(product)">
            <label
                class="capitalize max-w-[70px] text-xs font-semibold rounded-xl py-1 px-2 shadow-badge absolute top-3 left-3 z-10 bg-secondary text-white rtl:right-2"
                v-if="product.is_offer && product.flash_sale">{{ $t('label.flash_sale') }}</label>
            <img :src="product.cover" alt="product"
                class="w-full rounded-xl transition-all duration-300 group-hover:scale-105 group-hover:rotate-3"
                style="aspect-ratio: 1/1; object-fit: fill;">
            <button @click.stop.prevent="wishlist(product, product.wishlist = !product.wishlist)"
                :class="[product.wishlist ? 'lab-fill-heart' : 'lab-line-heart', product.wishlist ? 'text-primary' : 'text-secondary']"
                class="w-5 h-5 leading-5 sm:w-7 sm:h-7 sm:leading-7 rounded-full text-center text-xs sm:text-base shadow-badge absolute top-2 ltr:right-2 rtl:left-2 z-10 bg-white">
            </button>

            <button
                class="h-7 leading-7 px-3 sm:h-9 sm:leading-9 sm:px-4 rounded-3xl flex items-center gap-1.5 absolute bottom-2 ltr:right-2 rtl:left-2 bg-primary text-white">
                <i class="lab-fill-bag-check mobile:text-xs sm:-mt-0.5"></i>
                <span class="capitalize text-xs sm:text-sm font-semibold leading-[normal]">{{ $t('button.add') }}</span>
            </button>
        </figure>
        <div class="pt-4 pb-1 mobile:px-1.5 h-full flex flex-col gap-3 justify-between">
            <h3 class="capitalize text-xs sm:text-sm font-medium">
                {{ product.name }}
            </h3>
            <div>
                <p class="text-[10px] mobile:leading-none sm:text-xs capitalize mb-1">{{ product.unit }}</p>

                <h4 class="flex flex-wrap items-center gap-x-2 sm:gap-x-3" v-if="product.is_offer">
                    <span class="text-base sm:text-lg font-bold text-primary"> {{ product.discounted_price }} </span>
                    <del class="text-xs sm:text-sm font-semibold text-slate-400">{{
                        product.currency_price }}</del>
                </h4>

                <h4 class="flex flex-wrap items-center gap-x-2 sm:gap-x-3" v-else>
                    <span class="text-base sm:text-lg font-bold text-primary"> {{ product.currency_price }} </span>
                </h4>
            </div>
        </div>
    </div>


    <section class="sm:mb-10" v-if="isSectional && products.length > 0">
        <div class="container">
            <h2 class="text-xl sm:text-4xl font-bold -mb-6 sm:-mb-10">
                {{ productSection }}
            </h2>

            <Swiper :speed="1000" :loop="true" :navigation="true" :modules="modules" class="navigate-swiper"
                :breakpoints="{
                    '0': { slidesPerView: 'auto', spaceBetween: 16 },
                    '640': { slidesPerView: 3, spaceBetween: 24 },
                    '768': { slidesPerView: 4, spaceBetween: 24 },
                    '1024': { slidesPerView: 5, spaceBetween: 24 },
                }">
                <SwiperSlide class="mobile:!w-40" v-if="products.length > 0" v-for="product in products">
                    <div
                        class="sm:p-2 rounded-xl sm:rounded-2xl flex flex-col sm:border sm:border-gray-100 transition-all duration-300 sm:hover:shadow-hover group">
                        <figure class="relative overflow-hidden rounded-xl flex-shrink-0 isolate cursor-pointer"
                            @click.prevent="productDetails(product)">
                            <img :src="product.cover" alt="product"
                                class="w-full rounded-xl transition-all duration-300 group-hover:scale-105 group-hover:rotate-3"
                                style="aspect-ratio: 1/1; object-fit: fill;">
                            <button @click.stop.prevent="wishlist(product, product.wishlist = !product.wishlist)"
                                :class="[product.wishlist ? 'lab-fill-heart' : 'lab-line-heart', product.wishlist ? 'text-primary' : 'text-secondary']"
                                class="w-5 h-5 leading-5 sm:w-7 sm:h-7 sm:leading-7 rounded-full text-center text-xs sm:text-base shadow-badge absolute top-2 ltr:right-2 rtl:left-0 z-10 bg-white">
                            </button>
                            <button
                                class="h-7 leading-7 px-3 sm:h-9 sm:leading-9 sm:px-4 rounded-3xl flex items-center gap-1.5 absolute bottom-2 ltr:right-2 rtl:left-2 bg-primary text-white">
                                <i class="lab-fill-bag-check mobile:text-xs sm:-mt-0.5"></i>
                                <span class="capitalize text-xs sm:text-sm font-semibold leading-[normal]">{{
                                    $t('button.add') }}</span>
                            </button>
                        </figure>
                        <div class="pt-4 pb-1 mobile:px-1.5 h-full flex flex-col gap-3 justify-between">
                            <h3 class="capitalize text-xs sm:text-sm font-medium">
                                {{ product.name }}
                            </h3>
                            <div>
                                <p class="text-[10px] mobile:leading-none sm:text-xs capitalize mb-1">{{ product.unit }}
                                </p>

                                <h4 class="flex flex-wrap items-center gap-x-2 sm:gap-x-3" v-if="product.is_offer">
                                    <span class="text-base sm:text-lg font-bold text-primary"> {{
                                        product.discounted_price }} </span>
                                    <del class="text-xs sm:text-sm font-semibold text-slate-400">{{
                                        product.currency_price }}</del>
                                </h4>

                                <h4 class="flex flex-wrap items-center gap-x-2 sm:gap-x-3" v-else>
                                    <span class="text-base sm:text-lg font-bold text-primary"> {{ product.currency_price
                                        }} </span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </SwiperSlide>
            </Swiper>
        </div>
    </section>

    <ProductDetailsModalComponent ref="productDetailsRef" />
</template>

<script>
import router from "../../../router";
import composables from "../../../composables/composables";
import ProductDetailsModalComponent from './ProductDetailsModalComponent.vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Pagination } from 'swiper/modules';
export default {
    name: "ProductListComponent",
    components: {
        ProductDetailsModalComponent,
        Swiper,
        SwiperSlide,
    },
    props: {
        "products": "object",
        "isSectional": {
            type: Boolean,
            default: false
        },
        "productSection": {
            type: String,
            default: ""
        }
    },
    data() {
        return {
            modules: [Navigation, Pagination],
        }
    },
    methods: {
        wishlist: function (product, toggle) {
            this.$store.dispatch("frontendWishlist/toggle", {
                product_id: product.id,
                toggle: toggle
            }).then((res) => {
                if (this.$route.name === "frontend.wishlist") {
                    this.$store.dispatch("frontendProduct/wishlistProducts", {
                        paginate: 1,
                        per_page: 32,
                        page: 1
                    }).then((res) => {
                    }).catch((err) => {
                    });
                }
            }).catch((err) => {
                if (err.response.status === 401) {
                    product.wishlist = false;
                    router.push({ name: "auth.login" });
                }
            });
        },
        productDetails: function (product) {
            const detailsRef = this.$refs.productDetailsRef;
            detailsRef?.show(product.slug);
        }
    }
}
</script>