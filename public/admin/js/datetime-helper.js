class DateTimeHelper {
    constructor() {
        this.dateObject = new Date();
        this.month = this.dateObject.getMonth();
        this.year = this.dateObject.getFullYear();
        this.date = this.dateObject.getDate();
        this.hour = this.dateObject.getHours();
        this.minutes = this.dateObject.getMinutes();
        this.seconds = this.dateObject.getSeconds();
        this.milliseconds = this.dateObject.getMilliseconds();
        this.now = this.dateObject.getTime();
    }

    

    pad(number) {
        if (number < 10) {
            return '0' + number;
        }
        return number.toString();
    }

    setDate(date) {
        this.dateObject = date;
    }

    getTimestamp() {
        return this.dateObject.getFullYear() +
            this.pad(this.dateObject.getMonth()) +
            this.pad(this.dateObject.getDate()) +
            this.pad(this.dateObject.getHours()) +
            this.pad(this.dateObject.getMinutes()) +
            this.pad(this.dateObject.getSeconds());
    }

    getDate() {
        return this.year.toString() +
            '-' + this.pad(this.month) +
            '-' + this.pad(this.date);
    }

    getTime() {
        return this.pad(this.hour) +
            ':' + this.pad(this.minutes) +
            ':' + this.pad(this.seconds);
    }

    getSqlDateTime() {
        return this.getDate() +
            ' ' + this.getTime();
    }

    toSqlDateTime(date) {
        const d = new Date(date);
        return d.getFullYear() +
            '-' + this.pad(d.getMonth()) +
            '-' + this.pad(d.getDate()) +
            ' ' + this.pad(d.getHours()) +
            ':' + this.pad(d.getMinutes()) +
            ':' + this.pad(d.getSeconds());
    }

    getDateTimeToInt() {
        return Date.parse(this.dateObject.toString());
    }

    getYearsElapsed(date) {
        const time1 = this.now;
        const time2 = Date.parse(date);
        const diff = Math.abs(time1 - time2);
        const milliseconds = 365 * 24 * 60 * 60 * 1000;
        return Math.floor(diff / milliseconds);
    }

    getDaysElapsed(date) {
        const time1 = this.now;
        const time2 = Date.parse(date);
        const diff = Math.abs(time1 - time2);
        let milliseconds = 365 * 24 * 60 * 60 * 1000;
        const rem = diff % milliseconds;
        milliseconds = 24 * 60 * 60 * 1000;
        return Math.floor(rem / milliseconds) - 30;
    }

    getHoursElapsed(date) {
        const time1 = this.now;
        const time2 = Date.parse(date);
        const diff = Math.abs(time1 - time2);
        let milliseconds = 365 * 24 * 60 * 60 * 1000;
        let rem = diff % milliseconds;
        milliseconds = 24 * 60 * 60 * 1000;
        rem = rem % milliseconds;
        milliseconds = 60 * 60 * 1000;
        return Math.floor(rem / milliseconds);
    }

    getMinutesElapsed(date) {
        const time1 = this.now;
        const time2 = Date.parse(date);
        const diff = Math.abs(time1 - time2);
        let milliseconds = 365 * 24 * 60 * 60 * 1000;
        let rem = diff % milliseconds;
        milliseconds = 24 * 60 * 60 * 1000;
        rem = rem % milliseconds;
        milliseconds = 60 * 60 * 1000;
        rem = rem % milliseconds;
        milliseconds = 60 * 1000;
        return Math.floor(rem / milliseconds);
    }

    getSecondsElapsed(date) {
        const time1 = this.now;
        const time2 = Date.parse(date);
        const diff = Math.abs(time1 - time2);
        let milliseconds = 365 * 24 * 60 * 60 * 1000;
        let rem = diff % milliseconds;
        milliseconds = 24 * 60 * 60 * 1000;
        rem = rem % milliseconds;
        milliseconds = 60 * 60 * 1000;
        rem = rem % milliseconds;
        milliseconds = 60 * 1000;
        rem = rem % milliseconds;
        milliseconds = 1000;
        return Math.floor(rem / milliseconds);
    }

    getTimeElapsed(date) {
        let elapsedTime = '';

        let time = this.getYearsElapsed(date);
        if (time > 0) {
            elapsedTime = elapsedTime + time + ' years, ';
        }

        time = this.getDaysElapsed(date);
        if (time > 0) {
            elapsedTime = elapsedTime + time + ' days, ';
        }

        time = this.getHoursElapsed(date);
        if (time > 0) {
            elapsedTime = elapsedTime + time + ' hours, ';
        }

        time = this.getMinutesElapsed(date);
        if (time > 0) {
            elapsedTime = elapsedTime + time + ' minutes, and ';
        }

        elapsedTime = elapsedTime + this.getSecondsElapsed(date) + ' seconds';

        return elapsedTime;
    }

    compareWithNow(date) {
        const newDate = new Date(date);
        return this.dateObject > newDate;
    }
    compareWith(date1, date2) {
        const d1 = new Date(date1);
        const d2 = new Date(date2);
        return d1 > d2;
    }
}