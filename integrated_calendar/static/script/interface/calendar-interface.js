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
                let prevmonth = new Date(d.getFullYear(), d.getMonth() - 1, d.getDate());
                url.set('date', `${prevmonth.getFullYear()}-${prevmonth.getMonth() + 1}-${prevmonth.getDate()}`);
                month_interface();
                break;
            case 2:
                let weekcontrol = new WeekControl();
                let prevweek = new Date(weekcontrol.prevWeek(d))
                url.set('date', `${prevweek.getFullYear()}-${prevweek.getMonth() + 1}-${prevweek.getDate()}`);
                week_interface();
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
                let nextMonth = new Date(d.getFullYear(), d.getMonth() + 1, d.getDate());
                url.set('date', `${nextMonth.getFullYear()}-${nextMonth.getMonth() + 1}-${nextMonth.getDate()}`);

                month_interface();
                break;
            case 2:
                let weekcontrol = new WeekControl();
                let nextweek = new Date(weekcontrol.nextWeek(d))
                url.set('date', `${nextweek.getFullYear()}-${nextweek.getMonth() + 1}-${nextweek.getDate()}`);
                week_interface();
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
                url.date = new Date();
                url.pushState();
                week_interface();
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

            week_interface();
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
                db.setAttribute('date', `${month + 1 == 1 ? year - 1 : year}-${month == 0 ? 12 : month}-${db.innerText}`);
            } else if (day_counter != month_end) {
                day_counter++;
                db.innerText = day_counter;
                db.setAttribute('date', `${year}-${month + 1}-${db.innerHTML}`);
            } else {
                next_month_counter++;
                db.innerText = next_month_counter;
                db.classList.add('disabled');
                db.setAttribute('date', `${month + 1 == 12 ? year + 1 : year}-${month == 11 ? 1 : month + 2}-${db.innerText}`);
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
    const current_date = new Date();
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
        d.classList.remove('current_date');
        if (excess != prev_month) {
            excess++;
            d.innerText = excess
            d.classList.add('disabled');
            d.setAttribute('date', `${month + 1 == 1 ? year - 1 : year}-${month <= 0 ? 12 : month}-${d.innerText}`);
        } else if (day_counter != month_end) {
            day_counter++;
            d.innerText = day_counter;
            d.setAttribute('date', `${year}-${month + 1}-${d.innerText}`);
        } else {
            next_month_counter++;
            d.innerText = next_month_counter;
            d.classList.add('disabled');
            d.setAttribute('date', `${month + 1 == 12 ? year + 1 : year}-${month + 2 >= 12 ? 1 : month + 2}-${d.innerText}`);
        }

        if (d.getAttribute('date') == `${current_date.getFullYear()}-${current_date.getMonth() + 1}-${current_date.getDate()}`) {
            d.classList.add('current_date');
        }
        // render collection
        collectionWidgets(d);

        // event listener
        d.addEventListener("mouseup", async () => {
            if (!d.classList.contains('disabled')) { modalInterface(d) }
        });
    });
}

// week
function week_interface() {
    const current_date = new Date();
    let dates = document.querySelectorAll(".date");
    let urldate = url.urlDate();
    let d = new Date(url.date);
    let month_name = d.toLocaleString('default', { month: 'long' });
    date_indication.innerHTML = `${month_name}-${d.getFullYear()}`;

    let weekcontrol = new WeekControl(urldate.year, urldate.month, urldate.date);
    weekcontrol.render();
    let week = weekcontrol.week;

    [...dates].forEach((d, i) => {
        d.classList.remove('current_date');
        d.innerText = week[i];
        d.setAttribute('date', `${urldate.year}-${urldate.monthNoZero}-${d.innerText}`);
        collectionWidgets(d);

        if (d.getAttribute('date') == `${current_date.getFullYear()}-${current_date.getMonth() + 1}-${current_date.getDate()}`) {
            d.classList.add('current_date');
        }

        d.addEventListener("mouseup", () => {
            modalInterface(d);
        });
    })
}

// Modal interface

async function modalInterface(d) {
    const add_sched_modal = document.querySelector(".add_sched_modal");
    add_sched_modal.classList.add("modal-background"); /* MODAL BACKGROUND ----------------------------------*/

    dataFetch.endpoint = "./calendar_modal.html";
    const calendar_modal = await dataFetch.fetchingHTML();
    const modal_content = document.querySelector(".modal_content");
    modal_content.replaceChildren(calendar_modal.querySelector("body"));

    const close_modal = document.querySelector("#close_modal");
    const task_reminders_btn = document.querySelector(".task-reminders-btn");
    const add_sched_btn = document.querySelector(".add-sched-btn");

    const content = document.querySelector("content");

    modal_content.classList.add("modal-position"); /* MODAL POSITION --------------------------------------*/

    taskPreviewInterface(d); // first to load
    close_modal.addEventListener("mouseup", () => {
        modal_content.removeChild(modal_content.firstChild);
        /* remove the modal styles on close */
        add_sched_modal.classList.remove("modal-background");
        modal_content.classList.remove("modal-position");
    });

    task_reminders_btn.addEventListener("mouseup", () => {
        taskPreviewInterface(d);
    });

    add_sched_btn.addEventListener("mouseup", () => {
        addScheduleInterface(d);
    });
}

async function taskPreviewInterface(d) {
    const content = document.querySelector("content");
    const individual_modal = document.querySelector("individual_modal");
    const tasks_holder = document.createElement("tasks_holder");
    url.set('date', d.getAttribute('date'));
    let urldate = await url.urlDate(); //urldate object
    if (collection != null) {
        collection.forEach(c => {
            if (c.type == "TASK" || c.type == "RMDR") {
                let div = document.createElement('div');
                let image = document.createElement('img');
                let p = document.createElement('p');
                let actions = document.createElement('actions');
                let text = null;
                if (c.title == "") {
                    text = document.createTextNode("Title");
                    p.classList.add('no-content');
                } else {
                    text = document.createTextNode(`${c.title}`);
                }
                p.appendChild(text);

                div.appendChild(image);
                if (c.type == "TASK") {
                    image.setAttribute('src', '../static/icons/user_ico.png');
                    image.setAttribute('height', '50rem');
                    c.isChecked ? image.classList.add('done') : image.classList.add('not-done');
                } else {
                    image.setAttribute('src', '../static/icons/bell_ico.png');
                    image.setAttribute('height', '50rem');
                }

                let update_btn = document.createElement('button');
                update_btn.classList.add('update_btn');
                let delete_btn = document.createElement('button');
                delete_btn.classList.add('delete_btn');

                text = document.createTextNode('Update');
                update_btn.appendChild(text);
                text = document.createTextNode('Delete');
                delete_btn.appendChild(text);

                div.appendChild(p);

                actions.appendChild(update_btn)
                actions.appendChild(delete_btn);

                div.appendChild(actions);

                if (c.isRecurring != undefined) {
                    let week = new Date(d.getAttribute('date'));
                    let recurring = `${c.isRecurring}`.match(/.{1}/g) || [];
                    if (recurring.includes(`${week.getDay()}`)) {
                        tasks_holder.appendChild(div);
                        p.addEventListener("mouseup", async () => {
                            individualSchedInterface(c);
                        }); // Event listener -------------------------------------
                    }
                } else {
                    let c_date = new Date(c.remindDate.date);
                    if (d.getAttribute('date') == `${c_date.getFullYear()}-${c_date.getMonth() + 1}-${c_date.getDate()}`) {
                        tasks_holder.appendChild(div);
                        p.addEventListener("mouseup", () => {
                            console.log("Task clicked");
                            individualSchedInterface(c);
                        }); // Event listener -------------------------------------
                    }
                }

                delete_btn.addEventListener("mouseup", async () => {
                    const reminder = new Reminder(null, c.id);

                    await Swal.fire({
                        title: 'Are you sure you want to delete?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            await reminder.delete();
                            tasks_holder.removeChild(div);
                        }
                    });
                });

                update_btn.addEventListener("mouseup", async () => {
                    dataFetch.endpoint = 'edit_schedule.html';
                    const update_schedule = await dataFetch.fetchingHTML();
                    const body = update_schedule.querySelector('body');
                    tasks_holder.replaceChildren(body);

                    individual_modal.innerHTML = "";

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
                    const recurrings = document.querySelectorAll(".recurring");
                    const message = document.querySelector("input[name='message']");

                    // Assign values to fields
                    id.value = c.id;

                    for (let index = 0; index < type.options.length; index++) {
                        if (type.options[index].value == c.type) {
                            type.options[index].selected = true;
                        }
                    }

                    title.value = c.title;

                    remind_date.value = `${urldate.year}-${urldate.month}-${urldate.date}`;

                    if (c.isRecurring != undefined) {
                        let recurr = `${c.isRecurring}`.match(/.{1}/g) || [];
                        recurrings.forEach(recurring => {
                            if (recurr.includes(recurring.value)) {
                                recurring.checked = true;
                            }
                        });
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
                    let image = document.createElement('img');
                    let p = document.createElement('p');
                    let text = document.createTextNode(`${c.title}`);
                    p.appendChild(text);
                    div.appendChild(image);
                    div.appendChild(p);

                    c.source == "CANVAS" ? image.setAttribute('src', '../static/icons/canvas_ico.png') : image.setAttribute('src', '../static/icons/classroom_ico.png');
                    image.setAttribute('height', '50rem');

                    c.hasSubmitted ? image.classList.add('done') : image.classList.add('not-done');

                    tasks_holder.appendChild(div);
                    p.addEventListener("mouseup", () => {
                        individualSrcInterface(c)
                    }); // Event listener -------------------------------------
                }
            }
        });
    }
    if (tasks_holder.innerHTML == "") {
        let p = document.createElement('p');
        let image = document.createElement('img');
        let text = document.createTextNode("Nothing to see here...");
        image.src = "../static/icons/placeholder.png";
        image.setAttribute("class", "holder");
        p.appendChild(text);
        tasks_holder.appendChild(image);
        tasks_holder.appendChild(p);
    }
    content.replaceChildren(tasks_holder);
}

async function addScheduleInterface(d) {
    const content = document.querySelector("content");

    url.set('date', d.getAttribute('date'));
    let urldate = await url.urlDate(); //urldate object

    dataFetch.endpoint = 'add_schedule.html';
    const add_schedule = await dataFetch.fetchingHTML();

    content.replaceChildren(add_schedule.querySelector('body'));

    const clicked_date = document.querySelector('#clicked_date');
    const postReminder = document.querySelector('#post-reminder-button');
    const remind_date = document.querySelector("input[name='remind-date']");

    clicked_date.replaceChildren(d.getAttribute('date'));
    // set value of date picker
    remind_date.value = `${urldate.year}-${urldate.month}-${urldate.date}`;

    postReminder.addEventListener("mouseup", async () => {
        const form = document.querySelector('#post-reminder-form');
        const reminder = new Reminder(form, null);
        await reminder.post();
    });
}
// indicators for collection
function collectionWidgets(d) {
    if (collection != null) {
        collection.forEach(c => {
            if (c.type == "TASK" || c.type == "RMDR") {
                let div = document.createElement('div');
                let image = document.createElement('img');
                div.classList.add(c.type);
                let text = document.createTextNode(c.title == "" ? "(no-title)" : `${c.title.substr(0, 12)}...`);
                div.appendChild(image);
                div.appendChild(text);

                if (c.isRecurring != undefined) {
                    let week = new Date(d.getAttribute('date'));
                    let recurring = `${c.isRecurring}`.match(/.{1}/g) || [];

                    if (recurring.includes(`${week.getDay()}`)) {
                        d.appendChild(div);
                    }
                } else {
                    let c_date = new Date(c.remindDate.date);
                    if (d.getAttribute('date') == `${c_date.getFullYear()}-${c_date.getMonth() + 1}-${c_date.getDate()}`) {
                        d.appendChild(div);
                    }
                }

                if (c.type == "TASK") {
                    c.isChecked ? div.classList.add('done') : div.classList.add('not-done');
                    image.setAttribute('src', '../static/icons/user_ico.png');
                    image.setAttribute('height', '25px');
                } else {
                    image.setAttribute('src', '../static/icons/bell_ico.png');
                    image.setAttribute('height', '25px');
                }
            }

            if (c.type == "COURSEWORK" && c.dueDate) {
                let c_date = c.dueDate;
                if (d.getAttribute('date') == `${c_date.year}-${c_date.month}-${c_date.day}`) {
                    let div = document.createElement('div')
                    let image = document.createElement('img');
                    div.appendChild(image);

                    c.source == "CANVAS" ? image.setAttribute('src', '../static/icons/canvas_ico.png') : image.setAttribute('src', '../static/icons/classroom_ico.png');
                    image.setAttribute('height', '25px');

                    div.classList.add(c.source);
                    let text = document.createTextNode(c.title == "" ? "(no-title)" : `${c.title.substr(0, 12)}...`);
                    div.appendChild(text);
                    c.hasSubmitted ? div.classList.add('done') : div.classList.add('not-done');
                    d.appendChild(div);
                }
            }
        });
    }
}

// Individual modals
async function individualSchedInterface(c) {
    const individual_modal = document.querySelector("individual_modal");
    dataFetch.endpoint = "./view_individual_schedule.html";
    const individual_schedule = await dataFetch.fetchingHTML();
    const card_user = individual_schedule.querySelector('preview_card_user');
    const close_preview_btn = card_user.querySelector("#close_modal");
    const date = card_user.querySelector("preview_date");
    const type = card_user.querySelector("preview_type");
    const title = card_user.querySelector("preview_title");
    const target_course = card_user.querySelector("preview_target_course");
    const message = card_user.querySelector("preview_message");
    const isRecurring = card_user.querySelector("preview_isRecurring");
    const isChecked = card_user.querySelector("preview_isChecked");

    close_preview_btn.addEventListener("mouseup", () => {
        individual_modal.removeChild(individual_modal.firstChild);
    });

    let c_date = new Date(url.date);

    date.innerText = c.remindDate == null ? "" : `${c_date.getFullYear()}-${c_date.getMonth() + 1}-${c_date.getDate()}`;
    type.innerText = c.type == "RMDR" ? "Reminder" : "Task";
    title.innerText = c.title;
    target_course.innerText = c.targetCourse;
    message.innerText = c.message;
    isRecurring.innerText = c.isRecurring == null ? "" : `Recurring every ${c_date.toLocaleString('default', { weekday: 'long' })}`;
    isChecked.innerText = c.isChecked ? "Done" : "Not yet done.";

    individual_modal.replaceChildren(card_user);
    console.log(c);
}
// Individial source interface modal for data from api
async function individualSrcInterface(c) {
    const individual_modal = document.querySelector("individual_modal");
    dataFetch.endpoint = "./view_individual_schedule.html";
    const individual_schedule = await dataFetch.fetchingHTML();
    const card_source = individual_schedule.querySelector('preview_card_source');

    const close_preview_btn = card_source.querySelector("#close_modal");
    const date = card_source.querySelector("preview_date"); //dueDate
    const type = card_source.querySelector("preview_type"); // coursework
    const source = card_source.querySelector("preview_source"); // google or canvas

    const title = card_source.querySelector("preview_title");
    const courseName = card_source.querySelector("preview_courseName");
    const description = card_source.querySelector("preview_description");
    const hasSubmitted = card_source.querySelector("preview_hasSubmitted");
    const link = card_source.querySelector("preview_link");
    const isQuiz = card_source.querySelector("preview_isQuiz");

    close_preview_btn.addEventListener("mouseup", () => {
        individual_modal.removeChild(individual_modal.firstChild);
    });

    let c_date = c.dueDate;

    date.innerText = `${c_date.year}-${c_date.month}-${c_date.day}`;
    type.innerText = c.type;
    source.innerText = c.source;
    title.innerText = c.title;
    courseName.innerText = c.courseName;
    description.innerHTML = c.description;
    hasSubmitted.innerText = c.hasSubmitted ? "Submitted" : "Not yet submitted";
    isQuiz.innerText = c.isQuiz ? "Quiz" : "Activity/Assignment";
    link.innerHTML = `<a href="${c.link}">${c.link}</a>`;

    individual_modal.replaceChildren(card_source);
    console.log(c);

}