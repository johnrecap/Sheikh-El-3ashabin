import { createStore } from "vuex";

import createPersistedState from "vuex-persistedstate";
import { auth } from "./modules/auth";
import { company } from "./modules/company";
import { countryCode } from './modules/countryCode';
import { mail } from "./modules/mail";
import { otp } from "./modules/otp";
import { notification } from "./modules/notification";
import { socialMedia } from "./modules/socialMedia";
import { license } from "./modules/license";
import { cookies } from "./modules/cookies";
import { page } from "./modules/page";
import { analytic } from "./modules/analytic";
import { analyticSection } from "./modules/analyticSection";
import { theme } from "./modules/theme";
import { slider } from "./modules/slider";
import { currency } from "./modules/currency";
import { site } from "./modules/site";
import { productCategory } from "./modules/productCategory";
import { productAttribute } from "./modules/productAttribute";
import { tax } from "./modules/tax";
import { menuSection } from "./modules/menuSection";
import { menuTemplate } from "./modules/menuTemplate";
import { language } from "./modules/language";
import { smsGateway } from "./modules/smsGateway";
import { paymentGateway } from "./modules/paymentGateway";
import { timezone } from "./modules/timezone";
import { productAttributeOption } from "./modules/productAttributeOption";
import { product } from "./modules/product";
import { frontendSetting } from "./modules/frontend/frontendSetting";
import { frontendLanguage } from "./modules/frontend/frontendLanguage";
import { globalState } from "./modules/frontend/globalState";
import { frontendEditProfile } from "./modules/frontend/frontendEditProfile";
import { role } from "./modules/role";
import { permission } from "./modules/permission";
import { administrator } from "./modules/administrator";
import { administratorAddress } from "./modules/administratorAddress";
import { customer } from "./modules/customer";
import { customerAddress } from "./modules/customerAddress";
import { employee } from './modules/employee';
import { deliveryBoy } from "./modules/deliveryBoy";
import { deliveryBoyAddress } from "./modules/deliveryBoyAddress";
import { deliveryBoyOrder } from "./modules/deliveryBoyOrder";
import { employeeAddress } from './modules/employeeAddress';
import { unit } from "./modules/unit";
import { productBrand } from "./modules/productBrand";
import { barcode } from "./modules/barcode";
import { transaction } from "./modules/transaction";
import { salesReport } from "./modules/salesReport";
import { creditBalanceReport } from "./modules/creditBalanceReport";
import { productVariation } from "./modules/productVariation";
import { pushNotification } from "./modules/pushNotification";
import { user } from "./modules/user";
import { coupon } from "./modules/coupon";
import { productSeo } from "./modules/productSeo";
import { frontendCountryCode } from "./modules/frontend/frontendCountryCode";
import { frontendPage } from "./modules/frontend/frontendPage";
import { frontendSlider } from "./modules/frontend/frontendSlider";
import { frontendProductCategory } from "./modules/frontend/frontendProductCategory";
import { frontendProduct } from "./modules/frontend/frontendProduct";
import { promotion } from "./modules/promotion";
import { promotionProduct } from "./modules/promotionProduct";
import { productSection } from "./modules/productSection";
import { productSectionProduct } from "./modules/productSectionProduct";
import { benefit } from "./modules/benefit";
import { frontendPromotion } from "./modules/frontend/frontendPromotion";
import { purchase } from './modules/purchase';
import { returnOrder } from './modules/returnOrder';
import { damage } from './modules/damage';
import { frontendBenefit } from "./modules/frontend/frontendBenefit";
import { frontendProductSection } from "./modules/frontend/frontendProductSection";
import { frontendAddress } from "./modules/frontend/frontendAddress";
import { supplier } from "./modules/supplier";
import { frontendWishlist } from "./modules/frontend/frontendWishlist";
import { frontendProductVariation } from "./modules/frontend/frontendProductVariation";
import { frontendSignup } from "./modules/frontend/frontendSignup";
import { frontendCart } from "./modules/frontend/frontendCart";
import { stock } from "./modules/stock";
import { frontendCoupon } from "./modules/frontend/frontendCoupon";
import { orderArea } from "./modules/orderArea";
import { notificationAlert } from "./modules/notificationAlert";
import { frontendPaymentGateway } from "./modules/frontend/frontendPaymentGateway";
import { frontendOrder } from "./modules/frontend/frontendOrder";
import { frontendOrderArea } from "./modules/frontend/frontendOrderArea";
import { dashboard } from "./modules/dashboard";
import { frontendOverview } from "./modules/frontend/frontendOverview";
import { onlineOrder } from "./modules/onlineOrder";
import { productsReport } from "./modules/productsReport";
import { myOrderDetails } from "./modules/myOrderDetails";
import { posOrder } from "./modules/posOrder";
import { posProductVariation } from "./modules/posProductVariation";
import { posProductCategory } from "./modules/posProductCategory";
import { posProduct } from "./modules/posProduct";
import { posCart } from "./modules/posCart";
import { outlet } from './modules/outlet';
import { frontendProductBrand } from './modules/frontend/frontendProductBrand';
import { frontendOutlet } from "./modules/frontend/frontendOutlet";
import { subscriber } from './modules/subscriber';
import { deliveryBoyDashboard } from "./modules/deliveryBoyDashboard";
import { deliveryZone } from "./modules/deliveryZone";
import { orderHistory } from "./modules/orderHistory";
import { frontendDeliveryZone } from "./modules/frontend/frontendDeliveryZone";
import { activeOrder } from "./modules/activeOrder";

export default new createStore({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        auth,
        company,
        countryCode,
        mail,
        otp,
        notification,
        socialMedia,
        license,
        cookies,
        page,
        analytic,
        analyticSection,
        theme,
        slider,
        currency,
        site,
        productCategory,
        tax,
        globalState,
        menuSection,
        menuTemplate,
        language,
        smsGateway,
        productAttribute,
        paymentGateway,
        timezone,
        productAttributeOption,
        role,
        permission,
        product,
        administrator,
        administratorAddress,
        customer,
        customerAddress,
        deliveryBoy,
        deliveryBoyAddress,
        deliveryBoyOrder,
        employee,
        employeeAddress,
        unit,
        productBrand,
        barcode,
        transaction,
        salesReport,
        creditBalanceReport,
        productVariation,
        pushNotification,
        user,
        productSeo,
        promotion,
        promotionProduct,
        productSection,
        productSectionProduct,
        benefit,
        purchase,
        damage,
        returnOrder,
        supplier,
        outlet,
        coupon,
        frontendSetting,
        frontendLanguage,
        frontendEditProfile,
        frontendCountryCode,
        frontendPage,
        frontendSlider,
        frontendProductCategory,
        frontendProduct,
        frontendBenefit,
        frontendPromotion,
        frontendProductSection,
        frontendWishlist,
        frontendProductVariation,
        frontendAddress,
        frontendSignup,
        frontendCart,
        frontendCoupon,
        stock,
        orderArea,
        notificationAlert,
        frontendPaymentGateway,
        frontendOrder,
        frontendOrderArea,
        dashboard,
        frontendOverview,
        onlineOrder,
        productsReport,
        myOrderDetails,
        posOrder,
        posProductVariation,
        posProductCategory,
        posProduct,
        posCart,
        frontendProductBrand,
        frontendOutlet,
        subscriber,
        deliveryBoyDashboard,
        deliveryZone,
        orderHistory,
        frontendDeliveryZone,
        activeOrder
    },
    plugins: [
        createPersistedState({
            paths: ["auth", "globalState", "frontendCart", "posCart"],
        }),
    ],
});
