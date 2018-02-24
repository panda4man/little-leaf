import moment from 'moment';

export default class Project {
    constructor(attributes = {}) {
        // Inherit from attr object
        Object.keys(attributes).forEach(k => {
            this[k] = attributes[k];
        });

        // Set local variables not passed down from server
        this.hours_worked = 0;
    }

    get due_at_moment() {
        let value = this.due_at;

        if(value) {
            value = moment(this.due_at);
        }

        return value;
    }

    get completed_at_moment() {
        let value = this.completed_at;

        if(value) {
            value = moment.utc(value);
        }

        return value;
    }

    static hydrate(data) {
        if(Array.isArray(data)) {
            return data.map(o => {
                return new Project(o);
            });
        } else {
            return new Project(data);
        }
    }
}