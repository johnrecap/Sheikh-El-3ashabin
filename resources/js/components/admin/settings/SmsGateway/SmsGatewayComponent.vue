<template>
    <LoadingComponent :props="loading" />
    <div class="row">
        <div v-for="(smsGateway, index) in smsGateways.slice(0, 2)" class="col-12 sm:col-6 xl:col-4 mb-5">
            <button @click="selectActive(index)"
                class="tab-button w-full flex items-center gap-2 px-4 h-10 rounded-lg bg-white hover:text-primary hover:bg-primary/10"
                :data-tab="'#' + smsGateway.slug" :key="smsGateway" :class="index === selectIndex ? 'tab-active' : ''">
                <span class="capitalize whitespace-nowrap text-[15px]">
                    {{ smsGateway.name }}
                </span>
            </button>
        </div>
        <div v-if="smsGateways.length > 2" class="col-12 sm:col-6 xl:col-4 mb-5">
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
                        :data-tab="'#' + smsGateway.slug"
                        v-for="(smsGateway, index) in smsGateways.slice(2, smsGateways.length)" :key="smsGateway"
                        :class="index + 2 === selectIndex ? 'tab-active' : ''">
                        <span
                            class="flex-auto ltr:text-left rtl:text-right text-sm capitalize whitespace-nowrap text-ellipsis overflow-hidden">
                            {{ smsGateway.name }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div :id="smsGateway.slug" class="tab-content db-card" v-for="(smsGateway, index) in smsGateways" :key="smsGateway"
        :class="index === selectIndex ? 'tab-active' : ''">
        <div class="db-card-header">
            <h3 class="db-card-title">{{ smsGateway.name }}</h3>
        </div>
        <div class="db-card-body">
            <form @submit.prevent="save(index)" :id="'formElem' + index">
                <div class="form-row">
                    <input type="hidden" :value="smsGateway.slug" name="sms_type">
                    <div class="form-col-12 sm:form-col-6" v-for="smsGatewayOption in smsGateway.options"
                        :key="smsGatewayOption">
                        <label :for="smsGatewayOption.option" class="db-field-title">
                            {{ $t("label." + smsGatewayOption.option) }}
                        </label>
                        <input v-if="smsGatewayOption.type === enums.inputTypeEnum.TEXT" type="text"
                            :value="smsGatewayOption.value"
                            v-bind:class="errors[smsGatewayOption.option] ? 'invalid' : ''"
                            :name="smsGatewayOption.option" :id="smsGatewayOption.option" class="db-field-control" />

                        <select v-else class="db-field-control" :id="smsGatewayOption.option"
                            :name="smsGatewayOption.option"
                            v-bind:class="errors[smsGatewayOption.option] ? 'invalid' : ''">
                            <option :value="index" :selected="index === smsGatewayOption.value"
                                v-for="(activity, index) in smsGatewayOption.activities">
                                {{ $t("label." + activity) }}
                            </option>
                        </select>

                        <small class="db-field-alert" v-if="errors[smsGatewayOption.option]">{{
        errors[smsGatewayOption.option][0]
    }}</small>
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
    name: "SmsGatewayComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            search: {
                paginate: 0,
                order_column: "id",
                order_type: "asc"
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
        smsGateways: function () {
            return this.$store.getters["smsGateway/lists"];
        },
    },
    mounted() {
        try {
            this.loading.isActive = true;
            this.$store.dispatch("smsGateway/lists", this.search).then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        } catch (err) {
            this.loading.isActive = false;
        }
    },
    methods: {
        save: function (index) {
            try {
                const form = document.getElementById("formElem" + index);
                const formDataObj = {};
                [...form.elements].filter((el) => el.tagName !== 'BUTTON').forEach((item) => {
                    formDataObj[item.name] = item.value;
                });
                this.loading.isActive = true;
                this.$store.dispatch("smsGateway/save", { form: formDataObj, search: this.search }).then((res) => {
                    this.loading.isActive = false;
                    alertService.successFlip(res.config.method === "put" ?? 0, this.$t("menu.sms_gateway"));
                    this.errors = {};
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                });
            } catch (err) {
                alert(err);
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
        },
    },
};
</script>