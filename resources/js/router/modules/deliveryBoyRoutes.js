import DeliveryBoyComponent from "../../components/admin/deliveryBoys/DeliveryBoyComponent.vue";
import DeliveryBoyListComponent from "../../components/admin/deliveryBoys/DeliveryBoyListComponent.vue";
import DeliveryBoyShowComponent from "../../components/admin/deliveryBoys/DeliveryBoyShowComponent.vue";
import DeliveryBoyOrderDetailsComponent from "../../components/admin/deliveryBoys/DeliveryBoyOrderDetailsComponent.vue";
import DeliveredOrderShowComponent from "../../components/admin/deliveryBoys/deliveredOrder/DeliveredOrderShowComponent.vue";

export default [
    {
        path: "/admin/delivery-boys",
        component: DeliveryBoyComponent,
        name: "admin.delivery-boys",
        redirect: {  name: "#",},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "delivery-boys",
            breadcrumb: "delivery_boys",
        },
        children: [
            {
                path: "",
                component: DeliveryBoyListComponent,
                name: "#",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "delivery-boys",
                    breadcrumb: "",
                },
            },
            {
                path: "show/:id",
                component: DeliveryBoyShowComponent,
                name: "admin.delivery-boys.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "delivery-boys",
                    breadcrumb: "view",
                },
            },
            {
                path: "show/:id/:orderId",
                component: DeliveryBoyOrderDetailsComponent,
                name: "admin.delivery-boys.order.details",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "delivery-boys",
                    breadcrumb: "order_details",
                },
            },
            {
                path: "delivered-order/show/:id/:orderId",
                component: DeliveredOrderShowComponent,
                name: "admin.delivery-boys.delivered-order.details",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "delivery-boys",
                    breadcrumb: "delivered_order_details",
                },
            },
        ],
    },
];
