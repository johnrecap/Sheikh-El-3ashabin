<template>
    <LoadingContentComponent :props="loading" />
    <div class="col-12 lg:col-6">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="font-semibold text-lg capitalize text-heading">{{ $t("label.active_deliveries_map") }}</h3>
            </div>
            <div class="db-card-body relative">
                <div class="w-full rounded-2xl bg-white">
                    <div ref="theGoogleMap" id="active-delivery-google-map" class="w-full h-[385px] rounded-xl">
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { Loader } from "google-maps";
import LoadingContentComponent from "../../components/LoadingComponent.vue";
import _ from "lodash";
import ENV from '../../../../config/env';
import orderStatusEnum from "../../../../enums/modules/orderStatusEnum";

const options = { libraries: ["places", "geometry", "drawing"] };
const loader = new Loader(ENV.GOOGLE_MAP_KEY, options);

export default {
    name: "ActiveDeliveriesMapComponent",
    components: { LoadingContentComponent },
    props: {
        location: Object,
        position: Function,
        setting: {
            type: Object,
            default: {
                autocomplete: {
                    type: Boolean,
                    default: true,
                    required: false
                },
                mouseEvent: {
                    type: Boolean,
                    default: true,
                    required: false
                },
                currentLocation: {
                    type: Boolean,
                    default: true,
                    required: false
                }
            }
        }
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            currentLocation: {
                lat: null,
                lng: null,
            },
            address: null,
            props: {
                search: {
                    paginate: 0,
                    order_column: 'id',
                    order_by: "desc",
                    excepts: orderStatusEnum.DELIVERED + '|' + orderStatusEnum.RETURNED,
                }
            },
        }
    },
    computed: {
        orders: function () {
            return this.$store.getters['deliveryBoyDashboard/lists'];
        },
        frontendSetting: function () {
            return this.$store.getters['frontendSetting/lists']
        },
    },
    mounted: async function () {
        this.list();
    },
    methods: {
        list: function (page = 1) {
            this.loading.isActive = true;
            this.props.search.page = page;
            this.$store.dispatch('deliveryBoyDashboard/lists', this.props.search).then(res => {
                this.initMap();
                this.loading.isActive = false;
            }).catch((err) => {
                this.loading.isActive = false;
            });
        },
        initMap: async function () {
            const google = await loader.load();
            const mapCenter = this.orders.length > 0
                ? { lat: parseFloat(this.orders[0].order_address.latitude), lng: parseFloat(this.orders[0].order_address.longitude) }
                : { lat: parseFloat(this.frontendSetting.company_latitude), lng: parseFloat(this.frontendSetting.company_longitude) };
            this.map = new google.maps.Map(document.getElementById("active-delivery-google-map"), {
                center: mapCenter,
                zoom: 12,
            });

            this.orders.forEach(order => {
                this.createMarker(order.id, order.order_serial_no, order.order_address);
            });

        },

        createMarker: function (id, serialId, address) {
            const offsetDistance = 0.00005;
            const generateOffset = (index) => ({
                lat: parseFloat(address.latitude) + offsetDistance * (index % 2 === 0 ? 1 : -1) * Math.ceil(index / 2),
                lng: parseFloat(address.longitude) + offsetDistance * (index % 2 === 1 ? 1 : -1) * Math.ceil(index / 2),
            });

            const existingMarkers = this.orders.filter(order =>
                parseFloat(order.order_address.latitude) === parseFloat(address.latitude) &&
                parseFloat(order.order_address.longitude) === parseFloat(address.longitude)
            );

            const positionIndex = existingMarkers.findIndex(order => order.id === id);
            const markerPosition = generateOffset(positionIndex);


            const marker = new google.maps.Marker({
                position: markerPosition,
                map: this.map,
                icon: {
                    url: this.frontendSetting.custom_map_marker,
                    scaledSize: new google.maps.Size(30, 45),
                }
            });

            const navigateToOrder = this.navigateToOrder.bind(this);
            const infoWindow = new google.maps.InfoWindow({
                content: `<div class="pt-2">
                    <div class="flex items-center gap-1 mb-3">
                        <p class="text-base font-medium whitespace-nowrap">Order ID:</p>
                        <a href="#" class="text-base font-medium whitespace-nowrap text-[#008BBA]">#${serialId}</a>
                    </div>
                    <div class="flex items-end justify-between gap-4">
                        <div class="flex items-center gap-1">
                            <i class="lab-fill-map-2 text-paragraph flex-shrink-0"></i>
                            <span class="text-sm font-medium">
                                ${address.apartment ? address.apartment + ', ' : ''}${address.address}
                            </span>
                        </div>
                        <a href="#" onclick="navigateToOrder(${id})" class="flex items-center gap-0.5 text-[#01BE5F]">
                            <span class="text-xs whitespace-nowrap font-semibold">See Order Details</span>
                            <i class="lab-line-chevron-right"></i>
                        </a>
                    </div>
                </div>`,
            });

            marker.addListener("click", () => {
                infoWindow.open(this.map, marker);
            });

            window.navigateToOrder = navigateToOrder;
        },

        navigateToOrder: function (orderId) {
            this.$router.push({ name: 'admin.dashboard.activeOrder.show', params: { id: orderId } });
        }
    }
}
</script>

<style>
.gm-style-iw-chr {
    position: absolute !important;
    right: 0 !important;
}

.gm-ui-hover-effect>span {
    background-color: #01BE5F;
}

.gm-style-iw-ch {
    padding-top: 0 !important;
    display: none !important;
}
</style>