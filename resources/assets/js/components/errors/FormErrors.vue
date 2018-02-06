<template>
    <div>
        <v-alert v-for="(es, key) in errors">
            <div v-for="e in es">{{e}}</div>
        </v-alert>
    </div>
</template>

<script>
    export default {
        props: ['errors']
    }
</script>