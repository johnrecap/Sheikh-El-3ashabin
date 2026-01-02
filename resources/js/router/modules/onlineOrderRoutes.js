import OnlineOrderComponent from "../../components/admin/onlineOrders/OnlineOrderComponent.vue";
import OnlineOrderListComponent from "../../components/admin/onlineOrders/OnlineOrderListComponent.vue";
import OnlineOrderShowComponent from "../../components/admin/onlineOrders/OnlineOrderShowComponent.vue";

export default [
    {
        path: '/admin/online-orders',
        component: OnlineOrderComponent,
        name: 'admin.order',
        redirect: {name: 'admin.order.list'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'online-orders',
            breadcrumb: 'online_orders'
        },
        children: [
            {
                path: '',
                component: OnlineOrderListComponent,
                name: 'admin.order.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'online-orders',
                    breadcrumb: ''
                },
            },
            {
                path: "show/:id",
                component: OnlineOrderShowComponent,
                name: "admin.order.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "online-orders",
                    breadcrumb: "view",
                },
            }
        ]
    }
]
