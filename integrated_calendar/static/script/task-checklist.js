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
}
class Reminder {
    constructor(form, id, is_checked) {
        this.form = form;
        this.id = id;
        this.is_checked = is_checked;
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

    done = async () => {
        // Set a task as DONE

        var req;
        const formData = new URLSearchParams();

        formData.append('reminder-id', this.id);
        formData.append('is-checked', this.is_checked);

        var data = {
            method: 'POST',
            body: formData
        };

        req = new Request('../../api/set_is_checked.php', data);

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                var parsed = JSON.parse(data);
                console.log(parsed);
                reminders(); // render the reminders again
            });
    }
}

var collection = [];
const dataFetch = new DataFetch();
reminders();
add_edit_delete_check();

async function reminders() { // fetch reminders
    dataFetch.endpoint = '../../api/get_reminders.php?type=TASK';
    category_reminder = await dataFetch.fetching();
    if (category_reminder == undefined) { // if undefined user not logged in
        return;
    }
    collection = category_reminder;
    arrangeData();
    add_edit_delete_check();
}
async function arrangeData() {
    const image = "../static/icons/user_ico.png";

    const tasksSection = document.querySelector("#tasks_section");
    var task_id;
    var title;
    var description;
    var date_posted;
    var due_date;
    var is_checked;
    var type;
    var is_recurring;
    let html = "";
    if (collection) {
        const sortedCollection = sortCollection(collection);
        for (let x = 0; x < sortedCollection.length; x++) {
            let tasks = sortedCollection[x];
            if (tasks.type == "TASK") {
                task_id = tasks.id;
                title = tasks.title == null ? "" : tasks.title;
                description = tasks.message == null ? "\nDescription is not indicated." : tasks.message;
                date_posted = new Date(tasks.dateCreated.date);
                due_date = new Date(tasks.remindDate.date);
                is_checked = tasks.isChecked;
                type = tasks.type;
                is_recurring = tasks.isRecurring;
                html += notification_section(image, task_id, type, title, description, date_posted, due_date, is_checked, is_recurring);
                add_edit_delete_check();
            }
        }
    }
    tasksSection.innerHTML = html;
}

function sortCollection(forSorting) {
    let not_done = [];
    let done = [];
    for (let x = 0; x < forSorting.length; x++) {
        if (!forSorting[x].isChecked) {
            not_done.push(forSorting[x]);
        } else {
            done.push(forSorting[x]);
        }
    }
    not_done = not_done.sort((a, b) => new Date(a.remindDate.date) - new Date(b.remindDate.date));

    return not_done.concat(done);
}

function notification_section(image, task_id, type, title, description, date_posted, due_date, is_checked, is_recurring) {
    let html = "";
    let datePosted = format_date_time(date_posted);
    let isChecked = is_checked ? "\nCOMPLETED" : "";
    let check_int = is_checked ? 1 : 0;
    let dueDate;

    if (due_date != null) {
        dueDate = format_date_time(due_date);
    } else {
        dueDate = "No due date."
    }

    html += '<div class="task" id="' + task_id + '">';
    html += '<img src="' + image + '"" alt = "icon" width = "50" class="source_image"> ';
    html += '<div class="contents">';
    html += '<span class="title"><b> Title: </b>' + title + '</span><br>';
    html += '<span class="dueDate"><b> Date Posted: </b>' + datePosted + '</span><br>';
    html += '<span class="dueDate"><b> Due Date: </b>' + dueDate + '</span><br>';
    html += '<span class="finish"><b>' + isChecked + '</b></span>';
    html += '<div class="description">' + description + '</div><br></div>';
    html += '<div class="button-container">';
    if (is_checked) {
        html += `<button class="mark_as_done" reminderID = ${task_id} isChecked=${check_int}>Unsubmit`;
    } else {
        html += `<button class="mark_as_done" reminderID = ${task_id} isChecked=${check_int}>Mark as Done</button>`;
    }
    html += `<button 
    class="edit_task" 
    reminderID = ${task_id} 
    type=${type} 
    title="${title}"
    message="${description}"
    remindDate="${dueDate}" 
    isRecurring=${is_recurring} 
    isChecked=${check_int} 
    >Edit</button>
    <button class="delete_task" reminderID = ${task_id}>Delete</button> <br><br>`
    html += '</div>';
    html += '</div>';
    html += '</div>';
    return html;
}

function format_date_time(dueDate) {
    newdate = new Date(dueDate).toLocaleString('en-US', {
        weekday: 'long',
        day: 'numeric',
        year: 'numeric',
        month: 'long',
    });
    return newdate;
}

async function addShedule_view() {
    let modal = document.querySelector('.add_sched_modal');
    let modal_content = document.querySelector('.modal_content');

    modal.style.display = 'block';

    let html = await fetchGETData('add_task.html');
    let parser = new DOMParser();
    let doc = parser.parseFromString(html, 'text/html');

    modal_content.innerHTML = doc.querySelector('body').innerHTML;
    let close_modal = document.querySelector('#close_modal');
    close_modal.addEventListener('mouseup', () => {
        modal.style.display = 'none';
    });

    clicked_date = format_date_time(new Date());

    document.querySelector("#clicked_date").innerHTML = clicked_date;

    POSTreminder();
}

async function fetchGETData(src) {
    let res = await fetch(src);
    let data = await res.text();
    return data;
}

function add_edit_delete_check() {
    let edit_task = document.querySelectorAll('.edit_task');
    let delete_task = document.querySelectorAll('.delete_task');
    let task_btn = document.querySelector("#add_task");
    let done_btn = document.querySelectorAll(".mark_as_done");

    task_btn.addEventListener('mouseup', () => { addShedule_view() });

    edit_task.forEach(btn => {
        btn.addEventListener('mouseup', () => { edit_schedule(btn) });
    });

    delete_task.forEach(btn => {
        btn.addEventListener('mouseup', async () => {
            DELETEreminder(btn.getAttribute('reminderID'));
        });
    });
    done_btn.forEach(btn => {
        btn.addEventListener('mouseup', async () => {
            CHECKreminder(btn.getAttribute('reminderID'), btn.getAttribute('isChecked'));
        });
    });
}

async function edit_schedule(reminderID) {
    let modal = document.querySelector('.add_sched_modal');
    let modal_content = document.querySelector('.modal_content');

    modal.style.display = 'block';

    modal_content.innerHTML = await fetchGETData('edit_task.html');

    UPDATEreminder(reminderID);

    cancel_edit.addEventListener("mouseup", () => {
        modal.style.display = 'none';
    });
}

function POSTreminder() {
    // Post Reminder
    let modal = document.querySelector('.add_sched_modal');
    const postReminder = document.querySelector('#post-reminder-button');
    postReminder.addEventListener('click', async () => {
        const form = document.querySelector('#post-reminder-form');
        const reminder = new Reminder(form, null, null);
        await reminder.post();
        modal.style.display = 'none';
    });
}

async function DELETEreminder(reminderID) {
    // Delete Reminder
    var verify = confirm('Are you sure you want to delete this task?');
    if (verify) {
        const reminder = new Reminder(null, reminderID, null);
        let parentDiv = document.getElementById('tasks_section');
        let div = document.getElementById(reminderID);
        await reminder.delete();
        parentDiv.removeChild(div);
    }
}

function CHECKreminder(reminderID, isChecked) {
    // Mark Reminder as Done
    let is_checked = isChecked == 0 ? 1 : 0;
    let verify;
    if (isChecked == 0) {
        verify = confirm('Mark this task as DONE?');
    } else {
        verify = confirm('Are you sure you want to UNSUBMIT this task?');
    }
    if (verify) {
        var req;
        const formData = new URLSearchParams();

        formData.append('reminder-id', reminderID);
        formData.append('is-checked', is_checked);

        var data = {
            method: 'POST',
            body: formData
        };

        req = new Request('../../api/set_is_checked.php', data);

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    }
}
function UPDATEreminder(button) {
    let modal = document.querySelector('.add_sched_modal');
    // Edit Reminder
    const editReminder = document.querySelector('#edit-reminder-button');
    // Get fields
    const reminder_id = document.querySelector("input[name='reminder-id']");
    const reminder_type = document.querySelector('#edit-reminder-type-option');
    const title = document.querySelector("input[name='title']");
    const remind_date = document.querySelector("input[name='remind-date']");
    const message = document.querySelector("textarea[name='message']");

    // Assign values to fields
    reminder_id.value = button.getAttribute('reminderID');
    document.querySelector("input[name='reminder-id-field']").value = button.getAttribute('reminderID');

    title.value = button.getAttribute('title');
    if (button.getAttribute('remindDate')) {
        var d = new Date(button.getAttribute('remindDate'));
        remind_date.value = `${d.getFullYear()}-${d.getMonth()}-${d.getDate().toString().length <= 1 ? '0' + d.getDate() : d.getDate()}`;
    }
    if (button.getAttribute('message')) {
        message.value = button.getAttribute('message');
    }

    // Confirm edit
    editReminder.addEventListener('click', async () => {
        const form = document.querySelector("#edit-reminder-form");
        const reminder = new Reminder(form, null, null);
        await reminder.update();
        modal.style.display = 'none';
    });
}