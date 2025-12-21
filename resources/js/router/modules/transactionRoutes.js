import TransactionListComponent from "../../components/admin/transactions/TransactionListComponent.vue";

export default [
    {
        path: '/admin/transactions',
        component: TransactionListComponent,
        name: 'admin.transactions.list',
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: 'transactions',
            breadcrumb: 'transactions'
        }
    }
]
