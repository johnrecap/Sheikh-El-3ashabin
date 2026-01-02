<template>
    <!-- MapComponent placeholder - Google Maps has been removed from address system -->
    <div class="map-placeholder p-4 bg-gray-100 rounded-lg text-center">
        <i class="fa-solid fa-map-location-dot text-4xl text-gray-400 mb-2"></i>
        <p class="text-sm text-gray-500">{{ $t('label.map_not_available') || 'الخريطة غير متاحة حالياً' }}</p>
    </div>
</template>

<script>
export default {
    name: "MapComponent",
    props: {
        location: { type: Object, default: () => ({ lat: 0, lng: 0 }) },
        position: { type: Function, default: () => {} }
    },
    mounted() {
        // Placeholder component - no functionality
        // This component is kept for backward compatibility with Outlet/DeliveryZone/Company settings
    }
}
</script>

<style scoped>
.map-placeholder {
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>
