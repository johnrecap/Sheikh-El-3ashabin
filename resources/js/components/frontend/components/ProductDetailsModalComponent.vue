<template>
    <LoadingComponent :props="loading" />

    <div :id="'product-modal' + productSlug"
        class=" fixed inset-0 z-50 p-3 w-screen h-screen overflow-y-auto bg-black/50 transition-all duration-300 opacity-0 invisible">
        <div class="max-w-3xl w-full rounded-xl mx-auto bg-white transition-all duration-300">


            <div class="flex items-center justify-between gap-4 py-3.5 px-4 border-b border-slate-100">
                <h3 class="text-lg font-semibold capitalize">{{ $t('label.product') }}</h3>
                <button @click.prevent="closeModal('product-modal' + productSlug)"
                    class="lab-line-circle-cross text-lg text-danger"></button>
            </div>

            <div class="p-4 sm:p-5 border-b border-gray-100">
                <div class="row">
                    <div class="col-12 sm:col-6 lg:col-5">
                        <img :src="product.image" alt="product" class="w-full">
                    </div>
                    <div class="col-12 sm:col-6 lg:col-7">
                        <h2 class="text-2xl font-medium capitalize mb-2">{{ product.name }}</h2>
                        <p class="text-[10px] mobile:leading-none sm:text-base capitalize mb-2">{{ product.unit }}</p>
                        <h3 class="flex flex-wrap items-center gap-x-2 sm:gap-x-3 pb-3 mb-4 border-b border-gray-100"
                            v-if="product.is_offer">
                            <span class="text-base sm:text-2xl font-bold text-primary"> {{ product.currency_price }}
                            </span>
                            <del class="text-xs sm:text-sm font-semibold text-slate-400">{{
                                product.old_currency_price }}</del>
                        </h3>

                        <h3 class="flex flex-wrap items-center gap-x-2 sm:gap-x-3 pb-3 mb-4 border-b border-gray-100"
                            v-else>
                            <span class="text-base sm:text-2xl font-bold text-primary"> {{ product.currency_price }}
                            </span>
                        </h3>

                        <VariationComponent v-if="initialVariations.length > 0 && variationComponent"
                            :method="selectedVariationMethod" :variations="initialVariations" />

                        <dl class="flex flex-wrap items-center gap-x-6 gap-y-3 mb-6">
                            <dt class="capitalize text-lg font-semibold">{{ $t('label.quantity') }}:</dt>
                            <dd class="flex items-center gap-6">
                                <div class="flex items-center gap-1 w-20 p-1 rounded-full bg-[#F7F7FC]">
                                    <button @click.prevent="quantityDecrement" type="button"
                                        :class="temp.quantity === 1 ? 'cursor-not-allowed' : ''"
                                        class="lab-fill-circle-minus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
                                    <input type="number" v-model="temp.quantity" v-on:keypress="onlyNumber($event)"
                                        v-on:keyup="quantityUp" class="text-center w-full h-5 text-sm font-medium">
                                    <button @click.prevent="quantityIncrement" type="button"
                                        :class="temp.stock === temp.quantity ? 'cursor-not-allowed' : temp.quantity === temp.maximum_purchase_quantity ? 'cursor-not-allowed' : ''"
                                        class="lab-fill-circle-plus text-lg leading-none transition-all duration-300 hover:text-primary"></button>
                                </div>
                                <div v-if="!initialVariations.length || selectedVariation != null">
                                    <p v-if="temp.stock > 0" class="capitalize">
                                        {{ $t('label.available') }}:
                                        <b>({{ temp.stock }}) </b>
                                        {{ product.unit }}
                                    </p>
                                    <p v-else class="capitalize text-danger">
                                        {{ $t('label.stock_out') }}
                                    </p>
                                </div>
                            </dd>
                        </dl>


                        <dl v-if="temp.quantity > 0" class="flex flex-wrap items-center gap-x-6 gap-y-3 mb-8">
                            <dt class="capitalize text-lg font-semibold">{{ $t('label.total_price') }}:</dt>
                            <dd class="flex items-center gap-6 text-green-500 font-semibold text-lg">
                                {{
                                    currencyFormat(temp.totalPrice, setting.site_digit_after_decimal_point,
                                        setting.site_default_currency_symbol, setting.site_currency_position)
                                }}
                            </dd>
                        </dl>

                        <div class="flex flex-wrap items-center gap-6">
                            <button @click.prevent="addToCart" :disabled="enableAddToCardButton" type="button"
                                :class="enableAddToCardButton === false ? 'shadow-btn-primary !bg-primary' : ''"
                                class="flex items-center gap-3 px-8 h-12 leading-12 rounded-full transition-all duration-500 bg-slate-400 text-white">
                                <i class="lab-line-bag text-xl"></i>
                                <span class="whitespace-nowrap font-bold">{{ $t("button.add_to_cart") }}</span>
                            </button>
                            <button type="button" @click="wishlist(product.wishlist = !product.wishlist)"
                                :class="product.wishlist ? 'text-primary' : 'text-secondary'"
                                class="flex items-center gap-3 px-8 h-12 leading-12 rounded-full transition-all duration-500 shadow-btn-secondary bg-white">
                                <i :class="product.wishlist ? 'lab-fill-heart' : 'lab-line-heart'"
                                    class="lab-line-heart text-xl"></i>
                                <span class="whitespace-nowrap font-bold">{{ $t('button.favorite') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-5 text-sm leading-6">
                <p class="text-description" v-html="product.details"></p>
            </div>

        </div>
    </div>
</template>

<script>
import composables from "../../../composables/composables";
import LoadingComponent from "../components/LoadingComponent.vue";
import VariationComponent from "../components/VariationComponent.vue";
import targetService from "../../../services/targetService";
import router from "../../../router";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import { useHead } from '@vueuse/head';

export default {
    name: 'ProductDetailsModalComponent',
    components: {
        LoadingComponent,
        VariationComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                search: {
                    slug: null,
                }
            },
            productSlug: null,
            enableAddToCardButton: true,
            selectedVariation: null,
            productArray: {},
            variationComponent: false,
            initProduct: {
                isVariation: false,
                variationId: null,
                sku: null,
                stock: 0,
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0,
                maximum_purchase_quantity: 0,
                file_attachment: null
            },
            temp: {
                name: "",
                image: "",
                isVariation: false,
                variationId: null,
                productId: 0,
                sku: null,
                stock: 0,
                taxes: {},
                shipping: {},
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0,
                maximum_purchase_quantity: 0,
                file_attachment: null
            },
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        categories: function () {
            return this.$store.getters["frontendProductCategory/ancestorsAndSelf"];
        },
        initialVariations: function () {
            return this.$store.getters["frontendProductVariation/initialVariation"];
        },
        product: function () {
            return this.$store.getters["frontendProduct/show"];
        },
        images: function () {
            return this.$store.getters["frontendProduct/showImages"];
        },
        cartCoupon: function () {
            return this.$store.getters['frontendCart/coupon'];
        }
    },
    methods: {
        closeModal: function (id) {
            composables.closeModal(id);
            setTimeout(() => {
                this.reset();
            }, 300);
        },
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        multiTargets: function (event, commonBtnClass, commonDivClass, targetID) {
            targetService.multiTargets(event, commonBtnClass, commonDivClass, targetID)
        },
        wishlist: function (toggle) {
            this.$store.dispatch("frontendWishlist/toggle", {
                product_id: this.product.id,
                toggle: toggle
            }).then((res) => {
            }).catch((err) => {
                if (err.response.status === 401) {
                    this.product.wishlist = false;
                    router.push({ name: "auth.login" });
                }
            });
        },
        readMore: function () {
            this.show();
        },
        show: function (productSlug) {
            this.productSlug = productSlug;
            if (typeof productSlug !== "undefined" && productSlug != null) {
                this.loading.isActive = true;
                this.props.search.slug = productSlug;
                this.$store.dispatch("frontendProduct/show", this.props.search).then((res) => {
                    this.initProduct = {
                        isVariation: false,
                        variationId: null,
                        sku: res.data.data.sku,
                        stock: res.data.data.stock,
                        quantity: 1,
                        discount: 0,
                        price: res.data.data.price,
                        oldPrice: res.data.data.old_price,
                        totalPrice: res.data.data.price,
                        maximum_purchase_quantity: res.data.data.maximum_purchase_quantity,
                        file_attachment: res.data.data.file_attachment
                    };
                    this.temp = {
                        name: res.data.data.name,
                        image: res.data.data.image,
                        isVariation: false,
                        variationId: null,
                        productId: res.data.data.id,
                        sku: res.data.data.sku,
                        stock: res.data.data.stock,
                        taxes: res.data.data.taxes,
                        shipping: res.data.data.shipping,
                        quantity: 1,
                        discount: 0,
                        price: res.data.data.price,
                        oldPrice: res.data.data.old_price,
                        totalPrice: res.data.data.price,
                        maximum_purchase_quantity: res.data.data.maximum_purchase_quantity,
                        file_attachment: res.data.data.file_attachment
                    };


                    this.$store.dispatch("frontendProductCategory/ancestorsAndSelf", res.data.data.category_slug).then((categoryRes) => {
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                    });

                    this.$store.dispatch("frontendProductVariation/initialVariation", res.data.data.id).then((initVariationRes) => {
                        if (initVariationRes.data.data.length > 0) {
                            this.variationComponent = true;
                        }

                        if (!initVariationRes.data.data.length && res.data.data.stock > 0) {
                            this.enableAddToCardButton = false;
                        }
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                    });

                    if (res.data.data?.seo && res.data.data?.seo?.title && res.data.data?.seo?.description) {
                        let metaData = [
                            { name: 'title', content: res.data.data.seo.title },
                            { name: 'description', content: res.data.data.seo.description },
                        ];

                        if (res.data.data.seo.thumb && res.data.data.seo.cover) {
                            metaData.push({ content: res.data.data.seo.thumb });
                            metaData.push({ content: res.data.data.seo.cover });
                        }

                        useHead({
                            title: this.setting.company_name + ' - ' + res.data.data.seo.title,
                            meta: metaData
                        });
                    }

                    composables.openModal('product-modal' + productSlug);
                }).catch((err) => {
                    this.loading.isActive = false;
                    if (err.response.data.message) {
                        alertService.error(err.response.data.message);
                    }
                });
            }
        },
        selectedVariationMethod: function (variation) {
            this.enableAddToCardButton = true;
            this.selectedVariation = null;

            this.temp.isVariation = this.initProduct.isVariation;
            this.temp.variationId = this.initProduct.variationId;
            this.temp.sku = this.initProduct.sku;
            this.temp.stock = this.initProduct.stock;
            this.temp.quantity = this.initProduct.quantity;
            this.temp.discount = this.initProduct.discount;
            this.temp.price = this.initProduct.price;
            this.temp.oldPrice = this.initProduct.oldPrice;
            this.temp.totalPrice = this.initProduct.price;
            this.temp.maximum_purchase_quantity = this.initProduct.maximum_purchase_quantity;
            this.temp.file_attachment = this.initProduct.file_attachment;

            if (variation) {
                this.selectedVariation = variation;

                this.temp.isVariation = true;
                this.temp.variationId = variation.id;
                this.temp.sku = variation.sku;
                this.temp.stock = variation.stock;
                this.temp.quantity = 1;
                this.temp.discount = 0;
                this.temp.price = variation.price;
                this.temp.oldPrice = variation.old_price;
                this.temp.totalPrice = variation.price;
                this.temp.maximum_purchase_quantity = this.initProduct.maximum_purchase_quantity;
                this.temp.file_attachment = this.initProduct.file_attachment;

                if (variation.stock > 0) {
                    this.enableAddToCardButton = false;
                }
            }
        },
        quantityUp: function () {
            if (this.temp.quantity === 0) {
                this.temp.quantity = 1;
            }
            if (this.temp.quantity > this.temp.stock) {
                this.temp.quantity = this.temp.stock
            }

            if (this.temp.quantity > this.temp.maximum_purchase_quantity) {
                alertService.error(this.$t('message.purchase_limit_exceeded'));
                this.temp.quantity = this.temp.maximum_purchase_quantity
            }

            if (this.temp.quantity) {
                this.enableAddToCardButton = false;
            } else {
                this.enableAddToCardButton = true;
            }
            this.totalPriceSetup();
        },
        quantityIncrement: function () {
            this.temp.quantity++;
            if (this.temp.quantity <= 0) {
                this.temp.quantity = 1;
            }

            if (this.temp.quantity > this.temp.stock) {
                this.temp.quantity--;
            }
            if (this.temp.quantity > this.temp.maximum_purchase_quantity) {
                alertService.error(this.$t('message.purchase_limit_exceeded'));
                this.temp.quantity--;
            }
            this.totalPriceSetup();
        },
        quantityDecrement: function () {
            this.temp.quantity--;
            if (this.temp.quantity <= 0) {
                this.temp.quantity = 1;
            }
            this.totalPriceSetup();
        },
        totalPriceSetup: function () {
            this.temp.totalPrice = (this.temp.price * this.temp.quantity);
        },
        addToCart: function () {
            this.enableAddToCardButton = true;
            this.productArray = {
                name: this.temp.name,
                product_id: this.temp.productId,
                image: this.temp.image,
                variation_names: '',
                variation_id: this.temp.variationId,
                sku: this.temp.sku,
                stock: this.temp.stock,
                taxes: this.temp.taxes,
                shipping: this.temp.shipping,
                quantity: this.temp.quantity,
                discount: this.temp.discount,
                price: this.temp.price,
                old_price: this.temp.oldPrice,
                total_price: this.temp.totalPrice,
                maximum_purchase_quantity: this.temp.maximum_purchase_quantity,
                file_attachment: this.temp.file_attachment
            }

            if (this.selectedVariation) {
                this.$store.dispatch("frontendProductVariation/ancestorsToString", this.selectedVariation.id).then((res) => {
                    this.productArray.variation_names = res.data.data;
                    this.variationComponent = false;
                    this.$store.dispatch("frontendCart/lists", this.productArray).then((res) => {
                        composables.closeModal('product-modal' + this.productSlug);
                        alertService.success(this.$t('message.add_to_cart'));
                        this.variationComponent = true;
                        this.productArray = {};
                        this.selectedVariation = null;
                        this.temp.isVariation = this.initProduct.isVariation;
                        this.temp.variationId = this.initProduct.variationId;
                        this.temp.sku = this.initProduct.sku;
                        this.temp.stock = this.initProduct.stock;
                        this.temp.quantity = this.initProduct.quantity;
                        this.temp.discount = this.initProduct.discount;
                        this.temp.price = this.initProduct.price;
                        this.temp.oldPrice = this.initProduct.oldPrice;
                        this.temp.totalPrice = this.initProduct.price;
                        this.temp.maximum_purchase_quantity = this.initProduct.maximum_purchase_quantity;
                        this.temp.file_attachment = this.initProduct.file_attachment;

                        if (Object.keys(this.cartCoupon).length !== 0) {
                            this.$store.dispatch("frontendCart/destroyCoupon").then().catch();
                            alertService.warning(this.$t('message.coupon_remove'));
                        }
                    }).catch((err) => {
                        alertService.error(this.$t('message.maximum_quantity'));
                        this.variationComponent = true;
                        this.selectedVariation = null;
                        this.temp.stock = this.initProduct.stock;
                        this.temp.quantity = this.initProduct.quantity;
                    });
                }).catch((err) => {
                });
            } else {
                this.$store.dispatch("frontendCart/lists", this.productArray).then((res) => {
                    composables.closeModal('product-modal' + this.productSlug);
                    alertService.success(this.$t('message.add_to_cart'));
                    this.enableAddToCardButton = false;
                    this.productArray = {};
                    this.selectedVariation = null;
                    this.temp.isVariation = this.initProduct.isVariation;
                    this.temp.variationId = this.initProduct.variationId;
                    this.temp.sku = this.initProduct.sku;
                    this.temp.stock = this.initProduct.stock;
                    this.temp.quantity = this.initProduct.quantity;
                    this.temp.discount = this.initProduct.discount;
                    this.temp.price = this.initProduct.price;
                    this.temp.oldPrice = this.initProduct.oldPrice;
                    this.temp.totalPrice = this.initProduct.price;
                    this.temp.maximum_purchase_quantity = this.initProduct.maximum_purchase_quantity;
                    this.temp.file_attachment = this.initProduct.file_attachment;

                    if (Object.keys(this.cartCoupon).length !== 0) {
                        this.$store.dispatch("frontendCart/destroyCoupon").then().catch();
                        alertService.warning(this.$t('message.coupon_remove'));
                    }
                }).catch((err) => {
                    alertService.error(this.$t('message.maximum_quantity'));
                    this.enableAddToCardButton = false;
                    this.selectedVariation = null;
                    this.temp.stock = this.initProduct.stock;
                    this.temp.quantity = this.initProduct.quantity;
                });
            }
        },
        reset: function () {

            this.enableAddToCardButton = true;
            this.selectedVariation = null;
            this.productArray = {};
            this.variationComponent = false;
            this.initProduct = {
                isVariation: false,
                variationId: null,
                sku: null,
                stock: 0,
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0,
                maximum_purchase_quantity: 0
            };
            this.temp = {
                name: "",
                image: "",
                isVariation: false,
                variationId: null,
                productId: 0,
                sku: null,
                stock: 0,
                taxes: {},
                shipping: {},
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0,
                maximum_purchase_quantity: 0
            };
        }
    }
}
</script>