import { createRouter, createWebHistory } from "vue-router";
import ENV from '../config/env';
import appService from "../services/appService";
import DashboardComponent from "../components/admin/dashboard/DashboardComponent.vue";
import ExceptionComponent from "../components/exception/ExceptionComponent.vue";
import NotFoundComponent from "../components/exception/NotFoundComponent.vue";
import store from "../store";
import authRoutes from "./modules/authRoutes";
import settingRoutes from "./modules/settingRoutes";
import productsRoutes from "./modules/productsRoutes";
import frontendRoutes from "./modules/frontendRoutes";
import profileRoutes from "./modules/profileRoutes";
import transactionRoutes from "./modules/transactionRoutes";
import salesReportRoutes from "./modules/salesReportRoutes";
import creditBalanceReportRoutes from "./modules/creditBalanceReportRoutes";
import pushNotificationRoutes from "./modules/pushNotificationRoutes";
import administratorRoutes from "./modules/administratorRoutes";
import customerRoutes from "./modules/customerRoutes";
import employeeRoutes from "./modules/employeeRoutes";
import deliveryBoyRoutes from "./modules/deliveryBoyRoutes";
import couponRoutes from "./modules/couponRoutes";
import PromotionRoutes from "./modules/PromotionRoutes";
import ProductSectionRoutes from "./modules/ProductSectionRoutes";
import purchaseRoutes from "./modules/purchaseRoutes";
import stockRoutes from "./modules/stockRoutes";
import returnOrderRoutes from "./modules/returnOrderRoutes";
import damageRoutes from "./modules/damageRoutes";
import onlineOrderRoutes from "./modules/onlineOrderRoutes";
import productsReportRoutes from "./modules/productsReportRoutes";
import posOrderRoutes from "./modules/posOrderRoutes";
import posRoutes from "./modules/posRoutes";
import subscriberRoutes from "./modules/subscriberRoutes";
import orderHistoryRoutes from "./modules/orderHistoryRoutes";
import activeOrderRoutes from "./modules/activeOrderRoutes";


const baseRoutes = [
    {
        path: "/",
        redirect: { name: "frontend.home" },
        name: "root"
    },
    {
        path: "/:pathMatch(.*)*",
        name: "route.notFound",
        component: NotFoundComponent,
        meta: {
            isFrontend: true,
        },
    },
    {
        path: "/exception",
        name: "route.exception",
        component: ExceptionComponent,
    },
    {
        path: "/admin/dashboard",
        component: DashboardComponent,
        name: "admin.dashboard",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "dashboard",
            breadcrumb: "dashboard",
        },
    },
];

export const routes = baseRoutes.concat(
    frontendRoutes,
    authRoutes,
    settingRoutes,
    profileRoutes,
    productsRoutes,
    administratorRoutes,
    customerRoutes,
    deliveryBoyRoutes,
    employeeRoutes,
    transactionRoutes,
    salesReportRoutes,
    creditBalanceReportRoutes,
    pushNotificationRoutes,
    productsRoutes,
    couponRoutes,
    PromotionRoutes,
    ProductSectionRoutes,
    purchaseRoutes,
    stockRoutes,
    returnOrderRoutes,
    damageRoutes,
    onlineOrderRoutes,
    productsReportRoutes,
    posOrderRoutes,
    posRoutes,
    subscriberRoutes,
    orderHistoryRoutes,
    activeOrderRoutes
);

const permission = store.getters.authPermission;
appService.recursiveRouter(routes, permission);

const API_URL = ENV.API_URL;
const router = createRouter({
    linkActiveClass: "active",
    mode: 'history',
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return { left: 0, top: 0 }
    }
});

router.beforeEach((to, from, next) => {
    if (to.meta.auth === true) {
        if (!store.getters.authStatus) {
            next({ name: "auth.login" });
        } else {
            if (to.meta.isFrontend === false) {
                if (to.meta.access === false) {
                    next({
                        name: "route.exception",
                    });
                } else {
                    next();
                }
            } else {
                next();
            }
        }
    } else if ((to.name === "auth.login" || to.name === "auth.signup" || to.name === "auth.forgotPassword") && store.getters.authStatus) {
        next({ name: "frontend.home" });
    } else {
        next();
    }
});
export default router;
