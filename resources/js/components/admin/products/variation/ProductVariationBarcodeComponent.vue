<template>
    <LoadingComponent :props="loading" />

    <div id="variationBarcodeModal" class="modal">
        <div class="modal-dialog !max-w-[400px]">
            <div class="modal-header">
                <h3 class="modal-title">{{ $t('label.barcode') }}</h3>
                <button class="modal-close fa-solid fa-xmark text-xl text-slate-400 hover:text-red-500" @click="reset">
                </button>
            </div>
            <div class="modal-body">
                <div class="row px-3 py-2 justify-center">
                    <div class="col-12 max-w-[240px] w-full bg-gray-200">
                        <div class="w-full p-1 bg-white max-w-[216px] w-full" id="variationBarcodePrint">
                            <h2 class="text-[10px] leading-tight font-bold mb-1">{{ barcodeProps.product_name }}</h2>
                            <h3 class="text-[9px] leading-tight font-medium mb-1">{{ barcodeProps.variation_name }}</h3>
                            <h4 class="text-[9px] leading-tight font-medium mb-2">{{ $t("label.category") }}: {{
                                barcodeProps.category_name }}</h4>
                            <div class="flex items-start gap-3">
                                <div class="text-center flex flex-col ">
                                    <img :src="barcodeProps.barcode_image" alt="barcode"
                                        class="w-32 h-10 flex-shrink-0">
                                    <span class="text-[9px] leading-tight font-medium">
                                        {{ barcodeProps.sku }}
                                    </span>
                                </div>

                                <div class="flex-auto">
                                    <h5 class="text-[9px] leading-tight font-medium mb-2">{{ $t("label.price") }}
                                    </h5>
                                    <h6 class="text-base leading-tight font-bold">{{ barcodeProps.price
                                        }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 hidden-print">
                        <div class="flex items-center justify-center gap-3">
                            <button v-if="barcodeProps.barcode_image" @click="downloadBarcode(barcodeProps.sku)"
                                type="button"
                                class="flex items-center justify-center gap-1.5 h-10 px-4 rounded-3xl text-white bg-primary">
                                <i class="lab lab-fill-download"></i>
                                <span class="capitalize text-sm font-medium">{{ $t('label.download') }}</span>
                            </button>
                            <PrintButtonComponent :props="printObj"
                                :buttonClass="'flex items-center justify-center gap-1.5 h-10 px-4 rounded-3xl text-white bg-success'" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import PrintButtonComponent from "../../components/buttons/PrintButtonComponent.vue";
import alertService from "../../../../services/alertService";
import _ from "lodash";
import composables from "../../../../composables/composables";

export default {
    name: "ProductVariationBarcodeComponent",
    components: { LoadingComponent, PrintButtonComponent },
    props: ["barcodeProps"],
    data() {
        return {
            loading: {
                isActive: false,
            },
            printObj: {
                id: "variationBarcodePrint",
                popTitle: this.$t('menu.variations')
            },
            productId: null,
            variationProps: {
                form: {
                    attribute: null
                },
                productId: null,
            },
            errors: {},
        };
    },
    methods: {
        reset: function () {
            composables.closeModal('variationBarcodeModal');
        },
        downloadBarcode: function (sku) {
            if (!isNaN(this.barcodeProps.variation_id)) {
                this.loading.isActive = true;
                this.$store.dispatch("productVariation/downloadBarcode", this.barcodeProps.variation_id).then((res) => {
                    this.loading.isActive = false;
                    composables.closeModal('variationBarcodeModal');
                    let fileType = "";
                    if (res.data.type) {
                        let type = res.data.type;
                        type = type.split("/");
                        fileType = type[1];
                    }

                    if (res.data.size > 0) {
                        const url = window.URL.createObjectURL(
                            new Blob([res.data])
                        );
                        const link = document.createElement("a");
                        link.href = url;
                        link.download =
                            "" + sku + "." + fileType;
                        link.click();
                        URL.revokeObjectURL(link.href);
                    } else {
                        alertService.info(this.$t("menu.variations") + " " + this.$t('message.barcode_not_found'));
                    }

                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        },
    }
};
</script>