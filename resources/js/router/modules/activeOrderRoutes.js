
import ActiveOrderListComponent from '../../components/admin/activeOrders/ActiveOrderListComponent.vue'
import ActiveOrderComponent from '../../components/admin/activeOrders/ActiveOrderComponent.vue'
import ActiveOrderShowComponent from '../../components/admin/activeOrders/ActiveOrderShowComponent.vue'
import OrderShowComponent from '../../components/admin/dashboard/deliveryBoy/OrderShowComponent.vue'

export default [
    {
        path: "/admin/dashboard/active-order/show/:id",
        component: OrderShowComponent,
        name: "admin.dashboard.activeOrder.show",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "dashboard",
            breadcrumb: "",
        }
    },
    {
        path: '/admin/active-orders',
        component: ActiveOrderComponent,
        name: 'admin.activeOrder',
        redirect: { name: 'admin.activeOrder.list' },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'active-orders',
            breadcrumb: 'active_orders'
        },
        children: [
            {
                path: '',
                component: ActiveOrderListComponent,
                name: 'admin.activeOrder.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'active-orders',
                    breadcrumb: ''
                }

            },
            {
                path: 'show/:id',
                component: ActiveOrderShowComponent,
                name: 'admin.activeOrder.show',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'active-orders',
                    breadcrumb: 'view'
                }
            },
        ],
    }
]