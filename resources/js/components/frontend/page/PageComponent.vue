<template>
    <section class="mb-10 sm:mb-20">
        <div class="container">
            <div class="mb-6">
                <h2 class="text-[26px] leading-10 font-semibold capitalize mb-2">
                    {{ page.title }}
                </h2>
                <div v-if="page.image" class="w-full mb-6">
                    <img :src="page.image" alt="image">
                </div>

                <div v-html="page.description" class="text-description pageHeading"> </div>

            </div>
            <TemplateManagerComponent :menuTemplateId="page.menu_template_id" />
        </div>
    </section>
</template>

<script>
import TemplateManagerComponent from "../components/TemplateManagerComponent.vue";

export default {
    name: "PageComponent",
    components: { TemplateManagerComponent },
    computed: {
        page: function () {
            return this.$store.getters['frontendPage/show'];
        }
    },
    mounted() {
        this.pageSetup();
    },
    methods: {
        pageSetup: function () {
            if (Object.keys(this.$route.params).length > 0 && typeof this.$route.params.slug === 'string') {
                this.$store.dispatch('frontendPage/show', this.$route.params.slug).then().catch()
            }
        }
    },
    watch: {
        $route() {
            this.pageSetup();
        }
    }
}
</script>

<style>
.pageHeading h1,
.pageHeading h2,
.pageHeading h3,
.pageHeading h4,
.pageHeading h5,
.pageHeading h6 {
    font-size: revert;
    font-weight: revert;
}
</style>