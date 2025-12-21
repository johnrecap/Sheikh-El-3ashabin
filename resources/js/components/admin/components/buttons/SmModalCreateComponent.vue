<template>
    <button type="button" @click="add" data-modal="#modal" class="h-8 px-3 flex items-center gap-2 text-xs tracking-wide capitalize rounded-md shadow text-white bg-primary">
        <i class="lab lab-line-add-circle"></i>
        <span>{{ props.title }}</span>
    </button>
</template>

<script>
import composables from "../../../../composables/composables";

export default {
    name: "SmModalCreateComponent",
    props: ['props'],
    methods: {
        add: function () {
            composables.openModal('modal');
        }
    }
}
</script>
