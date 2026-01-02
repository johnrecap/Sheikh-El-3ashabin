import CreditBalanceReportComponent from "../../components/admin/creditBalanceReport/CreditBalanceReportComponent.vue";

export default [
    {
        path: "/admin/credit-balance-report",
        component: CreditBalanceReportComponent,
        name: "admin.credit-balance-report",
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "credit-balance-report",
            breadcrumb: "credit_balance_report",
        },
    },
];
