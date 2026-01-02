import ProductsReportComponent from "../../components/admin/productsReport/ProductsReportComponent.vue";
import ProductsReportListComponent from "../../components/admin/productsReport/ProductsReportListComponent.vue";
export default [
    {
        path: "/admin/products-report",
        component: ProductsReportComponent,
        name: "admin.products-report",
        redirect: { name: "admin.products-report.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "products-report",
            breadcrumb: "products_report",
        },
        children: [
            {
                path: "",
                component: ProductsReportListComponent,
                name: "admin.products-report.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "products-report",
                    breadcrumb: "",
                },
            },
        ],
    },
];
