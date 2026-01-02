import HomeComponent from "../../components/frontend/home/HomeComponent.vue";
import WishlistComponent from "../../components/frontend/wishlist/WishlistComponent.vue";
import OrderHistoryComponent from "../../components/frontend/account/orderHistory/OrderHistoryComponent.vue";
import OrderDetailsComponent from "../../components/frontend/account/orderDetails/OrderDetailsComponent.vue";
import ChangePasswordComponent from "../../components/frontend/account/changePassword/ChangePasswordComponent.vue";
import AddressComponent from "../../components/frontend/account/address/AddressComponent.vue";
import PageComponent from "../../components/frontend/page/PageComponent.vue";
import ProductComponent from "../../components/frontend/product/ProductComponent.vue";
import PromotionProductComponent from "../../components/frontend/product/PromotionProductComponent.vue";
import ProductSectionProductComponent from "../../components/frontend/product/ProductSectionProductComponent.vue";
import FlashSaleProductComponent from "../../components/frontend/product/FlashSaleProductComponent.vue";
import OfferProductComponent from "../../components/frontend/product/OfferProductComponent.vue";
import DailyDealsProductComponent from "../../components/frontend/product/DailyDealsProductComponent.vue";
import OverviewComponent from "../../components/frontend/account/overview/OverviewComponent.vue";
import AccountComponent from "../../components/frontend/account/AccountComponent.vue";
import AccountInfoComponent from "../../components/frontend/account/accountInfo/AccountInfoComponent.vue";
import CheckoutComponent from "../../components/frontend/checkout/CheckoutComponent.vue";
import CheckoutCartListComponent from "../../components/frontend/checkout/cartList/CartListComponent.vue";
import CartListHeaderComponent from "../../components/frontend/checkout/cartList/HeaderComponent.vue";
import CheckoutCheckoutComponent from "../../components/frontend/checkout/checkout/CheckoutComponent.vue";
import CheckoutHeaderComponent from "../../components/frontend/checkout/checkout/HeaderComponent.vue";
import CheckoutPaymentComponent from "../../components/frontend/checkout/payment/PaymentComponent.vue";
import PaymentHeaderComponent from "../../components/frontend/checkout/payment/HeaderComponent.vue";
import MostPopularProductComponent from "../../components/frontend/product/MostPopularProductComponent.vue";
import GuestOrderSuccessComponent from "../../components/frontend/order/GuestOrderSuccessComponent.vue";

export default [
    {
        path: "/home",
        component: HomeComponent,
        name: "frontend.home",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/product",
        component: ProductComponent,
        name: "frontend.product",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/offers",
        component: OfferProductComponent,
        name: "frontend.offers",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/daily-deals",
        component: DailyDealsProductComponent,
        name: "frontend.daily.deals",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/promotion/:slug",
        component: PromotionProductComponent,
        name: "frontend.promotion.products",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/product-section/:slug",
        component: ProductSectionProductComponent,
        name: "frontend.productSection.products",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/most-popular",
        component: MostPopularProductComponent,
        name: "frontend.mostPopular.products",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },

    {
        path: "/flash-sale",
        component: FlashSaleProductComponent,
        name: "frontend.flashSale.products",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/wishlist",
        component: WishlistComponent,
        name: "frontend.wishlist",
        meta: {
            isFrontend: true,
            auth: true,
        },
    },
    {
        path: "/page/:slug",
        component: PageComponent,
        name: "frontend.page",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/order-success/:id",
        component: GuestOrderSuccessComponent,
        name: "frontend.orderSuccess",
        meta: {
            isFrontend: true,
            auth: false,
        },
    },
    {
        path: "/account",
        component: AccountComponent,
        name: "frontend.account",
        redirect: { name: "frontend.account.overview" },
        meta: {
            isFrontend: true,
            auth: true,
        },
        children: [
            {
                path: "overview",
                component: OverviewComponent,
                name: "frontend.account.overview",
                meta: {
                    isFrontend: true,
                    auth: true
                }
            },
            {
                path: "order-history",
                component: OrderHistoryComponent,
                name: "frontend.account.orderHistory",
                meta: {
                    isFrontend: true,
                    auth: true,
                }
            },
            {
                path: "order-details/:id",
                component: OrderDetailsComponent,
                name: "frontend.account.orderDetails",
                meta: {
                    isFrontend: true,
                    auth: true,
                },
            },
            {
                path: "account-info",
                component: AccountInfoComponent,
                name: "frontend.account.accountInfo",
                meta: {
                    isFrontend: true,
                    auth: true,
                },
            },
            {
                path: "change-password",
                component: ChangePasswordComponent,
                name: "frontend.account.changePassword",
                meta: {
                    isFrontend: true,
                    auth: true,
                },
            },
            {
                path: "address",
                component: AddressComponent,
                name: "frontend.account.address",
                meta: {
                    isFrontend: true,
                    auth: true,
                },
            }
        ]
    },
    {
        path: "/checkout",
        component: CheckoutComponent,
        name: "frontend.checkout",
        redirect: { name: "frontend.checkout.checkout" },
        meta: {
            isFrontend: true,
            auth: false, // Changed to allow guest checkout
        },
        children: [
            {
                path: "cart-list",
                components: { default: CheckoutCartListComponent, header: CartListHeaderComponent },
                name: "frontend.checkout.cartList",
                meta: {
                    isFrontend: true,
                    auth: false // Changed to allow guest checkout
                }
            },
            {
                path: "checkout",
                components: { default: CheckoutCheckoutComponent, header: CheckoutHeaderComponent },
                name: "frontend.checkout.checkout",
                meta: {
                    isFrontend: true,
                    auth: false // Changed to allow guest checkout
                }
            },
            {
                path: "payment",
                components: { default: CheckoutPaymentComponent, header: PaymentHeaderComponent },
                name: "frontend.checkout.payment",
                meta: {
                    isFrontend: true,
                    auth: false // Changed to allow guest checkout
                }
            }
        ]
    }
];
