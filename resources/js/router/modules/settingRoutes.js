import SettingsComponent from "../../components/admin/settings/SettingsComponent.vue";
import CompanyComponent from "../../components/admin/settings/Company/CompanyComponent.vue";
import SiteComponent from "../../components/admin/settings/Site/SiteComponent.vue";
import MailComponent from "../../components/admin/settings/Mail/MailComponent.vue";
import OtpComponent from "../../components/admin/settings/Otp/OtpComponent.vue";
import NotificationComponent from "../../components/admin/settings/Notification/NotificationComponent.vue";
import SocialMediaComponent from "../../components/admin/settings/SocialMedia/SocialMediaComponent.vue";
import LicenseComponent from "../../components/admin/settings/License/LicenseComponent.vue";
import CookiesComponent from "../../components/admin/settings/Cookies/CookiesComponent.vue";
import AnalyticComponent from "../../components/admin/settings/analytics/AnalyticComponent.vue";
import AnalyticListComponent from "../../components/admin/settings/analytics/AnalyticListComponent.vue";
import AnalyticShowComponent from "../../components/admin/settings/analytics/AnalyticShowComponent.vue";
import ThemeComponent from "../../components/admin/settings/Theme/ThemeComponent.vue";
import SliderComponent from "../../components/admin/settings/Slider/SliderComponent.vue";
import SliderListComponent from "../../components/admin/settings/Slider/SliderListComponent.vue";
import SliderShowComponent from "../../components/admin/settings/Slider/SliderShowComponent.vue";
import CurrencyComponent from "../../components/admin/settings/Currency/CurrencyComponent.vue";
import CurrencyListComponent from "../../components/admin/settings/Currency/CurrencyListComponent.vue";
import ProductCategoryListComponent from "../../components/admin/settings/ProductCategory/ProductCateogryListComponent.vue";
import ProductCategoryComponent from "../../components/admin/settings/ProductCategory/ProductCategoryComponent.vue";
import ProductCategoryShowComponent from "../../components/admin/settings/ProductCategory/ProductCategoryShowComponent.vue";
import ProductAttributeComponent from "../../components/admin/settings/ProductAttribute/ProductAttributeComponent.vue";
import ProductAttributeListComponent from "../../components/admin/settings/ProductAttribute/ProductAttributeListComponent.vue";
import ProductAttributeShowComponent from "../../components/admin/settings/ProductAttribute/ProductAttributeShowComponent.vue";
import TaxComponent from "../../components/admin/settings/Tax/TaxComponent.vue";
import TaxListComponent from "../../components/admin/settings/Tax/TaxListComponent.vue";
import PageComponent from "../../components/admin/settings/Page/PageComponent.vue";
import PageListComponent from "../../components/admin/settings/Page/PageListComponent.vue";
import PageShowComponent from "../../components/admin/settings/Page/PageShowComponent.vue";
import LanguageComponent from "../../components/admin/settings/Language/LanguageComponent.vue";
import LanguageListComponent from "../../components/admin/settings/Language/LanguageListComponent.vue";
import LanguageShowComponent from "../../components/admin/settings/Language/LanguageShowComponent.vue";
import SmsGatewayComponent from "../../components/admin/settings/SmsGateway/SmsGatewayComponent.vue";
import PaymentGatewayComponent from "../../components/admin/settings/PaymentGateway/PaymentGatewayComponent.vue";
import RoleComponent from "../../components/admin/settings/Role/RoleComponent.vue";
import RoleListComponent from "../../components/admin/settings/Role/RoleListComponent.vue";
import RoleShowComponent from "../../components/admin/settings/Role/RoleShowComponent.vue";
import ProductBrandComponent from "../../components/admin/settings/ProductBrand/ProductBrandComponent.vue";
import ProductBrandListComponent from "../../components/admin/settings/ProductBrand/ProductBrandListComponent.vue";
import ProductBrandShowComponent from "../../components/admin/settings/ProductBrand/ProductBrandShowComponent.vue";
import BenefitComponent from "../../components/admin/settings/Benefit/BenefitComponent.vue";
import BenefitListComponent from "../../components/admin/settings/Benefit/BenefitListComponent.vue";
import BenefitShowComponent from "../../components/admin/settings/Benefit/BenefitShowComponent.vue";
import UnitComponent from "../../components/admin/settings/Unit/UnitComponent.vue";
import UnitListComponent from "../../components/admin/settings/Unit/UnitListComponent.vue";
import SupplierComponent from "../../components/admin/settings/Supplier/SupplierComponent.vue";
import SupplierListComponent from "../../components/admin/settings/Supplier/SupplierListComponent.vue";
import SupplierShowComponent from "../../components/admin/settings/Supplier/SupplierShowComponent.vue";
import NotificationAlertComponent from "../../components/admin/settings/NotificationAlert/NotificationAlertComponent.vue";
import OutletComponent from "../../components/admin/settings/Outlet/OutletComponent.vue";
import OutletListComponent from "../../components/admin/settings/Outlet/OutletListComponent.vue";
import OutletShowComponent from "../../components/admin/settings/Outlet/OutletShowComponent.vue";
import DeliveryZoneComponent from "../../components/admin/settings/DeliveryZone/DeliveryZoneComponent.vue";
import DeliveryZoneListComponent from "../../components/admin/settings/DeliveryZone/DeliveryZoneListComponent.vue";
import DeliveryZoneShowComponent from "../../components/admin/settings/DeliveryZone/DeliveryZoneShowComponent.vue";

import BackupComponent from "../../components/admin/settings/Backup/BackupComponent.vue";

export default [
    {
        path: "/admin/settings",
        component: SettingsComponent,
        name: "admin.settings",
        redirect: { name: "admin.settings.company" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "settings",
            breadcrumb: "settings",
        },
        children: [
            {
                path: "system-backup",
                component: BackupComponent,
                name: "admin.settings.backup",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "backup",
                },
            },
            {
                path: "company",
                component: CompanyComponent,
                name: "admin.settings.company",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "company",
                },
            },
            {
                path: "site",
                component: SiteComponent,
                name: "admin.settings.site",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "site",
                },
            },
            {
                path: "delivery-zones",
                component: DeliveryZoneComponent,
                name: "admin.settings.deliveryZone",
                redirect: { name: "admin.settings.deliveryZone.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "delivery_zones",
                },
                children: [
                    {
                        path: "list",
                        component: DeliveryZoneListComponent,
                        name: "admin.settings.deliveryZone.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: DeliveryZoneShowComponent,
                        name: "admin.settings.deliveryZone.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "mail",
                component: MailComponent,
                name: "admin.settings.mail",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "mail",
                },
            },
            {
                path: "otp",
                component: OtpComponent,
                name: "admin.settings.otp",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "otp",
                },
            },
            {
                path: "notification",
                component: NotificationComponent,
                name: "admin.settings.notification",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "notification",
                },
            },
            {
                path: "social-media",
                component: SocialMediaComponent,
                name: "admin.settings.socialMedia",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "social_media",
                },
            },
            {
                path: "cookies",
                component: CookiesComponent,
                name: "admin.settings.cookies",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "cookies",
                },
            },
            {
                path: "analytics",
                component: AnalyticComponent,
                name: "admin.settings.analytic",
                redirect: { name: "admin.settings.analytic.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "analytics",
                },
                children: [
                    {

                        path: "list",
                        component: AnalyticListComponent,
                        name: "admin.settings.analytic.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: AnalyticShowComponent,
                        name: "admin.settings.analytic.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ]
            },
            {
                path: "theme",
                component: ThemeComponent,
                name: "admin.settings.theme",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "theme",
                },
            },
            {
                path: "sliders",
                component: SliderComponent,
                name: "admin.settings.slider",
                redirect: { name: "admin.settings.slider.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "sliders",
                },
                children: [
                    {
                        path: "list",
                        component: SliderListComponent,
                        name: "admin.settings.slider.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: SliderShowComponent,
                        name: "admin.settings.slider.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "currencies",
                component: CurrencyComponent,
                name: "admin.settings.currency",
                redirect: { name: "admin.settings.currency.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "currencies",
                },
                children: [
                    {
                        path: "list",
                        component: CurrencyListComponent,
                        name: "admin.settings.currency.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "product-categories",
                component: ProductCategoryComponent,
                name: "admin.settings.productCategory",
                redirect: { name: "admin.settings.productCategory.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "product_categories",
                },
                children: [
                    {
                        path: "list",
                        component: ProductCategoryListComponent,
                        name: "admin.settings.productCategory.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: ProductCategoryShowComponent,
                        name: "admin.settings.productCategory.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "product-brands",
                component: ProductBrandComponent,
                name: "admin.settings.productBrand",
                redirect: { name: "admin.settings.productBrand.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "product_brands",
                },
                children: [
                    {
                        path: "list",
                        component: ProductBrandListComponent,
                        name: "admin.settings.productBrand.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: ProductBrandShowComponent,
                        name: "admin.settings.productBrand.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },

            {
                path: "suppliers",
                component: SupplierComponent,
                name: "admin.settings.supplier",
                redirect: { name: "admin.settings.supplier.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "suppliers",
                },
                children: [
                    {
                        path: "list",
                        component: SupplierListComponent,
                        name: "admin.settings.supplier.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: SupplierShowComponent,
                        name: "admin.settings.supplier.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },

            {
                path: "product-attributes",
                component: ProductAttributeComponent,
                name: "admin.settings.productAttribute",
                redirect: { name: "admin.settings.productAttribute.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "product_attributes",
                },
                children: [
                    {
                        path: "list",
                        component: ProductAttributeListComponent,
                        name: "admin.settings.productAttribute.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: ProductAttributeShowComponent,
                        name: "admin.settings.productAttribute.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "benefits",
                component: BenefitComponent,
                name: "admin.settings.benefit",
                redirect: { name: "admin.settings.benefit.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "benefits",
                },
                children: [
                    {
                        path: "list",
                        component: BenefitListComponent,
                        name: "admin.settings.benefit.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: BenefitShowComponent,
                        name: "admin.settings.benefit.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "units",
                component: UnitComponent,
                name: "admin.settings.unit",
                redirect: { name: "admin.settings.unit.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "units",
                },
                children: [
                    {
                        path: "list",
                        component: UnitListComponent,
                        name: "admin.settings.unit.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "taxes",
                component: TaxComponent,
                name: "admin.settings.tax",
                redirect: { name: "admin.settings.tax.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "taxes",
                },
                children: [
                    {
                        path: "list",
                        component: TaxListComponent,
                        name: "admin.settings.tax.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                ],
            },
            {
                path: "pages",
                component: PageComponent,
                name: "admin.settings.page",
                redirect: { name: "admin.settings.page.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "pages",
                },
                children: [
                    {
                        path: "list",
                        component: PageListComponent,
                        name: "admin.settings.page.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: PageShowComponent,
                        name: "admin.settings.page.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "languages",
                component: LanguageComponent,
                name: "admin.settings.language",
                redirect: { name: "admin.settings.language.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "languages",
                },
                children: [
                    {
                        path: "list",
                        component: LanguageListComponent,
                        name: "admin.settings.language.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: LanguageShowComponent,
                        name: "admin.settings.language.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "sms-gateway",
                component: SmsGatewayComponent,
                name: "admin.settings.smsGateway",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "sms_gateway",
                },
            },
            {
                path: "payment-gateway",
                component: PaymentGatewayComponent,
                name: "admin.settings.paymentGateway",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "payment_gateway",
                },
            },
            {
                path: "role",
                component: RoleComponent,
                name: "admin.settings.role",
                redirect: { name: "admin.settings.role.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "role_permissions",
                },
                children: [
                    {
                        path: "list",
                        component: RoleListComponent,
                        name: "admin.settings.role.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: RoleShowComponent,
                        name: "admin.settings.role.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "notification-alert",
                component: NotificationAlertComponent,
                name: "admin.settings.notificationAlert",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "notification_alert",
                }
            },
            {
                path: "outlets",
                component: OutletComponent,
                name: "admin.settings.outlet",
                redirect: { name: "admin.settings.outlet.list" },
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "outlets",
                },
                children: [
                    {
                        path: "list",
                        component: OutletListComponent,
                        name: "admin.settings.outlet.list",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "",
                        },
                    },
                    {
                        path: "show/:id",
                        component: OutletShowComponent,
                        name: "admin.settings.outlet.show",
                        meta: {
                            isFrontend: false,
                            auth: true,
                            permissionUrl: "settings",
                            breadcrumb: "view",
                        },
                    },
                ],
            },
            {
                path: "license",
                component: LicenseComponent,
                name: "admin.settings.license",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "settings",
                    breadcrumb: "license",
                }
            },

        ],
    },
];
