<template>
    <LoadingComponent :props="loading" />

    <div class="row p-4">
        <div class="col-12 sm:col-6 lg:col-5">
            <img :src="product.image" alt="products" class="w-full rounded-2xl">
        </div>

        <div class="col-12 sm:col-6 lg:col-7 lg:pl-10">
            <h2 class="text-3xl sm:text-4xl font-bold capitalize mb-5">{{ product.name }}</h2>
            <h3 class="flex items-start gap-4 mb-5">
                <span class="text-2xl font-bold">
                    {{
                        currencyFormat(temp.price, setting.site_digit_after_decimal_point,
                            setting.site_default_currency_symbol, setting.site_currency_position)
                    }}
                </span>
                <del v-if="product.is_offer" class="text-lg font-bold text-storeKing-red">
                    {{
                        currencyFormat(temp.oldPrice, setting.site_digit_after_decimal_point,
                            setting.site_default_currency_symbol, setting.site_currency_position)
                    }}
                </del>
            </h3>

            <VariationComponent v-if="initialVariations.length > 0 && variationComponent"
                :method="selectedVariationMethod" :variations="initialVariations" />

            <dl class="flex flex-wrap items-center gap-x-6 gap-y-3 mb-8">
                <dt class="capitalize text-lg font-semibold">{{ $t('label.quantity') }}:</dt>
                <dd class="flex items-center gap-6">
                    <div class="flex items-center gap-1 w-28 p-1 rounded-full bg-[#F7F7FC]">
                        <button @click.prevent="quantityDecrement" type="button"
                            :class="temp.quantity == 1 || temp.stock < 1 ? 'cursor-not-allowed' : ''"
                            class="lab-fill-circle-minus text-lg leading-none transition-all duration-300 hover:text-primary">
                        </button>


                        <input v-if="temp.sell_by_fraction == enums.askEnum.NO" type="number"
                            v-on:keypress="onlyNumber($event)" v-model="temp.quantity" v-on:keyup="quantityUp"
                            class="text-center w-full h-5 text-sm font-medium">


                        <input v-else v-model="temp.quantity" v-on:keypress="floatNumber($event)"
                            v-on:keyup="quantityUp" class="text-center w-full h-5 text-sm font-medium">

                        <button @click.prevent="quantityIncrement" type="button"
                            :class="temp.stock == temp.quantity ? 'cursor-not-allowed' : ''"
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
                    {{ currencyFormat(temp.totalPrice, setting.site_digit_after_decimal_point,
                        setting.site_default_currency_symbol, setting.site_currency_position) }}
                </dd>
            </dl>
            <div class="flex flex-wrap items-center gap-8 mb-10">
                <button @click.prevent="addToCart" :disabled="enableAddToCardButton" type="button"
                    :class="enableAddToCardButton === false ? 'shadow-btn-primary !bg-primary' : ''"
                    class="flex items-center gap-3 px-8 h-12 leading-12 rounded-full transition-all duration-500 bg-slate-400 text-white">
                    <i class="lab-line-bag text-xl"></i>
                    <span class="whitespace-nowrap font-bold">{{ $t("button.add_to_cart") }}</span>
                </button>
            </div>
        </div>
    </div>
</template>


<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import targetService from "../../../services/targetService";
import ProductListComponent from "../components/ProductListComponent.vue";
import VariationComponent from "../components/VariationComponent.vue";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import askEnum from "../../../enums/modules/askEnum";

export default {
    name: "ProductDetailsComponent",
    components: {
        VariationComponent,
        ProductListComponent,
        LoadingComponent
    },
    props: {
        "method": { type: Function },
        "productId": { type: Number }
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            props: {
                search: {
                    product_id: null,
                }
            },
            enums: {
                askEnum: askEnum
            },
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
                totalPrice: 0
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
                quantity: 1,
                discount: 0,
                price: 0,
                oldPrice: 0,
                totalPrice: 0,
                sell_by_fraction: askEnum.NO
            },
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        categories: function () {
            return this.$store.getters["posProductCategory/ancestorsAndSelf"];
        },
        initialVariations: function () {
            return this.$store.getters["posProductVariation/initialVariation"];
        },
        product: function () {
            return this.$store.getters["posProduct/show"];
        },
        images: function () {
            return this.$store.getters["posProduct/showImages"];
        },
    },
    mounted() {
        this.modalShow();
        this.show();
    },
    methods: {
        onlyNumber: function (e) {
            return appService.onlyNumber(e);
        },
        floatNumber: function (e) {
            return appService.floatNumber(e);
        },
        currencyFormat: function (amount, decimal, currency, position) {
            return appService.currencyFormat(amount, decimal, currency, position);
        },
        modalShow: function () {
            targetService.showTarget("variation-modal", 'modal-active');
        },
        show: function () {
            if (typeof this.$props.productId !== "undefined") {
                this.loading.isActive = true;
                this.props.search.product_id = this.$props.productId;
                this.$store.dispatch("posProduct/show", this.props.search).then((res) => {
                    this.initProduct = {
                        isVariation: false,
                        variationId: null,
                        sku: res.data.data.sku,
                        stock: res.data.data.stock,
                        quantity: res.data.data.sell_by_fraction == askEnum.YES ? (res.data.data.stock < 1 ? res.data.data.stock : appService.floatFormat('1.000000', this.setting.site_digit_after_decimal_point)) : 1,
                        discount: 0,
                        price: res.data.data.price,
                        oldPrice: res.data.data.old_price,
                        totalPrice: res.data.data.sell_by_fraction == askEnum.YES && res.data.data.stock < 1 ? (res.data.data.price * res.data.data.stock) : res.data.data.price
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
                        quantity: res.data.data.sell_by_fraction == askEnum.YES ? (res.data.data.stock < 1 ? "1.00" : appService.floatFormat('1.000000', this.setting.site_digit_after_decimal_point)) : 1,
                        discount: 0,
                        price: res.data.data.price,
                        oldPrice: res.data.data.old_price,
                        totalPrice: res.data.data.sell_by_fraction == askEnum.YES && res.data.data.stock < 1 ? (res.data.data.price * res.data.data.stock) : res.data.data.price,
                        sell_by_fraction: res.data.data.sell_by_fraction
                    };

                    this.$store.dispatch("posProductCategory/ancestorsAndSelf", res.data.data.category_slug).then((categoryRes) => {
                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                    });

                    this.$store.dispatch("posProductVariation/initialVariation", res.data.data.id).then((initVariationRes) => {
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
                }).catch((err) => {
                    this.loading.isActive = false;
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

            if (variation) {
                this.selectedVariation = variation;

                this.temp.isVariation = true;
                this.temp.variationId = variation.id;
                this.temp.sku = variation.sku;
                this.temp.stock = variation.stock;
                this.temp.quantity = appService.floatFormat('1.000000', this.setting.site_digit_after_decimal_point);
                this.temp.discount = 0;
                this.temp.price = variation.price;
                this.temp.oldPrice = variation.old_price;
                this.temp.totalPrice = variation.price;

                if (variation.stock > 0) {
                    this.enableAddToCardButton = false;
                }
            }
        },
        quantityUp: function () {

            const isValid = /^[0-9]*\.?[0-9]*$/.test(this.temp.quantity);
            if (isValid) {
                this.inputValue = this.temp.quantity;
            } else {
                this.temp.quantity = this.inputValue;
            }


            if (this.temp.sell_by_fraction == this.enums.askEnum.YES) {
                let quantity = "" + this.temp.quantity;
                if (quantity.includes('.')) {
                    quantity = quantity.split('.');
                    if (quantity[1].length > this.setting.site_digit_after_decimal_point) {
                        this.temp.quantity = quantity[0] + '.' + quantity[1].substring(0, this.setting.site_digit_after_decimal_point);
                    }
                } else if (this.temp.quantity < 0) {
                    this.temp.quantity = this.temp.stock;
                }

                if (this.temp.quantity > this.temp.stock) {
                    this.temp.quantity = this.temp.stock;
                }
            }
            else if (this.temp.quantity == 0 || this.temp.quantity < 0) {
                this.temp.quantity = 1;
            }
            else if (this.temp.quantity > this.temp.stock) {
                this.temp.quantity = Math.floor(this.temp.stock);

            }

            if (this.temp.quantity && this.temp.quantity > 0) {
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
                this.temp.quantity = this.temp.stock;
            }
            this.totalPriceSetup();
        },
        quantityDecrement: function () {
            this.temp.quantity--;
            this.temp.quantity = parseFloat(this.temp.quantity.toFixed(this.setting.site_digit_after_decimal_point));
            if (this.temp.quantity <= 0) {
                if (this.temp.sell_by_fraction == this.enums.askEnum.YES) {
                    this.temp.quantity = appService.floatFormat('1.000000', this.setting.site_digit_after_decimal_point);
                } else {
                    this.temp.quantity = 1;
                }
            }
            if (this.temp.sell_by_fraction == this.enums.askEnum.YES && this.temp.quantity > this.temp.stock) {
                this.temp.quantity = this.temp.stock;
            }

            this.totalPriceSetup();
        },
        totalPriceSetup: function () {
            this.temp.totalPrice = (this.temp.price * +this.temp.quantity);
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
                quantity: +this.temp.quantity,
                discount: this.temp.discount,
                price: this.temp.price,
                old_price: this.temp.oldPrice,
                total_price: this.temp.totalPrice,
                sell_by_fraction: this.temp.sell_by_fraction
            }

            if (this.selectedVariation) {
                this.$store.dispatch("posProductVariation/ancestorsToString", this.selectedVariation.id).then((res) => {
                    this.productArray.variation_names = res.data.data;
                    this.variationComponent = false;
                    this.$store.dispatch("posCart/lists", this.productArray).then((res) => {
                        targetService.hideTarget("variation-modal", 'modal-active');
                        this.method();
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
                    }).catch((err) => {
                        alertService.error(this.$t('message.maximum_quantity'));
                        this.enableAddToCardButton = false;
                        this.variationComponent = true;
                        this.selectedVariation = null;
                        this.temp.stock = this.initProduct.stock;
                        this.temp.quantity = this.initProduct.quantity;
                    });
                }).catch((err) => {
                });
            } else {
                this.$store.dispatch("posCart/lists", this.productArray).then((res) => {
                    targetService.hideTarget("variation-modal", 'modal-active');
                    this.method();
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
                }).catch((err) => {
                    alertService.error(this.$t('message.maximum_quantity'));
                    this.enableAddToCardButton = false;
                    this.selectedVariation = null;
                    this.temp.stock = this.initProduct.stock;
                    this.temp.quantity = this.initProduct.quantity;
                });
            }
        }
    }
}
</script>