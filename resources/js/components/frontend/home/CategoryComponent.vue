<template>
    <LoadingComponent :props="loading" />
    <section class="sm:mb-10">
        <div class="container">
            <h2 class="text-xl sm:text-4xl font-bold -mb-6 sm:-mb-10">
                {{ $t('label.browse_by_categories') }}
            </h2>
            <Swiper dir="ltr" :speed="1000" :loop="true" :navigation="true" :modules="modules" class="navigate-swiper"
                :breakpoints="breakpoints">
                <SwiperSlide v-for="category in categories" class="mobile:!w-20">
                    <router-link :to="{ name: 'frontend.product', query: { category: category.slug } }"
                        class="block w-full px-2 sm:px-4 py-3 sm:py-6 rounded-lg sm:rounded-2xl text-center group bg-gray-100 hover:bg-white hover:shadow-hover transition-all duration-300">
                        <img :src="category.thumb" alt="category"
                            class="h-8 sm:h-14 mx-auto mb-3 sm:mb-8 group-hover:rotate-6 transition-all duration-300">
                        <span
                            class="block text-xs sm:text-lg font-semibold capitalize text-center whitespace-nowrap overflow-hidden text-ellipsis  group-hover:text-primary transition-all duration-300">
                            {{ category.name }}
                        </span>
                    </router-link>
                </SwiperSlide>
            </Swiper>
        </div>
    </section>
</template>

<script>

import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import statusEnum from "../../../enums/modules/statusEnum";
import LoadingComponent from "../components/LoadingComponent.vue";
import displayModeEnum from '../../../enums/modules/displayModeEnum';


export default {
    name: "CategoryComponent",
    components: {
        Swiper,
        SwiperSlide,
        LoadingComponent
    },
    setup() {
        return {
            modules: [Navigation, Pagination, Autoplay],
        }
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            settings: {
                itemsToShow: 6,
                wrapAround: false,
                snapAlign: "start"
            },
            breakpoints: {
                0: { slidesPerView: 'auto', spaceBetween: 12 },
                640: { slidesPerView: 4, spaceBetween: 24 },
                768: { slidesPerView: 5, spaceBetween: 24 },
                1024: { slidesPerView: 6, spaceBetween: 24 }
            },
        }
    },
    computed: {
        categories: function () {
            return this.$store.getters["frontendProductCategory/lists"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendProductCategory/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            parent_id: null,
            status: statusEnum.ACTIVE,
        }).then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });
    },
}
</script>