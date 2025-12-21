
import OrderHistoryListComponent from '../../components/admin/orderHistory/OrderHistoryListComponent.vue'
import OrderHistoryComponent from '../../components/admin/orderHistory/OrderHistoryComponent.vue'
import OrderHistoryShowComponent from '../../components/admin/orderHistory/OrderHistoryShowComponent.vue'


export default [
    {
        path: '/admin/order-history',
        component: OrderHistoryComponent,
        name: 'admin.orderHistory',
        redirect: { name: 'admin.orderHistory.list' },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'order-history',
            breadcrumb: 'order_history'
        },
        children: [
            {
                path: '',
                component: OrderHistoryListComponent,
                name: 'admin.orderHistory.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'order-history',
                    breadcrumb: ''
                }

            },
            {
                path: 'show/:id',
                component: OrderHistoryShowComponent,
                name: 'admin.orderHistory.show',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'order-history',
                    breadcrumb: 'view'
                }
            },
        ],
    }
]