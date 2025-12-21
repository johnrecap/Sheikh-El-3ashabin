<template>
    <div v-if="theme === 'frontend'">
        <FrontendNavbarComponent />
        <FrontendCartComponent />
        <router-view></router-view>
        <FrontendMobileSideBarComponent />
        <FrontendMobileNavBarComponent />
        <FrontendMobileCategoryComponent />
        <FrontendMobileAccountComponent />
        <FrontendCookiesComponent />
        <FrontendFooterComponent />
    </div>

    <div v-if="theme === 'backend'">
        <main :class="sidebar ? 'ltr:lg:pl-64 rtl:lg:pr-64' : 'ltr:pl-0 rtl:pr-0'"
            class="pt-[121px] md:pt-16 font-admin text-paragraph transition-all duration-300">
            <BackendNavbarComponent />
            <BackendMenuComponent />
            <section class="p-3 lg:p-5">
                <router-view></router-view>
            </section>
        </main>
    </div>
</template>

<script>
import BackendNavbarComponent from "./layouts/backend/BackendNavbarComponent.vue";
import BackendMenuComponent from "./layouts/backend/BackendMenuComponent.vue";
import FrontendNavbarComponent from "./layouts/frontend/FrontendNavBarComponent.vue";
import FrontendFooterComponent from "./layouts/frontend/FrontendFooterComponent.vue";
import FrontendCartComponent from "./layouts/frontend/FrontendCartComponent.vue";
import FrontendMobileNavBarComponent from "./layouts/frontend/FrontendMobileNavBarComponent.vue";
import FrontendMobileCategoryComponent from "./layouts/frontend/FrontendMobileCategoryComponent.vue";
import FrontendMobileAccountComponent from "./layouts/frontend/FrontendMobileAccountComponent.vue";
import FrontendMobileSideBarComponent from "./layouts/frontend/FrontendMobileSideBarComponent.vue";
import FrontendCookiesComponent from "./layouts/frontend/FrontendCookiesComponent.vue";
import DisplayModeEnum from "../enums/modules/displayModeEnum";
import env from "../config/env";

export default {
    name: "DefaultComponent",
    components: {
        FrontendMobileSideBarComponent,
        FrontendMobileAccountComponent,
        FrontendMobileCategoryComponent,
        FrontendMobileNavBarComponent,
        FrontendCartComponent,
        FrontendNavbarComponent,
        FrontendFooterComponent,
        BackendNavbarComponent,
        BackendMenuComponent,
        FrontendCookiesComponent,
    },
    data() {
        return {
            theme: "frontend",
        }
    },
    beforeMount() {
        this.displayModeDefine();
        this.$store.dispatch('frontendSetting/lists').then(res => {
            this.$store.dispatch("globalState/init", {
                language_id: res.data.data.site_default_language,
                location: null,
                latitude: null,
                longitude: null
            });
        }).catch();

        if (env.DEMO === "true" || env.DEMO === true || env.DEMO === "1" || env.DEMO === 1) {
            this.$store.dispatch("authcheck").then(res => {
                if (res.data.status === false) {
                    this.$router.push({ name: "frontend.home" });
                };
            }).catch();
        }
    },
    computed: {
        logged: function () {
            return this.$store.getters.authStatus;
        },
        sidebar() {
            return this.$store.getters['globalState/lists'].topSidebar;
        },
        displayMode: function () {
            return this.$store.getters['globalState/lists'].display_mode;
        },
    },
    methods: {
        displayModeDefine: function () {
            let dir = "ltr";
            const attributes = {
                dir: "ltr",
            };
            if (this.$store.getters['globalState/lists'].display_mode === DisplayModeEnum.LTR) {
                dir = "ltr";
            } else {
                dir = "rtl";
            }
            Object.keys(attributes).forEach(attr => {
                document.documentElement.setAttribute(attr, dir);
            });
        }
    },
    watch: {
        $route(e) {
            if (e.meta.isFrontend === true) {
                document.body.style.backgroundColor = "";
                this.theme = "frontend";
            } else {
                this.theme = "backend";
                if (this.theme === "backend") {
                    document.body.style.backgroundColor = '#f7f7fc';
                }
            }
        },
        displayMode() {
            this.displayModeDefine();
        }
    },
}
</script>