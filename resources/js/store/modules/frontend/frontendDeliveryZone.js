import axios from "axios";
import appService from "../../../services/appService";

export const frontendDeliveryZone = {
    namespaced: true,
    state: {
        selectDeliveryZone: {},
    },
    getters: {
        selectDeliveryZone: function (state) {
            return state.selectDeliveryZone;
        },
    },
    actions: {
        selectDeliveryZone: function (context, payload) {
            return new Promise((resolve, reject) => {
                let url = "frontend/check-delivery-zone";
                if (payload) {
                    url = url + appService.requestHandler(payload);
                }
                axios.get(url).then((res) => {
                    context.commit("selectDeliveryZone", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
        checkByGovernorate: function (context, governorate) {
            return new Promise((resolve, reject) => {
                let url = "frontend/check-delivery-zone?governorate=" + encodeURIComponent(governorate);
                axios.get(url).then((res) => {
                    context.commit("selectDeliveryZone", res.data.data);
                    resolve(res);
                }).catch((err) => {
                    reject(err);
                });
            });
        },
    },
    mutations: {
        selectDeliveryZone: function (state, payload) {
            state.selectDeliveryZone = payload;
        },
    },
};
