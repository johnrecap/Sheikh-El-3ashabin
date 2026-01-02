<template>
    <a href="#" @click.prevent="excelDownload"
        class="w-full flex items-center gap-2 px-2.5 h-8 rounded-lg hover:text-primary hover:bg-primary/5">
        <i class="lab lab-line-file-excel lab-font-size-17 flex-shrink-0"></i>
        <span
            class="flex-auto ltr:text-left rtl:text-right text-sm capitalize whitespace-nowrap text-ellipsis overflow-hidden">
            {{ $t('button.excel') }}
        </span>
    </a>
</template>

<script>

export default {
    name: "ExcelComponent",
    props: {
        method: { type: Function }
    },
    methods: {
        excelDownload: function () {
            this.method();
        }
    }
}
</script>