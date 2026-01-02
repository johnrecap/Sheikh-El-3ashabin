<template>
    <header :class="isSticky == true
        ? 'fixed top-0 left-0 z-30 w-full shadow-xs bg-[#3D2B1F]'
        : 'sm:mb-6 sm:shadow-xs bg-[#3D2B1F]'
        ">

        <div class="container">
            <div class="flex items-center justify-between gap-5 h-16 lg:h-[74px]">

                <div class="flex items-center flex-shrink-0 gap-5">
                    <button @click.prevent="showTarget('mobile-sidebar-canvas', 'canvas-active')" type="button"
                        class="leading-none block lg:hidden">
                        <i class="lab-line-humburger text-xl"></i>
                    </button>
                    <RouterLink to="/" class="flex-shrink-0">
                        <img class="h-10 sm:h-12 w-auto max-w-[160px] object-contain" :src="setting.theme_logo" alt="logo">
                    </RouterLink>
                </div>
                <button type="button" @click.prevent="showTarget('search', 'search-active')"
                    class="leading-none block lg:hidden">
                    <i class="lab-line-search text-xl"></i>
                </button>
                <form @submit.prevent="search()"
                    class="w-full max-w-md h-10 group rounded-3xl hidden lg:flex items-center gap-2 px-4 border border-[#5C4A32] bg-[#2A1F14] transition-all duration-300 focus-within:border-primary focus-within:bg-[#3D2B1F]">
                    <button class="lab-line-search text-lg flex-shrink-0"></button>
                    <input type="search" v-model="searchProduct" :placeholder="$t('label.search') + '...'"
                        class="w-full h-full" />
                    <button type="button" @click.prevent="searchReset"
                        class="transition invisible group-focus-within:visible">
                        <i class="lab-fill-close-circle sm:text-xl text-primary"></i>
                    </button>
                </form>
                <div class="hidden lg:flex items-center gap-8">

                    <div class="paper-group" v-if="setting.site_language_switch === enums.activityEnum.ENABLE">
                        <button id="languagePaper" @click.prevent="handlePaper"
                            class="paper-button flex items-center gap-2 down-arrow">
                            <img :src="language.image" alt="flags" class="w-6 h-6 rounded-full">
                            <span class="font-semibold capitalize text-white">{{ language.name }}</span>
                        </button>
                        <ul
                            class="paper-content w-40 absolute top-12 ltr:right-0 rtl:left-0 shadow-paper rounded-lg z-10 p-2 bg-white transition-all duration-300 origin-top scale-y-0 group-hover:scale-y-100">
                            <li v-for="(LoopLanguage, index) in languages" :key="index"
                                @click.prevent="changeLanguage(LoopLanguage.id, LoopLanguage.code, LoopLanguage.display_mode)"
                                class="flex items-center gap-3 px-2 py-1.5 rounded-lg relative w-full cursor-pointer transition-all duration-300 hover:bg-slate-100">
                                <img :src="LoopLanguage.image" alt="flags" class="w-4 flex-shrink-0" />
                                <span class="text-sm font-medium capitalize flex-auto">{{ LoopLanguage.name }}</span>
                                <i v-if="language.id === LoopLanguage.id"
                                    class="lab-line-circle-check text-success"></i>
                            </li>
                        </ul>
                    </div>

                    <router-link class="flex items-center justify-center gap-2" :to="{ name: 'frontend.wishlist' }">
                        <i
                            class="lab-line-heart w-6 h-6 text-sm leading-6 text-center rounded-full bg-primary text-white">
                        </i>

                        <span class="font-semibold capitalize text-white"> {{ $t("label.favorite") }}</span>
                    </router-link>

                    <div class="paper-group">
                        <router-link v-if="!logged" :to="{ name: 'auth.login' }" class=" flex items-center gap-2">
                            <i
                                class="lab-line-user w-6 h-6 text-sm leading-6 text-center rounded-full bg-primary text-white">
                            </i>
                            <span class="font-semibold capitalize text-white"> {{ $t("label.account") }}</span>
                        </router-link>
                        <button id="profilePaperBtn" v-else @click.prevent="handlePaper"
                            class="paper-button flex items-center gap-2">
                            <i
                                class="lab-line-user  w-6 h-6 text-sm  leading-6 text-center rounded-full bg-primary text-white">
                            </i>
                            <span class="font-semibold capitalize text-white"> {{ $t("label.account") }}</span>
                            <i class="lab-line-chevron-down font-semibold"></i>
                        </button>
                        <div v-if="logged"
                            class="paper-content w-60 absolute top-12 ltr:right-0 rtl:left-0 z-10 rounded-2xl overflow-hidden shadow-paper origin-top scale-y-0 bg-white transition-all duration-300">
                            <div class="flex items-center gap-3 p-4 border-b border-[#EFF0F6]">
                                <img :src="profile.image" alt="avatar"
                                    class=" w-11 h-11 rounded-full object-cover flex-shrink-0">
                                <dl class="w-full">
                                    <dt class="font-semibold capitalize whitespace-nowrap mb-0.5">{{
                                        textShortener(profile.name, 20) }}</dt>
                                    <dd v-if="profile.phone" class="text-sm font-medium whitespace-nowrap text-text">{{
                                        profile.country_code }}{{
                                            profile.phone }}</dd>
                                </dl>
                            </div>
                            <nav class="flex flex-col py-2">
                                <router-link @click="hideProfilePaper"
                                    v-if="profile.role_id !== enums.roleEnum.CUSTOMER && Object.keys(authDefaultPermission).length > 0"
                                    class="flex items-center gap-3 px-4 py-2 transition-all duration-500 group hover:text-primary hover:bg-primary/10 transition-all"
                                    :to="{ path: '/admin/' + defaultMenu?.url }">
                                    <i class="text-sm text-[#A0A3BD] group-hover:text-primary"
                                        :class="defaultMenu?.icon"></i>
                                    <span class="text-sm font-medium capitalize whitespace-nowrap">
                                        {{ $t('menu.' + defaultMenu?.language) }}
                                    </span>
                                </router-link>

                                <router-link @click="hideProfilePaper"
                                    class="flex items-center gap-3 px-4 py-2 transition-all duration-500 group hover:text-primary hover:bg-primary/10 transition-all"
                                    :class="checkIsPathAndRoutePathSame('/account/order-history') ? '!text-primary profile-link' : ''"
                                    :to="{ name: 'frontend.account.orderHistory' }">
                                    <i class="text-sm text-[#A0A3BD] lab lab-fill-bag group-hover:text-primary"></i>
                                    <span class="text-sm font-medium capitalize whitespace-nowrap">
                                        {{ $t('menu.order_history') }}
                                    </span>
                                </router-link>

                                <router-link @click="hideProfilePaper"
                                    class="flex items-center gap-3 px-4 py-2 transition-all duration-500 group hover:text-primary hover:bg-primary/10 transition-all"
                                    :class="checkIsPathAndRoutePathSame('/account/account-info') ? '!text-primary profile-link' : ''"
                                    :to="{ name: 'frontend.account.accountInfo' }">
                                    <i class="text-sm text-[#A0A3BD] lab lab-fill-user group-hover:text-primary"></i>
                                    <span class="text-sm font-medium capitalize whitespace-nowrap">
                                        {{ $t('menu.account_info') }}
                                    </span>
                                </router-link>

                                <router-link @click="hideProfilePaper"
                                    class="flex items-center gap-3 px-4 py-2 transition-all duration-500 group hover:text-primary hover:bg-primary/10 transition-all"
                                    :class="checkIsPathAndRoutePathSame('/account/change-password') ? '!text-primary profile-link' : ''"
                                    :to="{ name: 'frontend.account.changePassword' }">
                                    <i class="text-sm text-[#A0A3BD] lab lab-fill-key group-hover:text-primary"></i>
                                    <span class="text-sm font-medium capitalize whitespace-nowrap">
                                        {{ $t('menu.change_password') }}
                                    </span>
                                </router-link>

                                <router-link @click="hideProfilePaper"
                                    class="flex items-center gap-3 px-4 py-2 transition-all duration-500 group hover:text-primary hover:bg-primary/10 transition-all"
                                    :class="checkIsPathAndRoutePathSame('/account/address') ? '!text-primary profile-link' : ''"
                                    :to="{ name: 'frontend.account.address' }">
                                    <i
                                        class="text-sm text-[#A0A3BD] lab lab-fill-location group-hover:text-primary"></i>
                                    <span class="text-sm font-medium capitalize whitespace-nowrap">
                                        {{ $t('menu.address') }}
                                    </span>
                                </router-link>

                                <button @click.prevent="logout()"
                                    class="flex items-center gap-3 px-4 py-2 transition-all duration-500 group hover:text-primary hover:bg-primary/10 transition-all">
                                    <i class="text-sm text-[#A0A3BD] lab lab-fill-logout group-hover:text-primary"></i>
                                    <span class="text-sm font-medium capitalize whitespace-nowrap">
                                        {{ $t('button.logout') }}
                                    </span>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div :class="isScrollingUp ? 'hidden lg:block' : 'hidden'" class="border-t border-[#5C4A32]">
            <div class="container">
                <div class="flex items-center justify-between gap-5 h-[74px]">
                    <div class="flex items-center justify-between gap-12">


                        <div class="paper-group">
                            <button id="navBarCategoryBtn" @click="handlePaper"
                                class="paper-button flex items-center gap-2 px-4 h-[42px] rounded-lg text-white bg-heading">
                                <i class="lab-line-category text-xl"></i>
                                <span class="capitalize font-semibold"> {{ $t("label.browse_category") }}</span>
                                <i class="lab-line-chevron-down font-semibold"></i>
                            </button>
                            <ul class="paper-content category-list">
                                <li v-for="category in categories" class="category-item">
                                    <router-link @click="hideCategoryPaper"
                                        :to="{ name: 'frontend.product', query: { category: category.slug } }"
                                        class="category-menu">
                                        <span class="text-sm font-medium capitalize">{{ textShortener(category.name, 30)}}</span>
                                        <i v-if="category.children.length > 0" class="lab-line-chevron-right ltr:rotate-0 rtl:-rotate-180"></i>
                                    </router-link>
                                    <MenuChildrenComponent v-if="category.children.length > 0"
                                        :categories="category.children" :key="category"
                                        v-on:hideCategoryPaper="hideCategoryPaper" />
                                </li>
                            </ul>
                        </div>


                        <nav class="flex items-center gap-8">
                            <router-link :to="{ name: 'frontend.home' }"
                                :class="checkIsPathAndRoutePathSame('/home') ? 'text-primary' : ''"
                                class="text-sm font-semibold capitalize text-white transition-all duration-300 hover:text-primary">
                                {{ $t("label.home") }}</router-link>

                            <router-link :to="{ name: 'frontend.offers' }"
                                :class="checkIsPathAndRoutePathSame('/offers') ? 'text-primary' : ''"
                                class="text-sm font-semibold capitalize text-white transition-all duration-300 hover:text-primary">
                                {{ $t("label.offers") }}</router-link>

                            <router-link :to="{ name: 'frontend.daily.deals' }"
                                :class="checkIsPathAndRoutePathSame('/daily-deals') ? 'text-primary' : ''"
                                class="text-sm font-semibold capitalize text-white transition-all duration-300 hover:text-primary">
                                {{ $t("label.daily_deals") }}</router-link>
                            <router-link v-if="flashSaleProducts.length > 0"
                                :class="checkIsPathAndRoutePathSame('/flash-sale') ? 'text-primary' : ''"
                                :to="{ name: 'frontend.flashSale.products' }"
                                class="text-sm font-semibold capitalize text-white transition-all duration-300 hover:text-primary">
                                {{ $t("label.flash_sale") }}</router-link>
                        </nav>
                    </div>
                    <div class="flex items-center justify-between gap-12">
                        <div class="flex items-center gap-2 text-primary">
                            <i class="lab-line-call-center font-medium"></i>
                            <a :href="'callto:' + setting.company_phone" class="text-sm font-semibold">{{
                                setting.company_phone }}</a>
                        </div>
                        <button @click="openCanvas('cart-canvas')"
                            class="flex items-center gap-2 px-4 h-[42px] rounded-lg shadow-cart text-white bg-primary">
                            <i class="lab-line-bag text-xl"></i>
                            <span class="capitalize font-semibold"> {{ $t("label.my_cart") }} ({{ carts.length
                            }})</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <form @submit.prevent="search" id="search"
        class="w-full  lg:w-auto fixed inset-0 z-30 py-5 px-4 bg-white transition-all duration-500 origin-top scale-y-0">
        <div class="flex items-center justify-between mb-4">
            <router-link :to="{ name: 'frontend.home' }"
                class="router-link-active router-link-exact-active flex-shrink-0">
                <img class="h-10 sm:h-12 w-auto max-w-[180px] object-contain" :src="setting.theme_logo" alt="logo">
            </router-link>
            <button type="button">
                <i @click.prevent="hideTarget('search', 'search-active')"
                    class="lab-line-circle-cross text-xl text-danger"></i>
            </button>
        </div>
        <div
            class="w-full h-10 rounded-3xl flex items-center gap-2 px-4 mb-4 border border-gray-100 bg-gray-100 transition-all duration-300 focus-within:border-primary focus-within:bg-white">
            <button class="lab-line-search text-lg flex-shrink-0"></button>
            <input id="searchSomething" v-model="searchProduct" @keyup="searchElement" class="w-full h-full"
                type="search" :placeholder="$t('label.search') + '...'">
        </div>
        <div class="lg:hidden h-[calc(100vh_-_140px)] rounded-xl overflow-y-auto p-4 bg-gray-100">
            <ul v-if="searchProductLists.length > 0" id="searchProductLists">
                <li :key="searchProductList.name"
                    class="py-1 hover:px-2 whitespace-nowrap overflow-hidden text-ellipsis rounded-lg transition-all duration-300 hover:bg-white hover:text-primary"
                    @click.prevent="goSearchProduct(searchProductList.name)"
                    v-for="searchProductList in searchProductLists">{{ searchProductList.name }}</li>
            </ul>
        </div>
    </form>

    <div id="order-modal" v-if="orderNotificationStatus" ref="orderNotificationModal" class="modal active ff-modal">
        <div class="modal-dialog max-w-[360px] p-6 text-center relative">
            <button @click.prevent="closeOrderNotificationModal('order-modal', 'active')"
                class="modal-close absolute top-4 right-4">
                <i class="fa-regular fa-circle-xmark"></i>
            </button>
            <h3 class="text-[18px] font-semibold leading-8 mb-6">
                {{ orderNotificationMessage }}
                <span class="block">{{ $t('message.please_check_your_order_list') }}</span>
            </h3>
            <router-link :to="{ path: '/admin/' + orderNotification.url }"
                class="db-btn h-[38px] shadow-[0px 4px 12px 0px #00A2514D] bg-primary text-white">
                {{ $t('button.let_me_check') }}
            </router-link>
        </div>
    </div>
</template>

<script>
import statusEnum from "../../../enums/modules/statusEnum";
import { onMounted, ref } from "vue";
import targetService from "../../../services/targetService";
import appService from "../../../services/appService";
import activityEnum from "../../../enums/modules/activityEnum";
import roleEnum from "../../../enums/modules/roleEnum";
import MenuChildrenComponent from "../../frontend/components/MenuChildrenComponent.vue";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage, isSupported } from "firebase/messaging";
import _ from "lodash";
import axios from 'axios';
import composables from "../../../composables/composables";

export default {
    name: "FrontendNavbarComponent",
    components: { MenuChildrenComponent },
    data() {
        return {
            loading: {
                isActive: false,
            },
            isSticky: false,
            lastScrollTop: 0,
            isScrollingUp: true,
            searchProductLists: [],
            currentRoute: "",
            defaultLanguage: null,
            enums: {
                activityEnum: activityEnum,
                roleEnum: roleEnum
            },
            languageProps: {
                paginate: 0,
                order_column: "id",
                order_type: "asc",
                status: statusEnum.ACTIVE
            },
            categoryTabStatus: false,
            activeTab: null,
            searchProduct: "",
            orderNotificationStatus: false,
            orderNotificationMessage: "",
            orderNotification: {
                permission: false,
                url: ""
            },
        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        },
        authDefaultPermission: function () {
            return this.$store.getters.authDefaultPermission;
        },
        profile: function () {
            return this.$store.getters.authInfo;
        },
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        language: function () {
            return this.$store.getters['frontendLanguage/show'];
        },
        languages: function () {
            return this.$store.getters['frontendLanguage/lists'];
        },
        categories: function () {
            return this.$store.getters['frontendProductCategory/trees'];
        },
        wishlists: function () {
            return this.$store.getters['frontendWishlist/lists'];
        },
        carts: function () {
            return this.$store.getters['frontendCart/lists'];
        },
        flashSaleProducts: function () {
            return this.$store.getters["frontendProduct/flashSaleProducts"];
        },
        offerProducts: function () {
            return this.$store.getters["frontendProduct/offerProducts"];
        },
        defaultMenu: function () {
            return this.$store.getters.authDefaultMenu;
        }
    },
    mounted() {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 0) this.isSticky = true;
            else this.isSticky = false;
            const st = window.scrollY || document.documentElement.scrollTop;
            if (st > this.lastScrollTop) {
                this.isScrollingUp = false;
            }
            else {
                this.isScrollingUp = true;
            }
            this.lastScrollTop = st <= 0 ? 0 : st;

            const resetRoutes = [
                'frontend.account.overview',
                'frontend.account.orderHistory',
                'frontend.account.accountInfo',
                'frontend.account.changePassword',
                'frontend.account.address',
                'auth.login',
                'auth.signup',
                'auth.signupVerify'
            ];

            if (resetRoutes.includes(this.$route.name)) {
                this.isSticky = false;
                this.isScrollingUp = true;
            }
        })

        this.currentRoute = this.$route.path;
        this.loading.isActive = true;
        this.orderPermissionCheck();
        this.$store.dispatch('frontendSetting/lists').then(res => {
            this.defaultLanguage = res.data.data.site_default_language;
            const globalState = this.$store.getters['globalState/lists'];
            if (globalState.language_id > 0) {
                this.defaultLanguage = globalState.language_id;
            }

            this.loading.isActive = false;
            this.$store.dispatch('frontendLanguage/lists', this.languageProps).then().catch();
            this.$store.dispatch('frontendLanguage/show', this.defaultLanguage).then(res => {
                this.$i18n.locale = res.data.data.code;
                this.$store.dispatch("globalState/init", {
                    language_code: res.data.data.code,
                    display_mode: res.data.data.display_mode
                });
            }).catch();

            window.setTimeout(async () => {

                try {

                    this.$store.dispatch('frontendCart/initOrderType', { order_type: orderTypeEnum.DELIVERY });

                    const supported = await isSupported();
                    if (!supported) {
                        return;
                    }

                    if (this.$store.getters.authStatus && res.data.data.notification_fcm_api_key && res.data.data.notification_fcm_auth_domain && res.data.data.notification_fcm_project_id && res.data.data.notification_fcm_storage_bucket && res.data.data.notification_fcm_messaging_sender_id && res.data.data.notification_fcm_app_id && res.data.data.notification_fcm_measurement_id) {
                        initializeApp({
                            apiKey: res.data.data.notification_fcm_api_key,
                            authDomain: res.data.data.notification_fcm_auth_domain,
                            projectId: res.data.data.notification_fcm_project_id,
                            storageBucket: res.data.data.notification_fcm_storage_bucket,
                            messagingSenderId: res.data.data.notification_fcm_messaging_sender_id,
                            appId: res.data.data.notification_fcm_app_id,
                            measurementId: res.data.data.notification_fcm_measurement_id
                        });
                        const messaging = getMessaging();

                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                getToken(messaging, { vapidKey: res.data.data.notification_fcm_public_vapid_key }).then((currentToken) => {
                                    if (currentToken) {
                                        axios.post('/frontend/device-token/web', { token: currentToken }).then().catch((error) => {
                                            if (error.response.data.message === 'Unauthenticated.') {
                                                this.$store.dispatch('loginDataReset');
                                            }
                                        });
                                    }
                                }).catch();
                            }
                        });

                        onMessage(messaging, (payload) => {
                            const notificationTitle = payload.notification.title;
                            const notificationOptions = {
                                body: payload.notification.body,
                                icon: '/images/required/firebase-logo.png'
                            };
                            new Notification(notificationTitle, notificationOptions);

                            if (payload.data.topicName === 'new-order-found' && this.orderNotification.permission) {
                                this.orderNotificationStatus = true;
                                this.orderNotificationMessage = payload.notification.body;
                                const audio = new Audio(res.data.data.notification_audio);
                                audio.play();
                            }
                        });
                    }

                } catch (error) { }
            }, 3000);

            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });

        this.loading.isActive = true;
        this.$store.dispatch('frontendProductCategory/trees').then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });

        if (this.logged) {
            this.loading.isActive = true;
            this.$store.dispatch("frontendWishlist/lists").then((res) => {
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        }
    },
    methods: {
        handlePaper: function (e) {
            composables.handlePaper(e);
        },
        openCanvas: function (id) {
            composables.openCanvas(id);
        },
        showTarget: function (id, cClass) {
            targetService.showTarget(id, cClass);
        },
        hideTarget: function (id, cClass) {
            targetService.hideTarget(id, cClass);
        },
        textShortener: function (text, number = 30) {
            return appService.textShortener(text, number);
        },
        checkIsPathAndRoutePathSame(path) {
            if (this.currentRoute === path) {
                return true;
            }
        },
        searchReset: function () {
            this.searchProduct = "";
        },
        changeLanguage: function (id, code, mode) {
            this.defaultLanguage = id;
            this.$store.dispatch("globalState/set", {
                language_id: id,
                language_code: code,
                display_mode: mode
            }).then(res => {
                this.$store.dispatch('frontendLanguage/show', id).then(res => {
                    this.$i18n.locale = res.data.data.code;
                }).catch();
            }).catch();

            let element = document.getElementById("languagePaper");
            if (element.parentElement.className.includes('active')) {
                element.parentElement.classList.remove('active');
            }
        },
        logout: function () {
            this.$store.dispatch("logout").then(res => {
                this.$router.push({ name: "frontend.home" });
            }).catch();
        },
        search: function () {
            if (typeof this.searchProduct !== "undefined" && this.searchProduct !== "") {
                this.$router.push({ name: "frontend.product", query: { name: this.searchProduct } });
                this.searchProduct = "";
                this.hideTarget('search', 'search-active')
            }
        },
        orderPermissionCheck: function () {
            const permissions = this.$store.getters.authPermission;
            if (permissions.length > 0) {
                _.forEach(permissions, (permission) => {
                    if (permission.name === 'online-orders') {
                        if (permission.access === true) {
                            this.orderNotification.permission = true;
                            this.orderNotification.url = permission.url;
                        }
                    }
                });
            }
        },
        closeOrderNotificationModal: function (id, cClass) {
            targetService.hideTarget(id, cClass);
            this.orderNotificationStatus = false;
        },
        searchElement: function () {
            if (this.searchProduct && this.searchProduct.length > 2) {
                let url = `frontend/product`;
                url = url + appService.requestHandler({ name: this.searchProduct });
                axios.get(url).then((res) => {
                    this.searchProductLists = res.data.data;
                }).catch();
            } else {
                this.searchProductLists = [];
            }
        },

        goSearchProduct: function (slug) {
            targetService.hideTarget('search', 'search-active');
            this.$router.push('/product?name=' + slug);
        },

        hideCategoryPaper: function () {
            let element = document.getElementById("navBarCategoryBtn");
            if (element.parentElement.className.includes('active')) {
                element.parentElement.classList.remove('active');
            }
        },
        hideProfilePaper: function () {
            let element = document.getElementById("profilePaperBtn");
            if (element.parentElement.className.includes('active')) {
                element.parentElement.classList.remove('active');
            }
        }
    },
    watch: {
        $route(to, from) {
            this.currentRoute = to.path;
        },
    }
}
</script>