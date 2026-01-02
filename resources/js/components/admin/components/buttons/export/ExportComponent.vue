<template>
    <button
        class="paper-button h-8 px-3 text-xs tracking-wide font-medium capitalize rounded-md shadow flex items-center justify-center gap-1 border border-primary text-primary bg-white">
        <i class="lab lab-line-file-export"></i>
        <span>{{ $t('button.export') }}</span>
        <i class="lab lab-line-chevron-down font-semibold"></i>
    </button>
</template>

<script>
export default {
    name: "ExportComponent"
}
</script>