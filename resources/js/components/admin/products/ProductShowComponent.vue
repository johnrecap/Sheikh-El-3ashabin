<template>
    <LoadingComponent :props="loading" />

    <div class="col-12">
        <div id="product" class="db-tab-div active">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 mb-5">

                <button @click.prevent="handleTab($event, 'information')"
                    class="tab-button tab-active w-full flex items-center gap-3 h-10 px-4 rounded-lg bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-fill-info lab-font-size-16"></i>
                    {{ $t("label.information") }}
                </button>
                <button type="button" @click.prevent="handleTab($event, 'image')"
                    class="tab-button w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-fill-image lab-font-size-16"></i>
                    {{ $t("label.images") }}
                </button>
                <button type="button" @click.prevent="handleTab($event, 'variations')"
                    class="tab-button w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                    <i class="lab lab-fill-variation lab-font-size-16"></i>
                    {{ $t("label.variation") }}
                </button>


                <div class="paper-group relative w-full">
                    <button id="moreBtn" @click="handlePaper"
                        class="paper-button w-full flex items-center gap-3 h-10 px-4 rounded-lg bg-white hover:text-primary hover:bg-primary/5">
                        <i class="lab lab-fill-circle-chevron-down text-sm transition-all duration-500"></i>
                        <span class="capitalize tracking-wide">{{ $t('label.more') }}</span>
                    </button>
                    <div
                        class="paper-content absolute top-11 right-0 w-full z-30 p-2 rounded-md origin-top bg-white shadow-lg transition-all duration-500">
                        <button type="button" @click.prevent="handleTab($event, 'offer')"
                            class="tab-button w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                            <i class="lab lab-fill-offers lab-font-size-16"></i>
                            {{ $t("label.offer") }}
                        </button>
                        <button type="button" @click.prevent="handleTab($event, 'seo')"
                            class="tab-button w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                            <i class="lab lab-fill-seo lab-font-size-16"></i>
                            {{ $t("label.seo") }}
                        </button>
                        <button type="button" @click.prevent="handleTab($event, 'barcode')"
                            class="tab-button w-full flex items-center gap-3 h-10 px-4 rounded-lg transition bg-white hover:text-primary hover:bg-primary/5">
                            <i class="lab lab-fill-scan-barcode lab-font-size-16"></i>
                            {{ $t("label.barcode") }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="db-card tab-content tab-active" id="information">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.information') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row py-2">
                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.name") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.name }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.sku") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.sku }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.category") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.category }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.barcode") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.barcode }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.brand") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.brand }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.tax") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.tax }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.buying_price") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.flat_buying_price }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.selling_price") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.flat_selling_price }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{
                                    $t("label.maximum_purchase_quantity")
                                }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.maximum_purchase_quantity
                                    }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{
                                    $t("label.low_stock_quantity_warning")
                                }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.low_stock_quantity_warning
                                    }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.weight") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.weight }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.unit") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{ product.unit }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.purchasable") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                    enums.askEnumArray[product.can_purchasable]
                                }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.show_stock_out") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                    enums.activityEnumArray[product.show_stock_out]
                                }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.file_attachment")
                                    }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                    enums.activityEnumArray[product.file_attachment]
                                }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.refundable") }}</span>
                                <span class="db-list-item-text w-full sm:w-1/2">{{
                                    enums.askEnumArray[product.refundable]
                                }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.status") }}</span>
                                <span class="db-list-item-text">{{ enums.statusEnumArray[product.status] }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.tags") }}</span>
                                <span class="db-list-item-text">{{ product.tags }}</span>
                            </div>
                        </div>

                        <div class="col-12 lg:col-6 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-title w-full sm:w-1/2">{{ $t("label.sell_by_fraction")
                                    }}</span>
                                <span class="db-list-item-text">{{ enums.askEnumArray[product.sell_by_fraction]
                                    }}</span>
                            </div>
                        </div>

                        <div class="col-12 !py-1.5">
                            <div class="db-list-item p-0">
                                <span class="db-list-item-text mt-2 w-full">
                                    <span class="mt-2 db-list-item-title">{{ $t("label.description") }}</span><br />
                                    <span class="mt-2 text-description" v-html="product.description"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="db-card tab-content" id="image">
                <div class="row px-3 py-0">
                    <div class="col-12 md:col-4">
                        <img class="db-image" alt="slider" :src="previewImage" />
                    </div>
                    <div class="col-12 md:col-8">
                        <form @submit.prevent="saveImage">
                            <p class="mt-2">{{ $t('label.size') }}: (262px,182px)</p>
                            <div class="flex gap-3 md:gap-4 py-4">
                                <label for="photo"
                                    class="db-btn relative cursor-pointer h-[38px] shadow-[0px_6px_10px_rgba(26,_183,_89,_0.24)] bg-primary text-white">
                                    <i class="lab lab-line-upload-image"></i>
                                    <span class="hidden sm:inline-block">{{
                                        $t("button.upload_new_image")
                                    }}</span>
                                    <input v-if="uploadButton" @change="changePreviewImage" ref="imageProperty"
                                        accept="image/png, image/jpeg, image/jpg" type="file" id="photo"
                                        class="absolute top-0 left-0 w-full h-full -z-10 opacity-0" />
                                </label>
                                <button v-if="saveButton" type="submit"
                                    class="db-btn h-[38px] shadow-[0px_6px_10px_rgba(26,_183,_89,_0.24)] text-white bg-[#1AB759]">
                                    <i class="lab lab-fill-save"></i>
                                    <span class="hidden sm:inline-block">{{ $t("button.save") }}</span>
                                </button>
                                <button v-if="resetButton" @click="resetPreviewImage" type="button"
                                    class="db-btn-outline h-[38px] shadow-[0px_6px_10px_rgba(251,_78,_78,_0.24)] !text-[#FB4E4E] !bg-white !border-[#FB4E4E]">
                                    <i class="lab lab-line-reset"></i>
                                    <span class="hidden sm:inline-block">{{ $t("button.reset") }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <div class="db-card tab-content" id="variations">
                <ProductVariationListComponent />
            </div>

            <div class="db-card tab-content" id="offer">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.offer') }}</h3>
                </div>
                <div class="db-card-body">
                    <form @submit.prevent="offerSsave" class="d-block w-full">
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label for="offer_start_date" class="db-field-title required">{{
                                    $t("label.offer_start_date")
                                }}</label>
                                <Datepicker hideInputIcon autoApply v-model="form.offer_start_date"
                                    :enableTimePicker="true" :is24="false" :monthChangeOnScroll="false" utc="false"
                                    :input-class-name="offerError.offer_start_date ? 'invalid' : ''">
                                    <template #am-pm-button="{ toggle, value }">
                                        <button @click="toggle">{{ value }}</button>
                                    </template>
                                </Datepicker>
                                <small class="db-field-alert" v-if="offerError.offer_start_date">{{
                                    offerError.offer_start_date[0]
                                }}</small>
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label for="offer_end_date" class="db-field-title required">{{
                                    $t("label.offer_end_date")
                                }}</label>
                                <Datepicker hideInputIcon autoApply v-model="form.offer_end_date"
                                    :enableTimePicker="true" :is24="false" :monthChangeOnScroll="false" utc="false"
                                    :disabled="form.is_daily_deals"
                                    :input-class-name="offerError.offer_end_date ? 'invalid' : ''">
                                    <template #am-pm-button="{ toggle, value }">
                                        <button @click="toggle">{{ value }}</button>
                                    </template>
                                </Datepicker>
                                <small class="db-field-alert" v-if="offerError.offer_end_date">{{
                                    offerError.offer_end_date[0]
                                }}</small>
                            </div>

                            <div class="form-col-12 sm:form-col-6">
                                <label for="discount" class="db-field-title required">
                                    {{ $t("label.discount_percentage") }}
                                </label>
                                <input v-model="form.discount" v-on:keypress="floatNumber($event)"
                                    v-bind:class="offerError.discount ? 'invalid' : ''" type="text" id="discount"
                                    class="db-field-control" />
                                <small class="db-field-alert" v-if="offerError.discount">{{ offerError.discount[0]
                                    }}</small>
                            </div>

                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required" for="yes">{{ $t("label.add_to_flash_sale")
                                    }}</label>
                                <div class="db-field-radio-group">
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input type="radio" v-model="form.add_to_flash_sale" id="yes"
                                                :value="enums.askEnum.YES" class="custom-radio-field">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="yes" class="db-field-label">{{ $t('label.yes') }}</label>
                                    </div>
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input type="radio" class="custom-radio-field"
                                                v-model="form.add_to_flash_sale" id="no" :value="enums.askEnum.NO">
                                            <span class="custom-radio-span"></span>
                                        </div>
                                        <label for="no" class="db-field-label">{{ $t('label.no') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-col-12 sm:form-col-6">
                                <div class="db-field-radio-group">
                                    <div class="db-field-radio">
                                        <div class="custom-radio">
                                            <input @change="dailyDealsChange" type="checkbox"
                                                class="custom-checkbox-field" id="daily_deals"
                                                :checked="form.is_daily_deals">
                                            <i class="lab lab-fill-check custom-checkbox-icon lab-font-size-10"></i>
                                        </div>
                                        <label for="daily_deals" class="db-field-label">{{ $t('label.daily_deals')
                                            }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-col-12">
                                <button type="submit" class="db-btn text-white bg-primary">
                                    <i class="lab lab-fill-save"></i>
                                    <span>{{ $t("button.save") }}</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="db-card tab-content" id="seo">
                <ProductSeoComponent />
            </div>

            <div class="db-card tab-content" id="barcode">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ $t('label.barcode') }}</h3>
                </div>
                <div class="db-card-body">
                    <div class="row px-3 py-0">
                        <div class="col-12 md:col-2 max-w-[240px] w-full bg-gray-200">
                            <div class="w-full p-1 bg-white max-w-[216px] w-full" id="productBarcodePrint">
                                <h2 class="text-[10px] leading-tight font-bold mb-1">{{ product.name }}</h2>
                                <h4 class="text-[9px] leading-tight font-medium mb-2">{{ $t("label.category") }}: {{
                                    product.category }}</h4>
                                <div class="flex items-start gap-3">
                                    <div class="text-center flex flex-col ">
                                        <img :src="barcodeImage" alt="barcode" class="w-32 h-10 flex-shrink-0">
                                        <span class="text-[9px] leading-tight font-medium">
                                            {{ product.sku }}
                                        </span>
                                    </div>

                                    <div class="flex-auto">
                                        <h5 class="text-[9px] leading-tight font-medium mb-2">{{ $t("label.price") }}
                                        </h5>
                                        <h6 class="text-base leading-tight font-bold">{{ product.old_currency_price
                                            }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 md:col-8 hidden-print">
                            <div class="flex items-center gap-6">
                                <button v-if="barcodeImage" @click="downloadBarcode(product.sku)" type="button"
                                    class="flex items-center justify-center gap-1.5 h-10 px-6 rounded-3xl text-white bg-primary">
                                    <i class="lab lab-fill-download"></i>
                                    <span class="capitalize text-sm font-medium">{{ $t('label.download') }}</span>
                                </button>
                                <PrintButtonComponent :props="printObj"
                                    :buttonClass="'flex items-center justify-center gap-1.5 h-10 px-6 rounded-3xl text-white bg-success'" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import statusEnum from "../../../enums/modules/statusEnum";
import askEnum from "../../../enums/modules/askEnum";
import appService from "../../../services/appService";
import alertService from "../../../services/alertService";
import ProductVariationListComponent from "./variation/ProductVariationListComponent.vue";
import ProductSeoComponent from "./seo/ProductSeoComponent.vue";
import { quillEditor } from 'vue3-quill'
import Datepicker from "@vuepic/vue-datepicker";
import activityEnum from "../../../enums/modules/activityEnum";
import composables from "../../../composables/composables";
import PrintButtonComponent from "../components/buttons/PrintButtonComponent.vue";

export default {
    name: "ProductShowComponent",
    components: {
        LoadingComponent,
        ProductVariationListComponent,
        quillEditor,
        ProductSeoComponent,
        Datepicker,
        PrintButtonComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            printObj: {
                id: "productBarcodePrint",
                popTitle: this.$t('menu.products')
            },
            tabMore: false,
            enums: {
                statusEnum: statusEnum,
                askEnum: askEnum,
                statusEnumArray: {
                    [statusEnum.ACTIVE]: this.$t("label.active"),
                    [statusEnum.INACTIVE]: this.$t("label.inactive"),
                },
                askEnumArray: {
                    [askEnum.YES]: this.$t("label.yes"),
                    [askEnum.NO]: this.$t("label.no"),
                },
                activityEnumArray: {
                    [activityEnum.ENABLE]: this.$t("label.enable"),
                    [activityEnum.DISABLE]: this.$t("label.disable"),
                }
            },
            deleteIndex: 0,
            imageCount: 0,
            defaultImage: null,
            previewImage: null,
            barcodeImage: null,
            livePreview: null,
            uploadButton: true,
            resetButton: false,
            saveButton: false,
            form: {
                add_to_flash_sale: "",
                discount: "1.5",
                offer_start_date: "",
                offer_end_date: "",
                is_daily_deals: false
            },
            offerError: {},
            handlePaper: composables.handlePaper,
        };
    },
    computed: {
        product: function () {
            return this.$store.getters["product/show"];
        },

    },
    mounted() {
        this.loading.isActive = true;
        this.show();
    },
    methods: {
        handleTab: function (event, targetID) {
            composables.handleTab(event, targetID);
            let element = document.getElementById("moreBtn");
            if (element.parentElement.className.includes('active')) {
                element.parentElement.classList.remove('active');
            }
        },
        floatNumber(e) {
            return appService.floatNumber(e);
        },
        switchImage: function (link, index) {
            this.livePreview = link;
            this.deleteIndex = index;
        },
        changePreviewImage: function (e) {
            if (e.target.files[0]) {
                this.previewImage = URL.createObjectURL(e.target.files[0]);
                this.saveButton = true;
                this.resetButton = true;
            }
        },
        resetPreviewImage: function () {
            this.$refs.imageProperty.value = null;
            this.previewImage = this.defaultImage;
            this.saveButton = false;
            this.resetButton = false;
        },
        show: function () {
            this.$store.dispatch("product/show", this.$route.params.id).then((res) => {
                this.defaultImage = res.data.data.preview;
                this.previewImage = res.data.data.preview;
                this.barcodeImage = res.data.data.barcode_image;
                this.livePreview = res.data.data.image;
                this.imageCount = res.data.data.images.length;
                this.form.add_to_flash_sale = res.data.data.add_to_flash_sale;
                this.form.discount = res.data.data.discount_percentage;
                this.form.offer_start_date = res.data.data.offer_start_date;
                this.form.offer_end_date = res.data.data.offer_end_date;
                this.form.is_daily_deals = this.isDatesAreSame(res.data.data.offer_start_date, res.data.data.offer_end_date);
                this.loading.isActive = false;
            }).catch((error) => {
                this.loading.isActive = false;
            });
        },
        saveImage: function () {
            if (this.$refs.imageProperty.files[0]) {
                try {
                    this.loading.isActive = true;
                    const formData = new FormData();
                    formData.append("image", this.$refs.imageProperty.files[0]);
                    this.$store.dispatch("product/uploadImage", {
                        id: this.$route.params.id,
                        form: formData
                    }).then((res) => {
                        alertService.success(this.$t("message.image_update"));
                        this.defaultImage = res.data.data.preview;
                        this.previewImage = res.data.data.preview;
                        this.livePreview = res.data.data.image;
                        this.imageCount = res.data.data.images.length;

                        if (this.$refs.imageProperty) {
                            this.$refs.imageProperty.value = null;
                        }

                        this.saveButton = false;
                        this.resetButton = false;
                        this.loading.isActive = false;

                        this.loading.isActive = false;
                    }).catch((err) => {
                        this.loading.isActive = false;
                        alertService.error(err.response.data.errors.image[0]);
                    });
                } catch (err) {
                    this.loading.isActive = false;
                    alertService.error(err.response.data.message);
                }
            }
        },
        deleteImage: function () {
            appService.destroyConfirmation().then((res) => {
                try {
                    this.loading.isActive = true;
                    this.$store.dispatch("product/deleteImage", {
                        id: this.$route.params.id,
                        index: this.deleteIndex,
                    }).then((res) => {
                        this.show();
                        this.loading.isActive = false;
                        alertService.success(this.$t("message.image_delete"));
                        this.deleteIndex = 0;
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
        offerSsave: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("product/productOffer", {
                    id: this.$route.params.id,
                    form: this.form,
                }).then((res) => {
                    alertService.success(this.$t("message.product_offer"));
                    this.loading.isActive = false;
                    this.offerError = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.offerError = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err.response.data.message);
            }
        },
        dailyDealsChange: function (e) {
            if (e.target.checked && this.form.offer_start_date != null) {
                const end_date = new Date(this.form.offer_start_date);
                end_date.setHours(23, 59, 0, 0);
                this.form.offer_end_date = end_date;
            }

            this.form.is_daily_deals = e.target.checked;
        },
        isDatesAreSame: function (date1, date2) {

            if (date1 != null && date2 != null) {
                date1 = new Date(date1);
                date2 = new Date(date2);

                return date1.getFullYear() === date2.getFullYear() &&
                    date1.getMonth() === date2.getMonth() &&
                    date1.getDate() === date2.getDate();
            } else {
                return false;
            }
        },
        downloadBarcode: function (sku) {
            if (!isNaN(this.$route.params.id)) {
                this.loading.isActive = true;
                this.$store.dispatch("product/downloadBarcode", this.$route.params.id).then((res) => {
                    this.loading.isActive = false;

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
                        alertService.info(this.$t("menu.products") + " " + this.$t('message.barcode_not_found'));
                    }

                }).catch((err) => {
                    this.loading.isActive = false;
                });
            }
        },
    },
    watch: {
        'form.offer_start_date'(newVal) {
            if (this.form.is_daily_deals) {
                if (this.form.offer_start_date != null) {
                    const end_date = new Date(this.form.offer_start_date);
                    end_date.setHours(23, 59, 0, 0);
                    this.form.offer_end_date = end_date;
                }
            }
        }
    }
};
</script>

<style scoped>
@media print {
    .hidden-print {
        display: none !important;
    }
}
</style>