// for fetching/getting data;
class DataFetch {
    // arranged by latency
    constructor(endpoint) {
        this.endpoint = endpoint;
    }

    fetching = async () => {
        const req = new Request(this.endpoint);

        const res = await fetch(req);
        const data = await res.text();
        let parsed = await JSON.parse(data);
        if ('errors' in parsed) {
            return;
        }
        return parsed;
    }

    fetchingHTML = async () => {
        const req = new Request(this.endpoint);
        const res = await fetch(req);
        const data = await res.text();
        let parser = new DOMParser();
        let doc = parser.parseFromString(data, 'text/html');
        return doc;
    }
    // How to use?
    // instantiate DataFetch
    // set endpoint value
    // then call the method fetching you want
}

// CRUD of Reminders
class Reminder {
    constructor(form, id) {
        this.form = form;
        this.id = id;
    }
    // post, update, delete

    // get the form by querySelector or whatever
    // set the form;
    post = async () => {
        const formData = new URLSearchParams();

        for (const pair of new FormData(this.form)) {
            formData.append(pair[0], pair[1]);
        }

        const data = {
            method: 'POST',
            body: formData
        };

        const req = new Request('../../api/post_reminder.php', data);
        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                var parsed = JSON.parse(data);
                console.log(parsed);
                reminders(); // render the reminders again
            });
    }

    update = async () => {
        const formData = new URLSearchParams();

        for (const pair of new FormData(this.form)) {
            formData.append(pair[0], pair[1]);
        }

        const data = {
            method: 'POST',
            body: formData
        };

        const req = new Request('../../api/edit_reminder.php', data);
        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                let parsed = JSON.parse(data);
                console.log(parsed);
                reminders(); // render the reminders again
            });
    }

    delete = async () => {
        // Delete Reminder

        const formData = new URLSearchParams();

        formData.append('reminder-id', this.id);

        var data = {
            method: 'POST',
            body: formData
        };

        const req = new Request('../../api/delete_reminder.php', data);

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                var parsed = JSON.parse(data);
                console.log(parsed);
                reminders(); // render the reminders again
            });
    }
}

// Updates the URL 
class Update {
    constructor(view, date) {
        this.view = view;
        this.date = date;
    }

    pushState = () => {
        let d = new Date(this.date);
        let date = `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`;

        // YY-MM-dd
        window.history.replaceState({}, `${window.location.href}`, `?view=${this.view}&date=${date}`)
    }
    // check url if params exist
    checkState = () => {
        let url = new URL(window.location.href);
        if (url.searchParams.get('view')) {
            this.view = url.searchParams.get('view');
            this.date = url.searchParams.get('date');
        } else {
            this.view = 1;
            this.date = Date();
            this.pushState();
        }
    }

    set = (paramName, paramValue) => {
        let url = new URL(window.location.href);
        switch (paramName) {
            case 'view':
                this.view = paramValue;
                url.searchParams.set(paramName, this.view);
                break;

            case 'date':
                this.date = paramValue;
                url.searchParams.set(paramName, this.date);
                break;

            default:
                break;
        }
        this.pushState();
    }

    urlDate = (date = this.date) => { // no offset 1 = January
        let d = new Date(date);
        let urldate = {
            year: d.getFullYear(),
            month: (d.getMonth() + 1).toString().length <= 1 ? `0${d.getMonth() + 1}` : d.getMonth() + 1,
            date: d.getDate().toString().length <= 1 ? `0${d.getDate()}` : d.getDate(),
            day: d.getDay(),
        }
        return urldate;
    }
}

class WeekControl {
    // please put month without offset e.g january should be 1 not 0;
    constructor(year, month, date) {
        this.year = parseInt(year);
        this.month = parseInt(month);
        this.date = parseInt(date);
        this.weeks = null;
        this.week = null;
        this.index = null;
    }

    render = () => {
        this.weeks = this.getWeeksInMonth(this.year, this.month);
        let key = this.whatWeek();
        switch (parseInt(key)) {
            case 0:
                this.week = [...this.prevMonth(), ...this.weeks[key]];
                break;
            case this.weeks.length - 1:
                this.week = [...this.weeks[key], ...this.nextMonth()];
                break;
            default:
                this.week = [...this.weeks[key]];
                break;
        }
    }

    whatWeek = () => {
        for (let i = 0; i < this.weeks.length; i++) {
            if (this.weeks[i].includes(this.date)) {
                this.index = i;
                return i;
            }
        }
    }

    prevMonth = () => {
        let weeks = null;
        if (this.month == 1) {
            weeks = this.getWeeksInMonth(this.year - 1, 12);
        } else {
            weeks = this.getWeeksInMonth(this.year, this.month - 1);
        }
        return weeks.at(-1);
    }

    nextMonth = () => {
        let weeks = null;
        if (this.month == 12) {
            weeks = this.getWeeksInMonth(this.year + 1, 1);
        } else {
            weeks = this.getWeeksInMonth(this.year, this.month + 1);
        }
        return weeks[0];
    }

    // code from https://gist.github.com/markthiessen/3883242
    getWeeksInMonth = (year, month) => { // no offset must be 1 = january
        const weeks = [],
            firstDate = new Date(year, month - 1, 1),
            lastDate = new Date(year, month, 0),
            numDays = lastDate.getDate();

        let dayOfWeekCounter = firstDate.getDay();

        for (let date = 1; date <= numDays; date++) {
            if (dayOfWeekCounter === 0 || weeks.length === 0) {
                weeks.push([]);
            }
            weeks[weeks.length - 1].push(date);
            dayOfWeekCounter = (dayOfWeekCounter + 1) % 7;
        }

        return weeks;
    }
}

// test cases for week control
weekControlTest = () => {
    let weekcontrol = new WeekControl(2021, 1, 1);
    console.log("First month first week");
    weekcontrol.render();
    let week = weekcontrol.week;
    let expectedValue = [27, 28, 29, 30, 31, 1, 2]
    for (let index = 0; index < expectedValue.length; index++) {
        if (week[index] != expectedValue[index]) {
            console.log("Failed");
            break
        }
    }
    console.log("Passed");

    weekcontrol = new WeekControl(2021, 1, 3);
    console.log("First month second week");
    weekcontrol.render();
    week = weekcontrol.week;
    expectedValue = [3, 4, 5, 6, 7, 8, 9]
    for (let index = 0; index < expectedValue.length; index++) {
        if (week[index] != expectedValue[index]) {
            console.log("Failed");
            break
        }
    }
    console.log("Passed");

    weekcontrol = new WeekControl(2021, 12, 31);
    console.log("last month last week");
    weekcontrol.render();
    week = weekcontrol.week;
    expectedValue = [26, 27, 28, 29, 30, 31, 1]
    for (let index = 0; index < expectedValue.length; index++) {
        if (week[index] != expectedValue[index]) {
            console.log("Failed");
            break;
        }
    }
    console.log("Passed");

    weekcontrol = new WeekControl(2021, 12, 21);
    console.log("last month fourth week");
    weekcontrol.render();
    week = weekcontrol.week;
    expectedValue = [19, 20, 21, 22, 23, 24, 25];
    for (let index = 0; index < expectedValue.length; index++) {
        if (week[index] != expectedValue[index]) {
            console.log("Failed");
            break;
        }
    }
    console.log("Passed");
}