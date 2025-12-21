import axios from "axios";
import appService from "../../services/appService";

export const deliveryBoyOrder = {
    namespaced: true,
    state: {
        show: {},
        temp: {
            temp_id: null,
            isEditing: false,
        },
        deliveredOrders: [],
        deliveredOrderPage: {},
        deliveredOrderPagination: [],

        deliveredOrderDetails: {},
        orderProducts: {},
        outletAddress: {},
        orderUser: {},
        orderAddress: {},
        orderSummary: [],
    },
    getters: {
        show: function (state) {
            return state.show;
        },
        temp: function (state) {
            return state.temp;
        },
        deliveredOrders: function (state) {
            return state.deliveredOrders;
        },
        deliveredOrderPagination: function (state) {
            return state.deliveredOrderPagination;
        },
        deliveredOrderPage: function (state) {
            return state.deliveredOrderPage;
        },

        deliveredOrderDetails: function (state) {
            return state.deliveredOrderDetails;
        },
        orderProducts: function (state) {
            return state.orderProducts;
        },
        outletAddress: function (state) {
            return state.outletAddress;
        },
        orderUser: function (state) {
            return state.orderUser;
        },
        orderAddress: function (state) {
            return state.orderAddress;
        },
        orderSummary: function (state) {
            return state.orderSummary;
        },
    },
    actions: {
        deliveredOrders: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = `admin/delivery-boy/delivered-order/${payload.id}`;
                if (payload.search) {
                    url = url + appService.requestHandler(payload.search);
                }
                axios.get(url).then((res) => {
                    if (typeof payload.vuex === "undefined" || payload.vuex === true) {
                        context.commit("deliveredOrders", res.data.data);
                        context.commit("deliveredOrderPage", res.data.meta);
                        context.commit("deliveredOrderPagination", res.data);
                    }
                    resolve(res);
                })
                    .catch((err) => {
                        reject(err);
                    });
            });
        },

        deliveredOrderDetails: function (context, payload) {
            return new Promise((resolve, reject) => {
                axios.get(`admin/delivery-boy/delivered-order/show/${payload.id}/${payload.orderId}`).then((res) => {
                    context.commit("deliveredOrderDetails", res.data.data);
                    context.commit("orderProducts", res.data.data.order_products);
                    context.commit("orderUser", res.data.data.user);
                    context.commit("outletAddress", res.data.data.branch);
                    context.commit("orderAddress", res.data.data.order_address);

                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        orderSummary: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "admin/delivery-boy/order-summary";
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
    },
    mutations: {
        show: function (state, payload) {
            state.show = payload;
        },
        temp: function (state, payload) {
            state.temp.temp_id = payload;
            state.temp.isEditing = true;
        },
        reset: function (state) {
            state.temp.temp_id = null;
            state.temp.isEditing = false;
        },
        deliveredOrders: function (state, payload) {
            state.deliveredOrders = payload;
        },
        deliveredOrderPagination: function (state, payload) {
            state.deliveredOrderPagination = payload;
        },
        deliveredOrderPage: function (state, payload) {
            if (typeof payload !== "undefined" && payload !== null) {
                state.deliveredOrderPage = {
                    from: payload.from,
                    to: payload.to,
                    total: payload.total,
                };
            }
        },
        deliveredOrderDetails: function (state, payload) {
            state.deliveredOrderDetails = payload;
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
        outletAddress: function (state, payload) {
            state.outletAddress = payload;
        },
        orderSummary: function (state, payload) {
            state.orderSummary = payload;
        },
    },
};
