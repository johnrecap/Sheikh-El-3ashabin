import PosOrderComponent from "../../components/admin/posOrders/PosOrderComponent.vue";
import PosOrderListComponent from "../../components/admin/posOrders/PosOrderListComponent.vue";
import PosOrderShowComponent from "../../components/admin/posOrders/PosOrderShowComponent.vue";

export default [
    {
        path: "/admin/pos-orders",
        component: PosOrderComponent,
        name: "admin.pos.orders",
        redirect: { name: "admin.pos.orders.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'pos',
            breadcrumb: 'pos_orders'
        },
        children: [
            {
                path: "",
                component: PosOrderListComponent,
                name: "admin.pos.orders.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "pos",
                    breadcrumb: "",
                },
            },
            {
                path: "show/:id",
                component: PosOrderShowComponent,
                name: "admin.pos.orders.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "pos",
                    breadcrumb: "view",
                },
            }
        ],
    },
];
