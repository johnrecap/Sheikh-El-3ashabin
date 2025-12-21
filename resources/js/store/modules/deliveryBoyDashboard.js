import axios from "axios";
import appService from "../../services/appService";

export const deliveryBoyDashboard = {
    namespaced: true,
    state: {
        orderOverview: [],
        lists: [],
        mapOrders: [],
        page: {},
        pagination: [],
        show: {},
        orderProducts: {},
        orderUser: {},
        orderAddress: {},
        orderStatistics: [],
        orderSummary: [],
        nextDeliveryOrder: {},
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        mapOrders: function (state) {
            return state.mapOrders;
        },
        pagination: function (state) {
            return state.pagination;
        },
        page: function (state) {
            return state.page;
        },
        show: function (state) {
            return state.show;
        },
        orderProducts: function (state) {
            return state.orderProducts;
        },
        orderUser: function (state) {
            return state.orderUser;
        },
        orderAddress: function (state) {
            return state.orderAddress;
        },
        orderStatistics: function (state) {
            return state.orderStatistics;
        },
        orderOverview: function (state) {
            return state.orderOverview;
        },
        orderSummary: function (state) {
            return state.orderSummary;
        },
        nextDeliveryOrder: function (state) {
            return state.nextDeliveryOrder;
        },

    },
    actions: {
        orderOverview: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/delivery-boy/dashboard/order-overview";
                axios.get(url).then((res) => {
                    context.commit("orderOverview", res.data.data);
                    resolve(res);
                })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/delivery-boy/dashboard/active-order";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("lists", res.data.data);
                        context.commit("page", res.data.meta);
                        context.commit("pagination", res.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        mapOrders: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/delivery-boy/dashboard/active-order";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("mapOrders", res.data.data);
                    }
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        show: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/delivery-boy/dashboard/active-order/show/${payload}`).then((res) => {
                    context.commit("show", res.data.data);
                    context.commit("orderProducts", res.data.data.order_products);
                    context.commit("orderUser", res.data.data.user);
                    context.commit("orderAddress", res.data.data.order_address);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        changeStatus: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.post(`admin/delivery-boy/dashboard/active-order/change-status/${payload.id}`).then((res) => {
                    context.commit('show', res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        orderStatistics: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/delivery-boy/dashboard/order-statistics";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    context.commit("orderStatistics", res.data.data);
                    resolve(res);
                })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        orderSummary: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/delivery-boy/dashboard/order-summary";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    context.commit("orderSummary", res.data.data);
                    resolve(res);
                })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },
        nextDeliveryOrder: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get('admin/delivery-boy/dashboard/active-order/next-delivery-order').then((res) => {
                    context.commit("nextDeliveryOrder", res.data.data);
                    context.commit("orderProducts", res.data.data.order_products);
                    context.commit("orderUser", res.data.data.user);
                    context.commit("orderAddress", res.data.data.order_address);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        lists: function (state, payload) {
            state.lists = payload;
        },
        mapOrders: function (state, payload) {
            state.mapOrders = payload;
        },
        pagination: function (state, payload) {
            state.pagination = payload;
        },
        page: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.page = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total,
                };
            }
        },
        show: function (state, payload) {
            state.show = payload;
        },
        orderProducts: function (state, payload) {
            state.orderProducts = payload;
        },
        orderUser: function (state, payload) {
            state.orderUser = payload;
        },
        orderAddress: function (state, payload) {
            state.orderAddress = payload;
        },
        orderStatistics: function (state, payload) {
            state.orderStatistics = payload;
        },
        orderOverview: function (state, payload) {
            state.orderOverview = payload;
        },
        orderSummary: function (state, payload) {
            state.orderSummary = payload;
        },
        nextDeliveryOrder: function (state, payload) {
            state.nextDeliveryOrder = payload;
        },
    },
};
