<template>
    <p class="first-letter:capitalize">
        {{ $t('label.showing') }}
        <b>{{ props.page.from }}</b>
        {{ $t('label.to') }}
        <b>{{ props.page.to }}</b>
        {{ $t('label.of') }}
        <b>{{ props.page.total }}</b>
        {{ $t('label.results') }}
    </p>
</template>

<script>
export default {
    name: "PaginationTextComponent",
    props: ['props'],
}
</script>
