<template>

<div v-if=" products.length > 0" v-for="product in products"
        class="sm:p-2 rounded-xl sm:rounded-2xl flex flex-col sm:border sm:border-gray-100 transition-all duration-300 sm:hover:shadow-hover group">
        <figure class="relative overflow-hidden rounded-xl flex-shrink-0 isolate">
            <img :src="product.cover" alt="product"
                class="w-full rounded-xl transition-all duration-300 group-hover:scale-105 group-hover:rotate-3">
        </figure>
        <div class="pt-4 pb-1 mobile:px-1.5 h-full flex flex-col gap-3 justify-between">
            <h3 class="capitalize text-xs sm:text-sm font-medium">
                {{ product.name }}
            </h3>
            <div>
                <p class="text-[10px] mobile:leading-none sm:text-xs capitalize mb-1">{{ product.unit }}</p>

                <h4 class="flex flex-wrap items-center gap-x-2 sm:gap-x-3" v-if="product.is_offer">
                    <span class="text-base sm:text-lg font-bold text-primary"> {{ product.discounted_price }} </span>
                    <del class="text-xs sm:text-sm font-semibold text-slate-400">{{
                        product.currency_price }}</del>
                </h4>

                <h4 class="flex flex-wrap items-center gap-x-2 sm:gap-x-3" v-else>
                    <span class="text-base sm:text-lg font-bold text-primary"> {{ product.currency_price }} </span>
                </h4>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "ProductListComponent",
    props: {
        "products": "object",
    },
    data() {
        return {
        }
    },
    methods: {
    }
}
</script>
