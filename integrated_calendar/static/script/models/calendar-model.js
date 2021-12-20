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
                console.log(data);
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

    pushState = async () => {
        let d = new Date(this.date);
        let date = `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`;

        // YY-MM-dd
        window.history.replaceState({}, `${window.location.href}`, `?view=${this.view}&date=${date}`)
    }
    // check url if params exist
    checkState = async () => {
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

    set = async (paramName, paramValue) => {
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

    urlDate = async (date = this.date) => {
        let d = new Date(date);
        let urldate = {
            year: d.getFullYear(),
            month: (d.getMonth() + 1).toString().length <= 1 ? `0${d.getMonth() + 1}` : d.getMonth() + 1,
            date: d.getDate().toString().length <= 1 ? `0${d.getDate()}` : d.getDate(),
        }
        return urldate;
    }
}

class WeekControl {

}