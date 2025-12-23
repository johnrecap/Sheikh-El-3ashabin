<template>
    <button
        class="settings-btn w-full md:hidden flex items-center justify-center gap-2 p-2 rounded bg-primary text-white">
        <span class="capitalize">{{ $t('menu.settings_menu') }}</span>
        <i class="fa-solid fa-chevron-down text-sm"></i>
    </button>
    <div class="h-0 overflow-hidden md:h-auto md:overflow-auto transition-all duration-300 font-medium">
        <nav class="db-card p-3">
            <router-link :to="{ name: 'admin.settings.company' }" class="db-tab-btn">
                <i class="lab lab-line-company text-sm"></i>
                {{ $t("menu.company") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.site' }" class="db-tab-btn">
                <i class="lab lab-line-site  text-sm"></i>
                {{ $t("menu.site") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.deliveryZone' }" class="db-tab-btn">
                <i class="lab lab-line-site  text-sm"></i>
                {{ $t("menu.delivery_zones") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.mail' }" class="db-tab-btn">
                <i class="lab lab-line-mail text-sm"></i>
                {{ $t("menu.mail") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.otp' }" class="db-tab-btn">
                <i class="lab lab-line-otp text-sm"></i>
                {{ $t("menu.otp") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.notification' }" class="db-tab-btn">
                <i class="lab lab-line-notification text-sm"></i>
                {{ $t("menu.notification") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.notificationAlert' }" class="db-tab-btn">
                <i class="lab lab-line-notification-alert text-sm"></i>
                {{ $t("menu.notification_alert") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.socialMedia' }" class="db-tab-btn">
                <i class="lab lab-line-social text-sm"></i>
                {{ $t("menu.social_media") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.cookies' }" class="db-tab-btn">
                <i class="lab lab-line-cookies text-sm"></i>
                {{ $t("menu.cookies") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.analytic' }" class="db-tab-btn">
                <i class="lab lab-line-analytic text-sm"></i>
                {{ $t("menu.analytics") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.theme' }" class="db-tab-btn">
                <i class="lab lab-line-theme text-sm"></i>
                {{ $t("menu.theme") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.slider' }" class="db-tab-btn">
                <i class="lab lab-line-sliders text-sm"></i>
                {{ $t("menu.sliders") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.currency' }" class="db-tab-btn">
                <i class="lab lab-line-currencies text-sm"></i>
                {{ $t("menu.currencies") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.productCategory' }" class="db-tab-btn">
                <i class="lab lab-line-item-categories text-sm"></i>
                {{ $t("menu.product_categories") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.productAttribute' }" class="db-tab-btn">
                <i class="lab lab-line-item-attributes text-sm"></i>
                {{ $t("menu.product_attributes") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.productBrand' }" class="db-tab-btn">
                <i class="lab lab-line-brand text-sm"></i>
                {{ $t("menu.product_brands") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.supplier' }" class="db-tab-btn">
                <i class="lab lab-line-supplier text-sm"></i>
                {{ $t("menu.suppliers") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.outlet' }" class="db-tab-btn">
                <i class="lab lab-line-branches text-sm"></i>
                {{ $t("menu.outlets") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.benefit' }" class="db-tab-btn">
                <i class="lab lab-line-benefits text-sm"></i>
                {{ $t("menu.benefits") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.unit' }" class="db-tab-btn">
                <i class="lab lab-line-unit text-sm"></i>
                {{ $t("menu.units") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.tax' }" class="db-tab-btn">
                <i class="lab lab-line-taxes text-sm"></i>
                {{ $t("menu.taxes") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.page' }" class="db-tab-btn">
                <i class="lab lab-line-pages text-sm"></i>
                {{ $t("menu.pages") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.role' }" class="db-tab-btn">
                <i class="lab lab-line-role-permission text-sm"></i>
                {{ $t("menu.role_permissions") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.language' }" class="db-tab-btn">
                <i class="lab lab-line-global text-sm"></i>
                {{ $t("menu.languages") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.smsGateway' }" class="db-tab-btn">
                <i class="lab lab-line-sms text-sm"></i>
                {{ $t("menu.sms_gateway") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.paymentGateway' }" class="db-tab-btn">
                <i class="lab lab-line-payment-gateway text-sm"></i>
                {{ $t("menu.payment_gateway") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.license' }" class="db-tab-btn">
                <i class="lab-line-license-code text-sm"></i>
                {{ $t("menu.license") }}
            </router-link>
            <router-link :to="{ name: 'admin.settings.backup' }" class="db-tab-btn">
                <i class="fa fa-database text-sm"></i>
                {{ $t("menu.backup") }}
            </router-link>
        </nav>
    </div>
</template>

<script>
export default {
    name: "MenuComponent",
};
</script>