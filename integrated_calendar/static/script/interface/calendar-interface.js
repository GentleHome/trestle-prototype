calendar = async () => {
    const container = document.querySelector('.container');
    const choose_view = document.querySelector(".choose_view");
    const prev = document.querySelector("#prev");
    const next = document.querySelector("#next");
    const today = document.querySelector("#today");
    const date_indication = document.querySelector("#date_indication");
    const selected_date = document.querySelector('#date');

    choose_view.addEventListener("change", go_choose_view);
    prev.addEventListener("mouseup", prev_btn);
    next.addEventListener("mouseup", next_btn);
    today.addEventListener("mouseup", today_btn);

    renderer(url.view);
    choose_view.options[url.view].selected = 'true';

    function go_choose_view() {
        url.set('view', parseInt(this.value));
        renderer();
    }

    function prev_btn() {
        let d = new Date(url.date);
        switch (parseInt(url.view)) {
            case 0:
                url.set('date', `${d.getFullYear() - 1}-${d.getMonth() + 1}-${d.getDate()}`);
                year_interface();
                break;
            case 1:
                // getMonth() has 1 offset e.g 0 = january
                if (d.getMonth() <= 0) {
                    url.set('date', `${d.getFullYear() - 1}-12-${d.getDate()}`);
                } else {
                    url.set('date', `${d.getFullYear()}-${d.getMonth()}-${d.getDate()}`);
                }
                month_interface();
                break;

            default:
                break;
        }
    }

    function next_btn() {
        let d = new Date(url.date);
        switch (parseInt(url.view)) {
            case 0:
                url.set('date', `${d.getFullYear() + 1}-${d.getMonth() + 1}-${d.getDate()}`);
                year_interface();
                break;
            case 1:
                // getMonth() has 1 offset e.g 0 = january
                if (d.getMonth() >= 11) {
                    url.set('date', `${d.getFullYear() + 1}-1-${d.getDate()}`)
                } else {
                    url.set('date', `${d.getFullYear()}-${d.getMonth() + 2}-${d.getDate()}`)
                }

                month_interface();
                break;

            default:
                break;
        }
    }

    function today_btn() {
        selected_date.innerHTML = "";
        switch (parseInt(url.view)) {
            case 0:
                url.date = new Date();
                url.pushState();
                year_interface();
                break;

            case 1:
                url.date = new Date();
                url.pushState();
                month_interface();
                break;

            default:
                break;
        }
    }
}
// renderer
async function renderer() {
    const container = document.querySelector('.container');
    switch (parseInt(url.view)) {
        case 0:
            dataFetch.endpoint = 'view_year.html';
            const view_year = await dataFetch.fetchingHTML();
            container.replaceChildren(view_year.querySelector('year'));
            year_builder();
            year_interface();
            break;

        case 1:
            dataFetch.endpoint = 'view_month.html';
            const view_month = await dataFetch.fetchingHTML();
            container.replaceChildren(view_month.querySelector('month'));

            month_builder();
            month_interface();
            break;

        case 2:
            dataFetch.endpoint = 'view_week.html';
            const view_week = await dataFetch.fetchingHTML();
            container.replaceChildren(view_week.querySelector('weeks'));

            break;

        default:
            break;
    }
}
// year
function year_builder() {
    let date_container = document.querySelectorAll('.date-container'); // get all date_containers
    date_container.forEach(dc => {
        for (let index = 0; index < 42; index++) {
            let div = document.createElement('div');
            div.setAttribute('class', 'date-box');
            let text = document.createTextNode(index);
            div.appendChild(text);
            dc.appendChild(div); // child date-container
        }
    });
}

function year_interface() {
    const current_date = new Date();
    const selected_date = document.querySelector('#date');
    let date_container = document.querySelectorAll('.date-container'); // get all date_containers

    let d = new Date(url.date);
    let year = d.getFullYear();
    let month = 0; // january

    date_indication.innerHTML = year;

    date_container.forEach(dc => {
        // get what day is the start of month. e.g monday, tuesday...so on
        let month_start = new Date(year, month, 1).getDay();
        // date current month ends
        let month_end = new Date(year, month + 1, 0).getDate();
        // date prev month ends
        let prev_month = new Date(year, month, 0).getDate();
        // excess dates from prev month
        let excess = prev_month - month_start;
        let day_counter = 0;
        let next_month_counter = 0;

        let date_box = dc.children; // get .date-box 
        [...date_box].forEach(db => {
            db.classList.remove('disabled');
            db.classList.remove('current_date');
            db.classList.remove('active')
            if (excess != prev_month) {
                excess++;
                db.innerText = excess;
                db.classList.add('disabled');
                db.setAttribute('date', `${month + 1 == 1 ? year - 1 : year}-${month}-${d.innerText}`);
            } else if (day_counter != month_end) {
                day_counter++;
                db.innerText = day_counter;
                db.setAttribute('date', `${year}-${month + 1}-${db.innerHTML}`);
            } else {
                next_month_counter++;
                db.innerText = next_month_counter;
                db.classList.add('disabled');
                db.setAttribute('date', `${month + 1 == 12 ? year + 1 : year}-${month + 2}-${d.innerText}`);
            }

            if (db.getAttribute('date') == `${d.getFullYear()}-${d.getMonth() + 1}-${d.getDate()}`) {
                db.classList.add('active');
            }

            if (db.getAttribute('date') == `${current_date.getFullYear()}-${current_date.getMonth() + 1}-${current_date.getDate()}`) {
                db.classList.add('current_date');
            }
            db.addEventListener('mouseup', () => {
                let actives = document.querySelectorAll('.active');
                if (actives.length > 0) {
                    actives.forEach(active => {
                        active.className = active.className.replace(" active", "");
                    });
                }
                db.classList.add('active');
                url.set('date', db.getAttribute('date'));
                selected_date.innerHTML = db.getAttribute('date');
            });

        });
        month++; // increment month
    });
}
// month
function month_builder() {
    let dates = document.querySelector('dates');
    for (let index = 0; index < 42; index++) {
        let div = document.createElement('div');
        div.setAttribute('class', 'date');
        let text = document.createTextNode(index);
        div.appendChild(text);
        dates.appendChild(div);
    }
}

async function month_interface() {
    let d = new Date(url.date);
    let dates = document.querySelector('dates').children;

    let year = d.getFullYear();
    let month = d.getMonth();

    let month_name = d.toLocaleString('default', { month: 'long' });
    date_indication.innerHTML = `${month_name}-${d.getFullYear()}`;

    let month_start = new Date(year, month, 1).getDay();
    // date current month ends
    var month_end = new Date(year, month + 1, 0).getDate();
    // date prev month ends
    let prev_month = new Date(year, month, 0).getDate();
    // excess dates from prev month
    let excess = prev_month - month_start;
    let day_counter = 0;
    let next_month_counter = 0;
    [...dates].forEach(d => {
        d.classList.remove('disabled');
        if (excess != prev_month) {
            excess++;
            d.innerText = excess
            d.classList.add('disabled');
            d.setAttribute('date', `${month + 1 == 1 ? year - 1 : year}-${month}-${d.innerText}`);
        } else if (day_counter != month_end) {
            day_counter++;
            d.innerText = day_counter;
            d.setAttribute('date', `${year}-${month + 1}-${d.innerText}`);
        } else {
            next_month_counter++;
            d.innerText = next_month_counter;
            d.classList.add('disabled');
            d.setAttribute('date', `${month + 1 == 12 ? year + 1 : year}-${month + 2}-${d.innerText}`);
        }
        // render collection
        collectionWidgets(d);

        // event listener
        d.addEventListener("mouseup", async () => { modalInterface(d) });
    });
}

// week
function week_interface() {

}

// Modal interface
async function modalInterface(d) {
    {
        url.set('date', d.getAttribute('date'));
        let urldate = await url.urlDate(); //urldate object

        dataFetch.endpoint = 'add_schedule.html';
        const add_schedule = await dataFetch.fetchingHTML();
        const modal_content = document.querySelector('.modal_content');
        modal_content.replaceChildren(add_schedule.querySelector('body'));

        const clicked_date = document.querySelector('#clicked_date');
        const close_modal = document.querySelector('#close_modal');
        const task_preview = document.querySelector('task_preview');
        const postReminder = document.querySelector('#post-reminder-button');
        const remind_date = document.querySelector("input[name='remind-date']");

        clicked_date.replaceChildren(d.getAttribute('date'));

        close_modal.addEventListener("mouseup", () => {
            modal_content.removeChild(modal_content.firstChild);
        });

        // set value of date picker
        remind_date.value = `${urldate.year}-${urldate.month}-${urldate.date}`;

        if (collection != null) {
            collection.forEach(c => {
                if (c.type == "TASK" || c.type == "RMDR") {
                    let div = document.createElement('div');
                    let p = document.createElement('p');
                    let text = document.createTextNode(`Title: ${c.title}`);
                    p.appendChild(text);
                    let delete_btn = document.createElement('button');
                    let update_btn = document.createElement('button');
                    text = document.createTextNode('delete');
                    delete_btn.appendChild(text);
                    text = document.createTextNode('update');
                    update_btn.appendChild(text);
                    div.appendChild(p);
                    div.appendChild(delete_btn);
                    div.append(update_btn);

                    if (c.isRecurring) {
                        let week = new Date(d.getAttribute('date'));
                        if (week.getDay() == c.isRecurring) {
                            task_preview.appendChild(div);
                        }
                    } else {
                        let c_date = new Date(c.remindDate.date);
                        if (d.getAttribute('date') == `${c_date.getFullYear()}-${c_date.getMonth() + 1}-${c_date.getDate()}`) {
                            task_preview.appendChild(div);
                        }
                    }

                    delete_btn.addEventListener("mouseup", async () => {
                        const reminder = new Reminder(null, c.id);
                        await reminder.delete();
                        task_preview.removeChild(div);
                    });

                    update_btn.addEventListener("mouseup", async () => {
                        dataFetch.endpoint = 'edit_schedule.html';
                        const update_schedule = await dataFetch.fetchingHTML();
                        const body = update_schedule.querySelector('body');
                        task_preview.replaceChildren(body);

                        const cancel_btn = document.querySelector('#cancel_edit');
                        cancel_btn.addEventListener("mouseup", () => {
                            modalInterface(d); // render prev contents of task_preview
                        });

                        // Edit Reminder
                        const edit_btn = document.querySelector('#edit-reminder-button');
                        // Get fields
                        const id = document.querySelector("input[name='reminder-id']");
                        const type = document.querySelector('#edit-reminder-type-option');
                        const title = document.querySelector("input[name='title']");
                        const remind_date = document.querySelector("input[name='remind-date']");
                        const isRecurring = document.querySelector("input[name='is-recurring']");
                        const message = document.querySelector("textarea[name='message']");

                        // Assign values to fields
                        id.value = c.id;

                        for (let index = 0; index < type.options.length; index++) {
                            if (type.options[index].value == c.type) {
                                type.options[index].selected = true;
                            }
                        }
                        title.value = c.title;
                        if (c.remindDate) {
                            remind_date.value = `${urldate.year}-${urldate.month}-${urldate.date}`;
                        }

                        if (c.isRecurring) {
                            isRecurring.checked = true;
                        }
                        if (c.message) {
                            message.value = c.message;
                        }

                        edit_btn.addEventListener("mouseup", async () => {
                            const form = document.querySelector("#edit-reminder-form");
                            const reminder = new Reminder(form, null);
                            await reminder.update();
                        })
                    });
                }

                if (c.type == "COURSEWORK" && c.dueDate) {
                    let c_date = c.dueDate;
                    if (d.getAttribute('date') == `${c_date.year}-${c_date.month}-${c_date.day}`) {
                        let div = document.createElement('div');
                        let p = document.createElement('p');
                        let text = document.createTextNode(`Title: ${c.title}`);
                        p.appendChild(text);
                        div.appendChild(p);

                        task_preview.appendChild(div);
                    }
                }

            });
        }

        postReminder.addEventListener("mouseup", async () => {
            const form = document.querySelector('#post-reminder-form');
            const reminder = new Reminder(form, null);
            await reminder.post();
        });

    }
}
// indicators for collection
function collectionWidgets(d) {
    if (collection != null) {
        collection.forEach(c => {
            if (c.type == "TASK" || c.type == "RMDR") {
                let div = document.createElement('div')
                div.classList.add(c.type);
                let text = document.createTextNode(c.title == "" ? "no-title" : c.title);
                div.appendChild(text);

                if (c.isRecurring) {
                    let week = new Date(d.getAttribute('date'))
                    if (week.getDay() == c.isRecurring) {
                        d.appendChild(div);
                    }
                } else {
                    let c_date = new Date(c.remindDate.date);
                    if (d.getAttribute('date') == `${c_date.getFullYear()}-${c_date.getMonth() + 1}-${c_date.getDate()}`) {
                        d.appendChild(div);
                    }
                }

            }

            if (c.type == "COURSEWORK" && c.dueDate) {
                let c_date = c.dueDate;
                if (d.getAttribute('date') == `${c_date.year}-${c_date.month}-${c_date.day}`) {
                    let div = document.createElement('div')
                    div.classList.add(c.source);
                    let text = document.createTextNode(c.title == "" ? "no-title" : c.title);
                    div.appendChild(text);
                    d.appendChild(div);
                }
            }
        });
    }
}