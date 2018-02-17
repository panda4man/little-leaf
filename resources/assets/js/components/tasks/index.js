import Vue from 'vue';

Vue.component('tasks', {
    props: ['tasks'],
    data() {
        return {
            mTasks: this.tasks
        }
    }
});