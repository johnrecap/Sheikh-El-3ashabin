
import ReturnOrderComponent from '../../components/admin/returnOrders/ReturnOrderComponent.vue'
import ReturnOrderCreateAndEditComponent from '../../components/admin/returnOrders/ReturnOrderCreateAndEditComponent.vue'
import ReturnOrderListComponent from '../../components/admin/returnOrders/ReturnOrderListComponent.vue'
import ReturnOrderShowComponent from '../../components/admin/returnOrders/ReturnOrderShowComponent.vue'

export default [
    {
        path:'/admin/return-orders',
        component: ReturnOrderComponent,
        name: 'admin.return-order',
        redirect: {name: 'admin.return-order'},
        meta: {
            isFrontend:false,
            auth:true,
            permissionUrl: 'return-orders',
            breadcrumb:'return_orders'
        },
        children: [
            {
                path:'',
                component: ReturnOrderListComponent,
                name: 'admin.return-order.list',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'return-orders',
                    breadcrumb: ''
                }

            },
            {
                path: 'create',
                component: ReturnOrderCreateAndEditComponent,
                name: 'admin.return-order.create',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'return_order_create',
                    breadcrumb: 'create'
                }
            },
            {
                path: 'show/:id',
                component: ReturnOrderShowComponent,
                name: 'admin.return-order.show',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'return_order_show',
                    breadcrumb: 'view'
                }
            },
            {
                path: 'edit/:id',
                component: ReturnOrderCreateAndEditComponent,
                name: 'admin.return-order.edit',
                meta: {
                    isFrontend:false,
                    auth:true,
                    permissionUrl: 'return_order_edit',
                    breadcrumb: 'edit'
                }
            }
        ]
    }
]