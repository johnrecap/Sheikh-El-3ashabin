<template>
    <aside :class="!sidebar ? 'max-lg:!translate-x-0 ltr:-translate-x-full rtl:translate-x-full' : ''"
        class="flex-shrink-0 w-64 h-screen lg:h-[calc(100vh_-_68px)] fixed top-0 lg:top-16 ltr:left-0 rtl:right-0 z-30 thin-scrolling bg-white ltr:max-lg:-translate-x-full rtl:max-lg:translate-x-full ltr:max-lg:shadow-db-sidebar-right rtl:max-lg:shadow-db-sidebar-left">

        <nav class="db-sidebar-nav">
            <div class="db-sidebar-header">
                <router-link class="w-24" :to="{ name: 'frontend.home' }">
                    <img class="h-8 w-auto max-w-full object-contain" :src="setting.theme_logo" alt="logo">
                </router-link>
                <button @click.prevent="closeSidebar" class="lab-line-circle-cross text-lg text-danger"></button>
            </div>
            <ul class="db-sidebar-nav-list" v-if="menus.length > 0" v-for="menu in menus" :key="menu">
                <li class="db-sidebar-nav-item" v-if="menu.url === '#'">
                    <a href="javascript:void(0);" class="db-sidebar-nav-title">
                        {{ $t('menu.' + menu.language) }}
                    </a>
                </li>

                <li class="db-sidebar-nav-item" v-else>
                    <router-link :to="'/admin/' + menu.url" class="db-sidebar-nav-menu">
                        <i class="text-sm" :class="menu.icon"></i>
                        <span class="text-base flex-auto">{{ $t('menu.' + menu.language) }}</span>
                    </router-link>
                </li>

                <li class="db-sidebar-nav-item" v-if="menu.children" v-for="children in menu.children">
                    <router-link :to="'/admin/' + children.url" class="db-sidebar-nav-menu">
                        <i class="text-sm" :class="children.icon"></i>
                        <span class="text-base flex-auto">{{ $t('menu.' + children.language) }}</span>
                    </router-link>
                </li>
            </ul>
        </nav>
    </aside>
</template>

<script>
export default {
    name: "BackendMenuComponent",
    data: function () {
        return {
            activeParentId: 1,
            activeChildId: 0
        }
    },
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        },
        menus: function () {
            return this.$store.getters.authMenu;
        },
        sidebar() {
            return this.$store.getters['globalState/lists'].topSidebar;
        },
    },
    methods: {
        closeSidebar: function () {
            this.$store.dispatch("globalState/set", { topSidebar: !this.$store.getters['globalState/lists'].topSidebar });
        },
    }
}
</script>