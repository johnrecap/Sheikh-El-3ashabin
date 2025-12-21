import SubscriberComponent from "../../components/admin/subscribers/SubscriberComponent.vue";
import SubscriberListComponent from "../../components/admin/subscribers/SubscriberListComponent.vue";

export default [
    {
        path: "/admin/subscribers",
        component: SubscriberComponent,
        name: "admin.subscribers",
        redirect: { name: "admin.subscribers.list" },
        meta: {
            isFrontend: false,
            auth: true,
            permissionUrl: "subscribers",
            breadcrumb: "subscribers",
        },
        children: [
            {
                path: "",
                component: SubscriberListComponent,
                name: "admin.subscribers.list",
                meta: {
                    isFrontend: false,
                    auth: true,
                    permissionUrl: "subscribers",
                    breadcrumb: "",
                },
            },
        ],
    },
];
