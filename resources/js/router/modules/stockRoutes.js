import StockComponent from "../../components/admin/stock/StockComponent.vue";
import StockListComponent from "../../components/admin/stock/StockListComponent.vue";

export default [
    {
        path: '/admin/stock',
        component: StockComponent,
        name: 'admin.stock',
        redirect: {name: 'admin.stock.list'},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'stock',
            breadcrumb: 'stock'
        },
        children: [
            {
                path: '',
                component: StockListComponent,
                name: 'admin.stock.list',
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: 'stock',
                    breadcrumb: ''
                },
            }
        ]
    }
]
