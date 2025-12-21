<template>
    <button @click="openModal" class="db-table-action edit">
        <i class="lab lab-line-edit"></i>
        <span class="db-tooltip">{{ $t('button.edit') }}</span>
    </button>
</template>

<script>
import composables from '../../../../composables/composables'
export default {

    name: "SmIconSidebarModalEditComponent",
    methods: {
        openModal : function (){
           composables.openCanvas('sidebar');
        }
    }
}
</script>
