<template>
    <LoadingComponent :props="loading" />
    <div class="row">
        <div v-for="(paymentGateway, index) in paymentGateways.slice(0, 2)" class="col-12 sm:col-6 xl:col-4 mb-5">
            <button @click="selectActive(index)"
                class="tab-button w-full flex items-center gap-2 px-4 h-10 rounded-lg bg-white hover:text-primary hover:bg-primary/10"
                :data-tab="'#' + paymentGateway.slug" :key="paymentGateway"
                :class="index === selectIndex ? 'tab-active' : ''">
                <span class="capitalize whitespace-nowrap text-[15px]">
                    {{ paymentGateway.name }}
                </span>
            </button>
        </div>

        <div v-if="paymentGateways.length > 2" class="col-12 sm:col-6 xl:col-4 mb-5">
            <div class="paper-group">
                <button id="morePaperBtn" @click="handlePaper"
                    class="paper-button w-full flex items-center gap-2 px-4 h-10 rounded-lg bg-white hover:text-primary hover:bg-primary/10">
                    <i class="lab-line-circle-more text-lg flex-shrink-0"></i>
                    <span
                        class="flex-auto ltr:text-left rtl:text-right text-sm capitalize whitespace-nowrap text-ellipsis overflow-hidden">
                        {{ $t('label.more_gateway') }}</span>
                    <i class="lab-fill-arrow-down text-sm"></i>
                </button>
                <div class="paper-content w-full absolute top-[42px] right-0 z-30 p-1 rounded-md shadow-paper bg-white">
                    <button @click="selectActive(index + 2)"
                        class="tab-button w-full flex items-center gap-2 px-2.5 h-8 rounded-lg hover:text-primary hover:bg-primary/5"
                        :data-tab="'#' + paymentGateway.slug"
                        v-for="(paymentGateway, index) in paymentGateways.slice(2, paymentGateways.length)"
                        :key="paymentGateway" :class="index + 2 === selectIndex ? 'tab-active' : ''">
                        <span
                            class="flex-auto ltr:text-left rtl:text-right text-sm capitalize whitespace-nowrap text-ellipsis overflow-hidden">
                            {{ paymentGateway.name }}
                        </span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <div :id="paymentGateway.slug" class="tab-content db-card" v-for="(paymentGateway, index) in paymentGateways"
        :key="paymentGateway" :class="index === selectIndex ? 'tab-active' : ''">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ paymentGateway.name }}</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save(index)" :id="'formElem' + index">
                <div class="form-row">
                    <input type="hidden" :value="paymentGateway.slug" name="payment_type">

                    <div class="form-col-12 sm:form-col-6" v-for="paymentGatewayOption in paymentGateway.options"
                        :key="paymentGatewayOption">
                        <label :for="paymentGatewayOption.option" class="db-field-title">
                            {{ $t("label." + paymentGatewayOption.option) }}
                        </label>
                        <input v-if="paymentGatewayOption.type === enums.inputTypeEnum.TEXT" type="text"
                            :value="paymentGatewayOption.value"
                            v-bind:class="errors[paymentGatewayOption.option] ? 'invalid' : ''"
                            :name="paymentGatewayOption.option" :id="paymentGatewayOption.option"
                            class="db-field-control" />

                        <select v-else class="db-field-control" :id="paymentGatewayOption.option"
                            :name="paymentGatewayOption.option"
                            v-bind:class="errors[paymentGatewayOption.option] ? 'invalid' : ''">
                            <option :value="index" :selected="index === paymentGatewayOption.value"
                                v-for="(activity, index) in paymentGatewayOption.activities">
                                {{ $t("label." + activity) }}
                            </option>
                        </select>

                        <small class="db-field-alert" v-if="errors[paymentGatewayOption.option]">
                            {{ errors[paymentGatewayOption.option][0] }}
                        </small>
                    </div>
                    <div class="form-col-12 mt-5">
                        <button type="submit" class="db-btn text-white bg-primary">
                            <i class="lab lab-fill-save text-base"></i>
                            <span>{{ $t("button.save") }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import LoadingComponent from "../../components/LoadingComponent.vue";
import alertService from "../../../../services/alertService";
import inputTypeEnum from "../../../../enums/modules/inputTypeEnum";
import composables from "../../../../composables/composables";

export default {
    name: "PaymentGatewayComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            search: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                excepts: "1|2"
            },
            selectIndex: 0,
            enums: {
                inputTypeEnum: inputTypeEnum
            },
            errors: {},
            handlePaper: composables.handlePaper,

        };
    },
    computed: {
        paymentGateways: function () {
            return this.$store.getters["paymentGateway/lists"];
        },
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("paymentGateway/lists", this.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
            alertService.error(err);
        }
    },
    methods: {
        save: function (index) {
            try {
                let form = document.getElementById("formElem" + index);
                let formDataObj = {};
                [...form.elements].filter((el) => el.tagName !== 'BUTTON').forEach((item) => {
                    formDataObj[item.name] = item.value;
                });

                this.loading.isActive = true;
                this.$store.dispatch("paymentGateway/save", { form: formDataObj, search: this.search }).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.payment_gateway"));
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                this.loading.isActive = false;
                alertService.error(err);
            }
        },
        selectActive: function (index) {
            this.selectIndex = index;
            let element = document.getElementById("morePaperBtn");
            if (element.parentElement.className.includes('active')) {
                element.parentElement.classList.remove('active');
            }
        }
    }
};
</script>