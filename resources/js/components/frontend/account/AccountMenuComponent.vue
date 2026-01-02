<template>
    <div class="py-6 lg:rounded-2xl lg:shadow-card bg-white">
        <div class="flex flex-col items-center justify-center mb-5">
            <RouterLink to="#" class="w-20 h-20 mb-3 rounded-full border border-primary">
                <img :src="profile?.image" alt="avatar"
                    class=" w-full h-full object-cover rounded-full border-2 border-white">
            </RouterLink>
            <h3 class="capitalize text-lg font-semibold text-center mb-0.5">{{ profile.name }}</h3>
            <p class="text-center text-text">{{ profile.phone ? profile.country_code + profile.phone : '' }}</p>
        </div>
        <nav class="flex flex-col">
            <router-link
                class="profile-link font-medium flex items-center gap-4 capitalize py-3 px-4 group hover:text-primary transition-all duration-500"
                :to="{ name: 'frontend.account.overview' }">
                <i
                    class="lab lab-fill-dashboard text-[#A0A3BD] group-hover:text-primary transition-all duration-500"></i>
                <span>{{ $t('menu.overview') }}</span>
            </router-link>

            <router-link
                class="profile-link font-medium flex items-center gap-4 capitalize py-3 px-4 group hover:text-primary transition-all duration-500"
                :to="{ name: 'frontend.account.orderHistory' }">
                <i class="lab lab-fill-bag text-[#A0A3BD] group-hover:text-primary transition-all duration-500"></i>
                <span>{{ $t('menu.order_history') }}</span>
            </router-link>

            <router-link
                class="profile-link font-medium flex items-center gap-4 capitalize py-3 px-4 group hover:text-primary transition-all duration-500"
                :to="{ name: 'frontend.account.accountInfo' }">
                <i class="lab lab-fill-user text-[#A0A3BD] group-hover:text-primary transition-all duration-500"></i>
                <span>{{ $t('menu.account_info') }}</span>
            </router-link>

            <router-link
                class="profile-link font-medium flex items-center gap-4 capitalize py-3 px-4 group hover:text-primary transition-all duration-500"
                :to="{ name: 'frontend.account.changePassword' }">
                <i class="lab lab-fill-key text-[#A0A3BD] group-hover:text-primary transition-all duration-500"></i>
                <span>{{ $t('menu.change_password') }}</span>
            </router-link>

            <router-link
                class="profile-link font-medium flex items-center gap-4 capitalize py-3 px-4 group hover:text-primary transition-all duration-500"
                :to="{ name: 'frontend.account.address' }">
                <i
                    class="lab lab-fill-location text-[#A0A3BD] group-hover:text-primary transition-all duration-500"></i>
                <span>{{ $t('menu.address') }}</span>
            </router-link>

            <button @click.prevent="logout()"
                class="flex items-center gap-3 px-4 py-2 transition-all duration-500 hover:bg-gray-100">
                <i class="text-sm text-[#A0A3BD] lab-fill-logout"></i>
                <span class="text-sm font-medium capitalize whitespace-nowrap">
                    {{ $t('button.logout') }}
                </span>
            </button>

        </nav>
    </div>
</template>

<script>
export default {
    name: "AccountMenuComponent",
    data() {
        return {
            image: "",
            name: "",
            phone: "",
        };
    },
    mounted() {
        try {
            const profile = this.$store.getters.authInfo;
            this.image = profile?.image;
            this.name = profile.name;
            this.phone = profile.phone ? profile.country_code + profile.phone : '';
        } catch (err) {
            alertService.error(err);
        }
    },
    computed: {
        profile() {
            return this.$store.getters.authInfo;
        }
    },
    methods: {
        logout: function () {
            this.$store.dispatch("logout").then(res => {
                this.$router.push({ name: "frontend.home" });
            }).catch();
        }
    }
};
</script>