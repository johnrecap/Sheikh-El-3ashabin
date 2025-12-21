<template>
    <LoadingComponent :props="loading" />
    <div class="w-full max-w-lg mx-auto rounded-2xl overflow-hidden bg-white shadow-card sm:mb-20">
        <form class="w-full p-6" @submit.prevent="signup">
            <div class="text-center mb-8">
                <h3 class="capitalize text-2xl mb-2 font-bold text-primary">{{ $t('label.sign_up') }} </h3>
            </div>
            <div class="mb-6">
                <label class="field-title required">{{ $t('label.name') }}</label>
                <input class="field-control" v-model="form.name" :class="errors.name ? 'invalid' : ''" id="formName"
                    type="text" />
                <small class="db-field-alert" v-if="errors.name">{{ errors.name[0] }}</small>
            </div>

            <div :class="!toggleValue ? 'mb-6' : ''">
                <div class="flex items-center justify-between">
                    <label :for="!toggleValue ? 'formEmail' : 'phone'" class="field-title required"> {{ inputLabel }}
                    </label>
                    <button @click="handleField()" type="button" class="field-title text-primary">
                        {{ inputButton }}
                    </button>
                </div>
                <div v-if="toggleValue" :class="errors.phone ? 'invalid' : ''"
                    class="flex items-center gap-1.5 px-4 h-12 rounded-lg border border-[#D9DBE9] hover:border-primary/30 focus-within:border-primary/30 transition-all duration-500">
                    <div class="w-fit flex-shrink-0 dropdown-group">
                        <button type="button" class="flex items-center gap-1">
                            {{ flag }}
                            <span class="whitespace-nowrap flex-shrink-0 text-sm">{{ form.country_code }}</span>
                            <input type="hidden" v-model="form.country_code">
                        </button>
                    </div>
                    <input v-model="form.phone" v-on:keypress="phoneNumber($event)" v-bind:class="errors.phone
                        ? 'invalid' : ''" type="text" id="phone" class="pl-2 field-title w-full h-full" />
                </div>

                <input v-if="!toggleValue" v-model="form.email" :class="errors.email ? 'invalid' : ''" id="formEmail"
                    type="text"
                    class="w-full h-12 px-4 rounded-lg text-base border border-[#D9DBE9] hover:border-primary/30 focus-within:border-primary/30 transition-all duration-500" />
                <small class="db-field-alert" v-if="errors.email_or_phone">{{ errors.email_or_phone }}</small>
                <span v-else>
                    <small class="db-field-alert" v-if="errors.phone && toggleValue">{{ errors.phone[0] }}</small>
                    <small class="db-field-alert" v-if="errors.email && !toggleValue">{{ errors.email[0] }}</small>
                </span>
            </div>


            <div class="mb-6">
                <label class="field-title required">{{ $t('label.password') }}</label>
                <input type="password" class="field-control" v-model="form.password"
                    :class="errors.password ? 'invalid' : ''" id="formPassword" />
                <small class="db-field-alert" v-if="errors.password">{{ errors.password[0] }}</small>
            </div>
            <button type="submit" class="field-button mb-6"> {{ $t('label.sign_up') }}</button>
            <div class="flex items-center justify-center gap-1.5">
                <span class="font-medium text-text">{{ $t('message.already_have_account') }}</span>
                <router-link :to="{ name: 'auth.login' }" class="capitalize font-bold text-primary">
                    {{ $t('label.sign_in') }}
                </router-link>
            </div>
        </form>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import appService from "../../../services/appService";
import ENV from "../../../config/env";
import askEnum from "../../../enums/modules/askEnum"
import alertService from "../../../services/alertService";
import router from "../../../router";
export default {
    name: "LoginComponent",
    components: { LoadingComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            form: {
                name: "",
                email: "",
                phone: "",
                country_code: "",
                password: ""
            },
            flag: "",
            errors: {},
            demo: ENV.DEMO,
            APP_URL: ENV.API_URL,
            toggleValue: false,
            inputLabel: this.$t('label.email'),
            inputButton: this.$t('label.use_phone_instead'),
            isDropdownVisible: false,
        }
    },
    computed: {
        countryCodes: function () {
            return this.$store.getters['frontendCountryCode/lists'];
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch('frontendCountryCode/lists');
        this.$store.dispatch('frontendSetting/lists').then(res => {
            this.$store.dispatch('frontendCountryCode/show', res.data.data.company_country_code).then(res => {
                this.form.country_code = res.data.data.calling_code;
                this.flag = res.data.data.flag_emoji;

                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
    methods: {
        toggleDropdown() {
            this.isDropdownVisible = !this.isDropdownVisible;
        },
        phoneNumber(e) {
            return appService.phoneNumber(e);
        },
        handleField: function () {
            this.toggleValue = !this.toggleValue

            if (this.toggleValue) {
                this.form.email = "";
                this.inputLabel = this.$t('label.phone');
                this.inputButton = this.$t('label.use_email_instead');
            }
            else {
                this.form.phone = "";
                this.inputLabel = this.$t('label.email');
                this.inputButton = this.$t('label.use_phone_instead');
            }
        },
        countryCodeChange: function (e) {
            this.flag = e.flag_emoji;
            this.form.country_code = e.calling_code;
            this.isDropdownVisible = false;
        },
        signup: function () {
            try {
                this.loading.isActive = true;
                this.$store.dispatch("frontendSignup/signupValidation", this.form).then((res) => {
                    if (this.setting.site_phone_verification === askEnum.YES && this.form.phone !== "" && (this.demo !== 'true' || this.demo !== 'TRUE' || this.demo !== 'True' || this.demo !== '1' || this.demo !== 1)) {
                        this.$store.dispatch("frontendSignup/otpPhone", this.form).then((res) => {
                            this.loading.isActive = false;
                            alertService.success(res.data.message, 'bottom-center');
                            this.$router.push({ name: "auth.signupVerify" });
                        }).catch((err) => {
                            this.loading.isActive = false;
                            alertService.error(err.response.data.message);
                        });
                    } else if (this.setting.site_email_verification === askEnum.YES && this.form.email !== "" && (this.demo !== 'true' || this.demo !== 'TRUE' || this.demo !== 'True' || this.demo !== '1' || this.demo !== 1)) {
                        this.$store.dispatch("frontendSignup/otpEmail", this.form).then((res) => {
                            this.loading.isActive = false;
                            alertService.success(res.data.message, 'bottom-center');
                            this.$router.push({ name: "auth.signupVerify" });
                        }).catch((err) => {
                            this.loading.isActive = false;
                            alertService.error(err.response.data.message);
                        });
                    } else {
                        this.$store.dispatch("frontendSignup/signup", this.form).then((res) => {
                            this.loading.isActive = false;
                            this.$store.dispatch("signupLoginVerify", this.form).then((res) => {
                                this.loading.isActive = false;
                                alertService.success(res.data.message, 'bottom-center');
                                this.$store.dispatch("frontendSignup/reset");
                                this.$router.push({
                                    name: "frontend.home",
                                });
                            }).catch((err) => {
                                this.loading.isActive = false;
                                this.errors = err.response.data.message;
                            });
                            this.$router.push({ name: "frontend.home" });
                            this.form = {
                                name: "",
                                email: "",
                                phone: "",
                                code: "",
                                password: ""
                            };
                            this.errors = {};
                        }).catch((err) => {
                            this.loading.isActive = false;
                            this.errors = err.response.data.errors;
                        })
                    }
                }).catch((err) => {
                    this.loading.isActive = false;
                    this.errors = err.response.data.errors;
                })
            } catch (err) {
                this.loading.isActive = false;
            }
        },
    }
}
</script>