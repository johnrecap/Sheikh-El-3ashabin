<template>
    <button v-print="props"
        class="w-full flex items-center gap-2 px-2.5 h-8 rounded-lg hover:text-primary hover:bg-primary/5">
        <i class="lab lab-line-printer lab-font-size-17 flex-shrink-0"></i>
        <span
            class="flex-auto ltr:text-left rtl:text-right text-sm capitalize whitespace-nowrap text-ellipsis overflow-hidden">
            {{ $t('button.print') }}
        </span>
    </button>
</template>

<script>
import print from "vue3-print-nb";

export default {
    name: "PrintComponent",
    props: ["props"],
    directives: {
        print
    }
}
</script>