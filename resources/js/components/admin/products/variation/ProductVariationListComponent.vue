<template>
    <div class="db-card-header">
        <h3 class="db-card-title">{{ $t('label.variation') }}</h3>
        <div class="db-card-filter">
            <ProductVariationCreateComponent :attributeProps="attributeProps" />
        </div>
    </div>
    <div class="db-card-body">
        <ul v-if="variations.length > 0" class="rounded border border-gray-200 w-fit sm:w-full">
            <li class="px-4 py-3 flex flex-wrap items-center gap-2.5 border-b last:border-none border-gray-200"
                v-for="variation in variations" :key="variation">
                <span class="flex-auto flex items-center">
                    <span v-for="(option, key) in variation.options"
                        class="text-base font-medium capitalize tracking-wide whitespace-nowrap after:content-['\e048'] rtl:after:rotate-180 after:font-icon after:font-bold after:text-sm after:ml-1.5">
                        {{ key }} :: {{ option }}
                    </span>
                    <span
                        class="text-base font-medium capitalize tracking-wide whitespace-nowrap after:content-['\e048'] rtl:after:rotate-180 after:font-icon after:font-bold after:text-sm after:ml-1.5">
                        {{ $t('label.price') }} :: {{ variation.flat_price }}
                    </span>
                    <div class="flex-auto flex sm:justify-end gap-2">

                        <button type="button" @click="showVariationBarcode(variation)" class="db-table-action">
                            <i class="lab lab-fill-scan-barcode text-cyan-500 bg-cyan-100"></i>
                            <span class="db-tooltip">{{ $t('button.barcode') }}</span>
                        </button>
                        <SmIconModalEditComponent @click="editVariation(variation.id)" />
                        <SmIconDeleteComponent @click="destroyVariation(variation.id)" />
                    </div>
                </span>
            </li>
        </ul>
    </div>

    <ProductVariationBarcodeComponent :barcodeProps="barcodeProps" />
</template>

<script>
import alertService from "../../../../services/alertService";
import appService from "../../../../services/appService";
import SmIconModalEditComponent from "../../components/buttons/SmIconModalEditComponent.vue";
import SmIconViewComponent from "../../components/buttons/SmIconViewComponent.vue";
import SmIconDeleteComponent from "../../components/buttons/SmIconDeleteComponent.vue";
import ProductVariationCreateComponent from "./ProductVariationCreateComponent.vue";
import ProductVariationBarcodeComponent from "./ProductVariationBarcodeComponent.vue";
import _ from "lodash";
import composables from "../../../../composables/composables";

export default {
    name: "ProductVariationListComponent",
    components: { ProductVariationCreateComponent, SmIconDeleteComponent, SmIconModalEditComponent, SmIconViewComponent, ProductVariationBarcodeComponent },
    data() {
        return {
            loading: {
                isActive: false
            },
            productId: null,
            barcodeProps: {
                variation_id: null,
                sku: null,
                barcode_image: '',
                product_name: '',
                category_name: '',
                price: '',
                variation_name: ''
            },
            attributeProps: {
                price: null,
                sku: null,
                barcode: null,
                elements: {},
                attribute: []
            },
        }
    },
    computed: {
        variations: function () {
            return this.$store.getters['productVariation/singleTree'];
        }
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.productId = this.$route.params.id;
            this.$store.dispatch('productVariation/singleTree', this.productId).then(res => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            });
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err.response.data.message);
        }
    },
    methods: {
        editVariation: function (productVariation) {
            composables.openModal('variationModal');
            this.loading.isActive = true;
            this.$store.dispatch('productVariation/edit', {
                productId: this.productId,
                id: productVariation
            }).then((res) => {
                this.loading.isActive = false;
                _.forEach(res.data.data, (element) => {
                    this.recursiveVariation(element);
                });
            }).catch((err) => {
                this.loading.isActive = false;
            })
        },
        showVariationBarcode: function (variation) {
            composables.openModal('variationBarcodeModal');
            this.barcodeProps.variation_id = variation.id;
            this.barcodeProps.sku = variation.sku;
            this.barcodeProps.barcode_image = variation.media[0].original_url;
            this.barcodeProps.product_name = variation.product_name;
            this.barcodeProps.category_name = variation.category_name;
            this.barcodeProps.price = variation.currency_price;
            const optionsArray = Object.entries(variation.options)
                .map(([key, value]) => `${key} / ${value}`);
            this.barcodeProps.variation_name = optionsArray.length > 1
                ? optionsArray.join(', ')
                : optionsArray.join('');
        },
        destroyVariation: function (id) {
            appService.destroyConfirmation().then(res => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch('productVariation/destroy', {
                        productVariationId: id,
                        productId: this.productId
                    }).then((res) => {
                        this.loading.isActive = false;
                        alertService.successFlip(null, this.$t('label.variation'));
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.message);
                    })
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }).catch((err) => {
                this.loading.isActive = false;
            })
        },
        recursiveVariation: function (data) {
            this.attributeProps.elements[data.product_attribute_id] = data.product_attribute_option_id;
            if (data.sku !== null) {
                this.attributeProps.price = data.flat_price;
                this.attributeProps.sku = data.sku;
                this.attributeProps.barcode = data.barcode;
            }
            if (data.children) {
                _.forEach(data.children, (element) => {
                    this.recursiveVariation(element);
                });
            }
        },
    }
}
</script>