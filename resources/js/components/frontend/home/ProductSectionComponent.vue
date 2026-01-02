<template>
    <LoadingComponent :props="loading"/>

    <div class="p-0 m-0" v-if="productSections.length > 0 && promotions.length > 0" v-for="(productSection, key) in productSections">

        <div v-for="(promotion, promotionKey) in promotions" class="p-0 m-0">
            <section v-if="key === promotionKey" class="mb-10 sm:mb-20">
                <div class="container">
                    <router-link :to="{name: 'frontend.promotion.products', params: { slug: promotion.slug }}" class="w-full rounded-3xl">
                        <img class="w-full rounded-3xl" :src="promotion.preview" alt="promotion" >
                    </router-link>
                </div>
            </section>
        </div>

        <ProductListComponent v-if="productSection.products.length > 0" :products="productSection.products"
            :productSection="productSection.name" :isSectional="true"/>


    </div>

    <div class="p-0 m-0" v-else-if="promotions.length > 0">
        <section v-for="promotion in promotions" class="mb-10 sm:mb-20">
            <div class="container">
                <router-link :to="{name: 'frontend.promotion.products', params: { slug: promotion.slug }}" class="w-full rounded-3xl">
                    <img class="w-full rounded-3xl" :src="promotion.preview" alt="promotion">
                </router-link>
            </div>
        </section>
    </div>

    <div class="p-0 m-0" v-else-if="productSections.length > 0" v-for="productSection in productSections">
        <ProductListComponent v-if="productSection.products.length > 0" :products="productSection.products"
            :productSection="productSection.name" :isSectional="true"/>
    </div>
</template>

<script>
import LoadingComponent from "../components/LoadingComponent.vue";
import promotionTypeEnum from "../../../enums/modules/promotionTypeEnum";
import statusEnum from "../../../enums/modules/statusEnum";
import ProductListComponent from "../components/ProductListComponent.vue";

export default {
    name: "ProductSectionComponent",
    components: {
        ProductListComponent,
        LoadingComponent
    },
    data() {
        return {
            loading: {
                isActive: false,
            },
            promotions: []
        }
    },
    computed: {
        productSections: function () {
            return this.$store.getters["frontendProductSection/lists"];
        },
    },
    mounted() {
        this.loading.isActive = true;
        this.$store.dispatch("frontendProductSection/lists").then(res => {
            this.loading.isActive = false;
        }).catch((err) => {
            this.loading.isActive = false;
        });

        this.$store.dispatch("frontendPromotion/lists", {
            paginate: 0,
            order_column: "id",
            order_type: "asc",
            type: promotionTypeEnum.BIG,
            status: statusEnum.ACTIVE,
            vuex: false
        }).then(res => {
            this.promotions       = res.data.data;
        }).catch((err) => {});
    }
}
</script>
