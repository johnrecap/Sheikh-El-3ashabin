import AdministratorComponent from "../../components/admin/administrators/AdministratorComponent.vue";
import AdministratorListComponent from "../../components/admin/administrators/AdministratorListComponent.vue";
import AdministratorShowComponent from "../../components/admin/administrators/AdministratorShowComponent.vue";
import AdministratorOrderDetailsComponent
    from "../../components/admin/administrators/AdministratorOrderDetailsComponent.vue";

export default [
    {
        path: "/admin/administrators",
        component: AdministratorComponent,
        name: "admin.administrators",
        redirect: {name: "admin.administrators.list"},
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "administrators",
            breadcrumb: "administrators",
        },
        children: [
            {
                path: "",
                component: AdministratorListComponent,
                name: "admin.administrators.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "administrators",
                    breadcrumb: "",
                }
            },
            {
                path: "show/:id",
                component: AdministratorShowComponent,
                name: "admin.administrators.show",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "administrators",
                    breadcrumb: "view",
                }
            },
            {
                path: "show/:id/:orderId",
                component: AdministratorOrderDetailsComponent,
                name: "admin.administrators.order.details",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "administrators",
                    breadcrumb: "order_details",
                },
            },
        ],
    },
];
