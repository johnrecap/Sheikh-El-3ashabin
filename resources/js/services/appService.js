import VueSimpleAlert from "vue3-simple-alert";
import askEnum from "../enums/modules/askEnum";
import currencyPositionEnum from "../enums/modules/currencyPositionEnum";
import orderStatusEnum from "../enums/modules/orderStatusEnum";
import purchasePaymentStatusEnum from "../enums/modules/purchasePaymentStatusEnum";
import purchaseStatusEnum from "../enums/modules/purchaseStatusEnum";
import returnStatusEnum from "../enums/modules/returnStatusEnum";
import statusEnum from "../enums/modules/statusEnum";
import taxTypeEnum from "../enums/modules/taxTypeEnum";
import store from "../store";

export default {
    phoneNumber: function (e) {
        let char = String.fromCharCode(e.keyCode);
        if (/^[+]?[0-9]*$/.test(char)) return true;
        else e.preventDefault();
    },

    onlyNumber: function (e) {
        let res =
            (e.charCode !== 8 && e.charCode === 0) ||
            (e.charCode >= 48 && e.charCode <= 57);
        if (res) return true;
        else e.preventDefault();
    },

    floatNumber: function (e) {
        let char = String.fromCharCode(e.keyCode);
        if (/^[.]?[0-9]*$/.test(char)) return true;
        else e.preventDefault();
    },

    currencyFormat(amount, decimal, currency, position) {
        if (position === currencyPositionEnum.LEFT) {
            return currency + parseFloat(amount).toFixed(decimal);
        } else {
            return parseFloat(amount).toFixed(decimal) + currency;
        }
    },

    floatFormat(amount, decimal = 2) {
        return parseFloat(amount).toFixed(decimal);
    },

    sideDrawerShow: function (id = "sideDrawer") {
        const drawerDivs = document?.querySelectorAll(".drawer");
        const drawerSets = document?.querySelectorAll("[data-drawer]");
        drawerSets?.forEach((drawerSet) => {
            const targetElm = document?.querySelector(
                drawerSet?.dataset?.drawer
            );
            drawerSets?.forEach((drawerBtn) =>
                drawerBtn?.classList?.remove("active")
            );
            drawerDivs?.forEach((drawerDiv) =>
                drawerDiv?.classList?.remove("active")
            );
            targetElm?.classList?.add("active");
            drawerSet?.classList?.add("active");
            document.body.style.overflowY = "hidden";
            document?.querySelector(".backdrop")?.classList?.add("active");
        });
    },
    sideDrawerHide: function (id = "sideDrawer") {
        const drawerDivs = document?.querySelectorAll(".drawer");
        const drawerSets = document?.querySelectorAll("[data-drawer]");
        document?.querySelectorAll("#sidebar")?.forEach((closeBtn) => {
            drawerSets?.forEach((drawerBtn) =>
                drawerBtn?.classList?.remove("active")
            );
            drawerDivs?.forEach((drawerDiv) =>
                drawerDiv?.classList?.remove("active")
            );
            document?.querySelector(".backdrop")?.classList?.remove("active");
            document.body.style.overflowY = "auto";
        });
    },

    modalShow: function (id = ".modal") {
        let modalButton = document?.querySelectorAll("[data-modal]");
        modalButton?.forEach((modalBtn) => {
            const modalTarget = document?.querySelector(id);
            modalTarget?.classList?.add("active");
            document.body.style.overflowY = "hidden";
        });
    },

    modalHide: function (id = ".modal") {
        let modalDivs = document?.querySelectorAll(id);
        document.body.style.overflowY = "auto";
        modalDivs?.forEach((modalDiv) => modalDiv?.classList?.remove("active"));
    },

    recursiveRouter: function (routes, permission) {
        if (!routes || !Array.isArray(routes) || routes.length === 0) {
            return;
        }

        if (
            !permission ||
            !Array.isArray(permission) ||
            permission.length === 0
        ) {
            return;
        }

        try {
            const routesToUpdate = [];

            const collectRoutes = (routeArray) => {
                if (!Array.isArray(routeArray)) return;

                routeArray.forEach((route) => {
                    if (route && typeof route === "object") {
                        if (
                            route.meta?.permissionUrl &&
                            typeof route.meta.permissionUrl === "string"
                        ) {
                            routesToUpdate.push(route);
                        }

                        if (
                            route.children &&
                            Array.isArray(route.children) &&
                            route.children.length > 0
                        ) {
                            collectRoutes(route.children);
                        }
                    }
                });
            };

            collectRoutes(routes);

            if (routesToUpdate.length === 0) {
                return;
            }

            const permissionMap = new Map();
            permission.forEach((perm) => {
                if (
                    perm &&
                    typeof perm === "object" &&
                    perm.url &&
                    typeof perm.url === "string"
                ) {
                    permissionMap.set(perm.url, perm);
                }
            });

            if (permissionMap.size === 0) {
                return;
            }

            routesToUpdate.forEach((route) => {
                try {
                    const perm = permissionMap.get(route.meta.permissionUrl);
                    if (perm) {
                        if (typeof perm.access !== "undefined") {
                            route.meta.access = perm.access;
                        }
                        if (typeof perm.title !== "undefined") {
                            route.meta.title = perm.title;
                        }
                    }
                } catch (routeError) {}
            });
        } catch (error) {}
    },

    textShortener: function (text, number = 30) {
        if (text) {
            if (!(text.length < number)) {
                return text.substring(0, number) + "..";
            }
        }
        return text;
    },
    htmlTagRemover: function (text) {
        if (text != null && text !== "" && isNaN(text)) {
            return text.replace(/(<([^>]+)>)/gi, "");
        }
        return text;
    },
    statusClass: function (status) {
        if (status === statusEnum.ACTIVE) {
            return "db-table-badge text-green-600 bg-green-100";
        } else {
            return "db-table-badge text-red-600 bg-red-100";
        }
    },

    askClass: function (ask) {
        if (ask === askEnum.YES) {
            return "db-table-badge text-green-600 bg-green-100";
        } else {
            return "db-table-badge text-red-600 bg-red-100";
        }
    },

    requestHandler: function (requests) {
        let i = 1;
        let what = "?";
        let response = "";

        for (let request in requests) {
            if (requests[request] !== "" && requests[request] !== null) {
                if (i !== 1) {
                    response += "&";
                }
                response += request + "=" + requests[request];
            }
            i++;
        }

        if (response) {
            response = what + response;
        }

        return response;
    },

    responsiveLoad: function () {
        let mainHeader = document?.querySelector(".db-header");
        let subHeader = document?.querySelector(".sub-header");
        let mainHeight = mainHeader?.scrollHeight;

        if (subHeader) {
            subHeader.style.top = `${mainHeight}px`;
        }
    },

    permissionChecker: function (permissionName) {
        let i,
            permissions = store.getters.authPermission;
        for (i = 0; i < permissions.length; i++) {
            if (
                typeof permissions[i].name !== "undefined" &&
                permissions[i].name
            ) {
                if (permissions[i].name === permissionName) {
                    return permissions[i].access;
                }
            }
        }
    },

    formDataShow: function (formData) {
        for (let pair of formData.entries()) {
            console.log(pair[0] + " : " + pair[1]);
        }
    },
    destroyConfirmation: function () {
        return new VueSimpleAlert.confirm(
            "You will not be able to recover the deleted record!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Delete it!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },

    taxTypeClass: function (type) {
        if (type === taxTypeEnum.FIXED) {
            return "db-table-badge text-blue-500 bg-blue-100";
        } else {
            return "db-table-badge text-orange-500 bg-orange-100";
        }
    },
    underscoreToSpace: function (str) {
        return str.replace(/_/g, " ");
    },
    decimalPoint: function (num, length = 2) {
        return Number.parseFloat(num).toFixed(length);
    },
    orderStatusClass: function (status) {
        if (status === orderStatusEnum.PENDING) {
            return "bg-amber-100 text-amber-500";
        } else if (status === orderStatusEnum.CONFIRMED) {
            return "bg-indigo-100 text-indigo-500";
        } else if (status === orderStatusEnum.ON_THE_WAY) {
            return "bg-cyan-100 text-cyan-500";
        } else if (status === orderStatusEnum.DELIVERED) {
            return "bg-green-100 text-green-500";
        } else if (status === orderStatusEnum.CANCELED) {
            return "bg-red-100 text-red-500";
        } else {
            return "bg-red-100 text-red-500";
        }
    },

    purchasePaymentStatusClass: function (status) {
        if (status === purchasePaymentStatusEnum.PENDING) {
            return "bg-amber-100 text-amber-500";
        } else if (status === purchasePaymentStatusEnum.PARTIAL_PAID) {
            return "bg-indigo-100 text-indigo-500";
        } else if (status === purchasePaymentStatusEnum.FULLY_PAID) {
            return "bg-green-100 text-green-500";
        }
    },
    purchaseStatusClass: function (status) {
        if (status === purchaseStatusEnum.PENDING) {
            return "bg-amber-100 text-amber-500";
        } else if (status === purchaseStatusEnum.ORDERED) {
            return "bg-indigo-100 text-indigo-500";
        } else if (status === purchaseStatusEnum.RECEIVED) {
            return "bg-green-100 text-green-500";
        }
    },

    returnStatusClass: function (status) {
        if (status == returnStatusEnum.PENDING) {
            return "text-[#F6A609]";
        } else if (status == returnStatusEnum.ACCEPT) {
            return "text-[#2AC769]";
        } else {
            return "text-[#FB4E4E]";
        }
    },

    cancelOrder: function () {
        return new VueSimpleAlert.confirm(
            "You want to cancel your order?",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Cancel it!",
                cancelButtonText: "No, Cancel",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },

    acceptOrder: function () {
        return new VueSimpleAlert.confirm(
            "You will not be able to cancel the order!",
            "Are you sure?",
            "warning",
            {
                confirmButtonText: "Yes, Accept it!",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },
    confirmOrder: function (amount) {
        return new VueSimpleAlert.confirm(
            "Amount: " + amount,
            "Do You Collect Payment?",
            "warning",
            {
                confirmButtonText: "Yes, Collect",
                cancelButtonText: "No, Cancel!",
                confirmButtonColor: "#696cff",
                cancelButtonColor: "#8592a3",
            }
        );
    },
    distance: function (lat1, lng1, lat2, lng2) {
        let radiationLat1 = (Math.PI * lat1) / 180;
        let radiationLat2 = (Math.PI * lat2) / 180;
        let theta = lng1 - lng2;
        let radiationTheta = (Math.PI * theta) / 180;
        let distance =
            Math.sin(radiationLat1) * Math.sin(radiationLat2) +
            Math.cos(radiationLat1) *
                Math.cos(radiationLat2) *
                Math.cos(radiationTheta);
        distance = Math.acos(distance);
        distance = (distance * 180) / Math.PI;
        distance = distance * 60 * 1.1515;
        distance = distance * 1.609344;
        return distance;
    },
};
