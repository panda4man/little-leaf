<template>
    <div>
        <v-alert :value="true" type="error" :key="key" v-for="(es, key) in formErrors">
            <div :key="index" v-for="(e, index) in es">{{e}}</div>
        </v-alert>
    </div>
</template>

<script>
    export default {
        props: ['formErrors']
    }
</script>