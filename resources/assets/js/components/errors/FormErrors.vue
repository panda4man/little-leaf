<template>
    <div>
        <v-alert :key="key" v-for="(es, key) in errors">
            <div :key="index" v-for="(e, index) in es">{{e}}</div>
        </v-alert>
    </div>
</template>

<script>
    export default {
        props: ['formErrors']
    }
</script>