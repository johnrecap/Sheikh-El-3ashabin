import _ from "lodash";
import orderTypeEnum from "../../../enums/modules/orderTypeEnum";
import AskEnum from "../../../enums/modules/askEnum";
import appService from "../../../services/appService";


export const frontendCart = {
    namespaced: true,
    state: {
        lists: [],
        subtotal: 0,
        total: 0,
        coupon: {},
        discount: 0,
        orderType: null,
        deliveryZone: {},
        deliveryAddress: {},
        outletAddress: {},
        paymentMethod: {},
        totalTax: 0,
        deliveryCharge: 0,
        isList: false,
        images: [],
        // Guest checkout fields
        guestInfo: {
            name: '',
            phone: ''
        },
        guestAddress: {
            governorate: '',
            city: '',
            street: '',
            building_number: '',
            apartment: ''
        }
    },
    getters: {
        lists: function (state) {
            return state.lists;
        },
        subtotal: function (state) {
            return state.subtotal;
        },
        coupon: function (state) {
            return state.coupon;
        },
        discount: function (state) {
            return state.discount;
        },
        total: function (state) {
            return state.total;
        },
        orderType: function (state) {
            return state.orderType;
        },
        deliveryZone: function (state) {
            return state.deliveryZone;
        },
        deliveryAddress: function (state) {
            return state.deliveryAddress;
        },
        outletAddress: function (state) {
            return state.outletAddress;
        },
        images: function (state) {
            return state.images;
        },
        paymentMethod: function (state) {
            return state.paymentMethod;
        },
        totalTax: function (state) {
            return state.totalTax;
        },
        deliveryCharge: function (state) {
            return state.deliveryCharge;
        },
        isList: function (state) {
            return state.isList;
        },
        guestInfo: function (state) {
            return state.guestInfo;
        },
        guestAddress: function (state) {
            return state.guestAddress;
        }
    },
    actions: {
        listChecker: function (context) {
            return new Promise((resolve, reject) => {
                if (context.state.lists.length > 0) {
                    context.commit('isList', true);
                    resolve({ status: true });
                } else {
                    context.commit('isList', false);
                    resolve({ status: false });
                }
                reject({
                    message: "no data found",
                    status: false
                });
            });
        },
        lists: function (context, payload) {
            return new Promise((resolve, reject) => {
                if (Object.keys(payload).length > 0) {
                    let isNew = false;
                    let productMatch = false;
                    if (context.state.lists.length === 0) {
                        isNew = true;
                    } else {
                        _.forEach(context.state.lists, (list, listKey) => {
                            if (list.product_id === payload.product_id && list.variation_id === payload.variation_id) {
                                productMatch = true;
                                if ((payload.quantity + list.quantity) <= list.stock) {
                                    if ((payload.quantity + list.quantity) <= list.maximum_purchase_quantity) {
                                        context.state.lists[listKey].quantity += payload.quantity;
                                    } else {
                                        reject({
                                            message: "maximum_quantity",
                                            status: false
                                        });
                                    }
                                } else {
                                    reject({
                                        message: "stock_out",
                                        status: false
                                    });
                                }
                            }
                        });

                        if (!productMatch) {
                            isNew = true;
                        }
                        productMatch = false;
                    }

                    if (isNew) {
                        context.state.lists.push({
                            name: payload.name,
                            product_id: payload.product_id,
                            image: payload.image,
                            variation_names: payload.variation_names,
                            variation_id: payload.variation_id,
                            sku: payload.sku,
                            stock: payload.stock,
                            taxes: payload.taxes,
                            shipping: payload.shipping,
                            quantity: payload.quantity,
                            discount: payload.discount,
                            price: payload.price,
                            old_price: payload.old_price,
                            total_tax: 0,
                            subtotal: 0,
                            total: 0,
                            total_price: payload.total_price,
                            maximum_purchase_quantity: payload.maximum_purchase_quantity,
                            file_attachment: payload.file_attachment
                        });
                        isNew = false;
                    }
                }
                context.commit("taxCalculation");
                context.commit("deliveryCharge", {
                    setting: context.rootState.frontendSetting.lists,
                    area: context.rootState.frontendOrderArea.lists
                });
                context.commit("subtotal");
                context.dispatch('listChecker').then().catch();
                resolve({ data: context.state.lists, status: true });
            });
        },
        quantity: function (context, payload) {
            context.commit("quantity", payload);
            context.commit("taxCalculation");
            context.commit("subtotal");
        },
        remove: function (context, payload) {
            context.commit("remove", payload);
            context.commit("taxCalculation");
            context.commit("subtotal");
            context.dispatch('listChecker').then().catch();
        },
        coupon: function (context, payload) {
            context.commit("coupon", payload);
            context.commit("subtotal");
        },
        destroyCoupon: function (context) {
            context.commit('coupon', {});
            context.commit("subtotal");
        },
        initOrderType: function (context, payload) {
            context.commit('orderTypeInit', payload);
            context.commit("deliveryCharge");
            context.commit("subtotal");
        },
        updateOrderType: function (context, payload) {
            context.commit('updateOrderType', payload);
            context.commit("deliveryCharge");
            context.commit("subtotal");
        },
        deliveryZone: function (context, payload) {
            context.commit('deliveryZone', payload);
            context.commit("deliveryCharge");
            context.commit("subtotal"); // Recalculate total with delivery charge
        },
        deliveryAddress: function (context, payload) {
            context.commit('deliveryAddress', payload);
            context.commit("deliveryCharge");
            context.commit("subtotal");
        },
        outletAddress: function (context, payload) {
            context.commit('outletAddress', payload);
            context.commit("subtotal");
        },
        images: function (context, payload) {
            context.commit('images', payload);
        },
        paymentMethod: function (context, payload) {
            context.commit('paymentMethod', payload);
        },
        setGuestInfo: function (context, payload) {
            context.commit('setGuestInfo', payload);
        },
        setGuestAddress: function (context, payload) {
            context.commit('setGuestAddress', payload);
        },
        resetCart: function (context) {
            context.commit('resetCart');
        },
    },
    mutations: {
        subtotal: function (state) {
            state.total = 0;
            if (state.lists.length > 0) {
                let subtotal = 0;
                let total = 0;
                _.forEach(state.lists, (list, listKey) => {
                    state.lists[listKey].subtotal = state.lists[listKey].price * state.lists[listKey].quantity;
                    state.lists[listKey].total = ((state.lists[listKey].price * state.lists[listKey].quantity) + state.lists[listKey].total_tax) - state.lists[listKey].discount;
                    subtotal += state.lists[listKey].subtotal;
                    total += state.lists[listKey].total;
                });
                state.subtotal = subtotal;
                state.total = total;
            } else {
                state.subtotal = 0;
                state.total = 0;
            }

            if (state.deliveryCharge > 0) {
                state.total += state.deliveryCharge;
            }

            if (Object.keys(state.coupon).length > 0) {
                state.total -= state.coupon.convert_discount;
            }
        },
        quantity: function (state, payload) {
            if (payload.status === "increment") {
                state.lists[payload.id].quantity++;
            } else if (payload.status === "decrement") {
                if (state.lists[payload.id].quantity !== 1) {
                    state.lists[payload.id].quantity--;
                }
            } else {
                state.lists[payload.id].quantity = payload.status;
            }

            state.lists[payload.id].total_price = state.lists[payload.id].price * state.lists[payload.id].quantity;
        },
        remove: function (state, payload) {
            state.lists.splice(payload.id, 1);
        },
        coupon: function (state, payload) {
            state.coupon = payload;
            if (Object.keys(payload).length > 0) {
                state.discount = payload.convert_discount;
            } else {
                state.discount = 0;
            }
        },
        orderTypeInit: function (state, payload) {
            if (state.orderType === null) {
                state.orderType = payload.order_type;
            }
        },
        updateOrderType: function (state, payload) {
            if (orderTypeEnum.DELIVERY === payload || orderTypeEnum.PICK_UP === payload) {
                state.orderType = payload;
            } else {
                state.orderType = null;
            }
        },
        deliveryZone: function (state, payload) {
            state.deliveryZone = payload;
        },
        deliveryAddress: function (state, payload) {
            state.deliveryAddress = payload;
        },
        outletAddress: function (state, payload) {
            state.outletAddress = payload;
        },
        images: function (state, payload) {
            state.images = payload;
        },
        paymentMethod: function (state, payload) {
            state.paymentMethod = payload;
        },
        taxCalculation: function (state) {
            let stateTotalTax = 0;
            _.forEach(state.lists, (list, listKey) => {
                if (list.taxes.length > 0) {
                    let taxes = [];
                    let total_tax = 0;
                    _.forEach(list.taxes, (tax, taxKey) => {
                        if (tax.tax_rate > 0) {
                            let taxPercentagePrice = ((list.price / 100) * parseFloat(tax.tax_rate));
                            total_tax += taxPercentagePrice;
                            taxes.push({
                                id: tax.id,
                                name: tax.name,
                                code: tax.code,
                                tax_rate: parseFloat(tax.tax_rate),
                                tax_amount: parseFloat(taxPercentagePrice)
                            })
                        }
                    });
                    state.lists[listKey].taxes = taxes;
                    state.lists[listKey].total_tax = (total_tax * state.lists[listKey].quantity);
                }
                stateTotalTax += state.lists[listKey].total_tax;
            });
            state.totalTax = stateTotalTax;
        },
        deliveryCharge: function (state, payload) {
            if (state.orderType === orderTypeEnum.DELIVERY) {
                if (Object.keys(state.deliveryZone).length > 0) {
                    // Use flat delivery charge from zone (no distance calculation)
                    state.deliveryCharge = parseFloat(state.deliveryZone.delivery_fee) || parseFloat(state.deliveryZone.delivery_charge_per_kilo) || 0;
                } else {
                    state.deliveryCharge = 0;
                }
            } else {
                state.deliveryCharge = 0;
            }
        },
        isList: function (state, payload) {
            state.isList = payload;
        },
        setGuestInfo: function (state, payload) {
            state.guestInfo = payload;
        },
        setGuestAddress: function (state, payload) {
            state.guestAddress = payload;
        },
        resetCart: function (state) {
            state.lists = [];
            state.images = [];
            state.subtotal = 0;
            state.total = 0;
            state.coupon = {};
            state.discount = 0;
            state.deliveryZone = {};
            state.deliveryAddress = {};
            state.outletAddress = {};
            state.paymentMethod = {};
            state.totalTax = 0;
            state.deliveryCharge = 0;
            // Reset guest info
            state.guestInfo = { name: '', phone: '' };
            state.guestAddress = { governorate: '', city: '', street: '', building_number: '', apartment: '' };
        }
    },
};
