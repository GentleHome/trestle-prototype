var clicked_date;
var mydate;
// date proccessing
function daysInMonth(month, year) {
    return new Date(year, month + 1, 0).getDate();
}

function getDayStartOfMonth(month, year) {
    return new Date(year, month, 1).getDay();
}

// code from https://gist.github.com/markthiessen/3883242
function getWeeksInMonth(year, month) {
    const weeks = [],
        firstDate = new Date(year, month, 1),
        lastDate = new Date(year, month + 1, 0),
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

// takes year and month, feeds myweeks with the 2D array getWeeksInMonth() returns
// loops myweeks and tries to find what index is the date today and returns what row number it is in the array
function getWeekNumber(year, month, date) {
    let myweeks = getWeeksInMonth(year, month);
    for (let i = 0; i < myweeks.length; i++) {
        if (myweeks[i].includes(date)) {
            return i;
        }
    }
}

// the ugly but stable code lmao 
function getWeeksToRender() {
    let weekNumber_before = manipulate.weekNumber - 1;
    let month_before = manipulate.month;
    let weekNumber_after = manipulate.weekNumber + 1;
    let month_after = manipulate.month;
    let year_before = manipulate.year;
    let year_after = manipulate.year;
    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(manipulate.year, month_before).length - 1;
    }

    if (weekNumber_after > getWeeksInMonth(manipulate.year, month_after).length - 1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    if (manipulate.month == 0) {
        year_before -= 1;
        month_before = 11;
        weekNumber_before = getWeeksInMonth(year_before, month_before).length - 1;
    }
    if (manipulate.month == 11) {
        year_after += 1;
        month_after = 0;
        weekNumber_after = 0;
    }

    let weeks_to_render_before = getWeeksInMonth(year_before, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(manipulate.year, manipulate.month)[manipulate.weekNumber];
    let weeks_to_render_after = getWeeksInMonth(year_after, month_after)[weekNumber_after];
    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
    };
}

function getWeeksToRender_prev() {
    manipulate.weekNumber -= 1;
    let weekNumber_before = manipulate.weekNumber - 1;
    let month_before = manipulate.month;
    let weekNumber_after = manipulate.weekNumber + 1;
    let month_after = manipulate.month;
    let year_before = manipulate.year;
    let year_after = manipulate.year;

    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(manipulate.year, month_before).length - 1;
    }

    if (manipulate.weekNumber < 0) {
        manipulate.month -= 1;
        manipulate.weekNumber = getWeeksInMonth(manipulate.year, manipulate.month).length - 1;
    }

    if (weekNumber_after > getWeeksInMonth(manipulate.year, manipulate.month).length - 1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    if (manipulate.month < 0) {
        manipulate.year -= 1;
        year_before -= 1;
        manipulate.month = 11;
    }

    let weeks_to_render_before = getWeeksInMonth(year_before, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(manipulate.year, manipulate.month)[manipulate.weekNumber];
    let weeks_to_render_after = getWeeksInMonth(year_after, month_after)[weekNumber_after];

    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
    };
}

function getWeeksToRender_next() {
    manipulate.weekNumber += 1;
    let weekNumber_before = manipulate.weekNumber - 1;
    let month_before = manipulate.month;
    let weekNumber_after = manipulate.weekNumber + 1;
    let month_after = manipulate.month;
    let year_before = manipulate.year;
    let year_after = manipulate.year;

    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(manipulate.year, month_before).length - 1;
    }

    if (manipulate.weekNumber > getWeeksInMonth(manipulate.year, manipulate.month).length - 1) {
        manipulate.month += 1;
        manipulate.weekNumber = 0;
    }

    if (weekNumber_after > getWeeksInMonth(manipulate.year, manipulate.month).length - 1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    if (manipulate.month > 11) {
        manipulate.year += 1;
        year_after += 1;
        manipulate.month = 0;
    }
    let weeks_to_render_before = getWeeksInMonth(year_before, month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(manipulate.year, manipulate.month)[manipulate.weekNumber];
    let weeks_to_render_after = getWeeksInMonth(year_after, month_after)[weekNumber_after];
    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
    };
}

function getWeeksToRender_today() {
    manipulate.year = date.getFullYear();
    manipulate.month = date.getMonth();
    manipulate.weekNumber = week_number;
    let weekNumber_before = week_number - 1;
    let month_before = date.getMonth();
    let weekNumber_after = week_number + 1;
    let month_after = date.getMonth();

    if (weekNumber_before < 0) {
        month_before -= 1;
        weekNumber_before = getWeeksInMonth(date.getFullYear(), month_before).length - 1;
    }

    if (weekNumber_after > getWeeksInMonth(date.getFullYear(), month_after).length - 1) {
        month_after += 1;
        weekNumber_after = 0;
    }

    let weeks_to_render_before = getWeeksInMonth(date.getFullYear(), month_before)[weekNumber_before];
    let weeks_to_render = getWeeksInMonth(date.getFullYear(), date.getMonth())[week_number];
    let weeks_to_render_after = getWeeksInMonth(date.getFullYear(), month_after)[weekNumber_after];

    return weeks_to_render = {
        before: weeks_to_render_before,
        current: weeks_to_render,
        after: weeks_to_render_after,
    };
}

// for clicking dates in year view
function highlightstuff() {
    let year_small_box = document.querySelectorAll('.small_box');
    let year_small_box_2 = document.querySelectorAll('.small_box-2');
    year_small_box.forEach(small_box => {
        small_box.addEventListener('mouseup', setActive);
    });
    year_small_box_2.forEach(small_box_2 => {
        small_box_2.addEventListener('mouseup', setActive);
    });
}

function setActive() {
    let activated = document.querySelectorAll('.active');
    // if there are more than one .active it will remove the .active on the previously selected boxes
    if (activated.length > 0) {
        activated.forEach(active => {
            active.className = active.className.replace(" active", "");
        });
    }
    this.classList.add('active');
    let day = this.getAttribute('data-day');
    let month = this.getAttribute('data-month');
    let year = this.getAttribute('data-year');
    let mydate = new Date(year, month, day);
    document.querySelector('#date').innerHTML = mydate;
}
// fetch for getting dummy data
async function getCourseWorks() {
    const response = await fetch('../process/get_courseworks.php');
    const data = await response.text();
    let data_parse = await JSON.parse(data);

    return data_parse;
}
// clicking a date in month and week view triggers the modal that allows you to add schedule
function trigger_modal() {
    let week_box = document.querySelectorAll('.long_box');
    let month_box = document.querySelectorAll('.box');
    week_box.forEach(box => {
        box.addEventListener('mouseup', () => { addShedule_view(box) });
    });

    month_box.forEach(box => {
        box.addEventListener('mouseup', () => { addShedule_view(box) });
    });
}

async function addShedule_view(element) {
    let modal = document.querySelector('.add_sched_modal');
    let modal_content = document.querySelector('.modal_content');

    modal.style.display = 'block';

    let html = await fetchGETData('add_schedule.html');
    let parser = new DOMParser();
    let doc = parser.parseFromString(html, 'text/html');

    modal_content.innerHTML = doc.querySelector('body').innerHTML;

    let close_modal = document.querySelector('#close_modal');
    close_modal.addEventListener('mouseup', () => {
        modal.style.display = 'none';
    });

    clicked_date = element.getAttribute('data-month') + '-' + element.getAttribute('data-day') + '-' + element.getAttribute('data-year');
    mydate = new Date(clicked_date);
    // fetching data from dummy_source and putting it on html
    detailView(element)

    document.querySelector("#clicked_date").innerHTML = clicked_date;
    let start_date = document.querySelector("form[name='schedule'] input[name='start_date']");
    let end_date = document.querySelector("form[name='schedule'] input[name='end_date']");

    start_date.value = formatDate(clicked_date);
    end_date.min = start_date.value;

    start_date.addEventListener('change', () => {
        end_date.min = start_date.value;
    });

    let myform = document.querySelector("form[name='schedule']");
    let submit_btn = document.querySelector("#submit_btn");
    let formdata = new FormData(myform);
    formdata.append("date", clicked_date);

    submit_btn.addEventListener("mouseup", async () => {
        let post_response = await fetchPOSTData('../process/stubs/add_schedule.php', formdata);
        console.log(post_response);
    });
}

async function fetchGETData(src) {
    let res = await fetch(src);
    let data = await res.text();
    return data;
}

async function fetchPOSTData(src, formData) {
    let res = await fetch(src, { method: "post", body: formData })
    let data = await res.text();
    return data;
}

// will show the detail view of the tasks
async function detailView(selected) {
    var html = '';
    // for recurring reminders(user_created)
    var dayofweek = day.indexOf(selected.getAttribute('data-dayofweek'))
    if (collection) {
        for (let x = 0; x < collection.length; x++) {
            let element = collection[x];
            if (element.type == 'COURSEWORK' && element.dueDate) {
                let dueDate = element.dueDate;
                if (dueDate.day == selected.getAttribute('data-day') && dueDate.month == selected.getAttribute('data-month') && dueDate.year == selected.getAttribute('data-year')) {
                    html += `<b>Course name: ${element.courseName} </b><br>
                        <b> Assignment name: ${element.title} </b><br>
                        | Due date: ${dueDate.month} / ${dueDate.day} / ${dueDate.year} <br>
                        Description: ${element.description} <br>
                        Link: <a href="${element.link}">course work link</a> <br>
                        Source: <b> ${element.source} </b> <br><br>`
                }
            }

            if (element.type == 'TASK') {
                let d = new Date(element.remindDate.date);
                if (element.isRecurring) {
                    if (element.isRecurring == dayofweek) {
                        html += detailsHTML(element);
                    }
                } else {
                    if (d.getDate() == selected.getAttribute('data-day') && d.getMonth() + 1 == selected.getAttribute('data-month') && d.getFullYear() == selected.getAttribute('data-year')) {
                        html += detailsHTML(element);
                    }
                }
            }
        }
    }
    document.querySelector('task_preview').innerHTML = html;
    edit_delete(selected);
}
// details of user created reminder
async function detailsHTML(user_created) {
    let html = '';
    html += `<b>Target Course: ${user_created.targetCourse} </b><br>
    <b> Task Title: ${user_created.title} </b><br>
    Description: ${user_created.description}<br>`
    if (!user_created.isRecurring) {
        html += `Remind date: ${user_created.remindDate.date}<br>`
    } else {
        html += `Recurring every: ${day[user_created.isRecurring]} <br>`
    }
    html += `<button class="edit_task">edit</button>
    <button class="delete_task">delete</button> <br><br>`

    return html;
}

async function edit_schedule(selected) {
    console.log(clicked_date);
    document.querySelector('task_preview').innerHTML = await fetchGETData('edit_schedule.html');
    document.querySelector("form[name='edit_schedule'] input[name='edit_start_date']").value = formatDate(clicked_date);
    document.querySelector("form[name='edit_schedule'] input[name='edit_end_date']").min = formatDate(clicked_date);

    let edit_start_date = document.querySelector("form[name='edit_schedule'] input[name='edit_start_date']");
    let edit_end_date = document.querySelector("form[name='edit_schedule'] input[name='edit_end_date']");

    edit_start_date.value = formatDate(clicked_date);
    edit_end_date.min = edit_start_date.value;

    edit_start_date.addEventListener('change', () => {
        edit_end_date.min = edit_start_date.value;
    });

    let myform = document.querySelector("form[name='edit_schedule']");
    let edit_submit_btn = document.querySelector("#edit_submit_btn");
    let cancel_edit = document.querySelector('#cancel_edit');
    let formdata = new FormData(myform);
    formdata.append("date", clicked_date);

    edit_submit_btn.addEventListener("mouseup", async () => {
        let post_response = await fetchPOSTData('../process/stubs/edit_schedule.php', formdata)
        console.log(post_response);
        detailView(element)
    });

    cancel_edit.addEventListener("mouseup", () => {
        detailView(selected)
    });
}

function edit_delete(selected) {
    let edit_task = document.querySelectorAll('.edit_task');
    let delete_task = document.querySelectorAll('.delete_task');

    edit_task.forEach(btn => {
        btn.addEventListener('mouseup', () => { edit_schedule(selected) });
    });

    delete_task.forEach(btn => {
        btn.addEventListener('mouseup', async () => {
            console.log(await fetchGETData('../process/stubs/delete_schedule.php'));
        });
    });
}

// https://stackoverflow.com/questions/23593052/format-javascript-date-as-yyyy-mm-dd
function formatDate(date) {
    var d = new Date(date),
        month = "" + (d.getMonth() + 1),
        day = "" + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = "0" + month;
    if (day.length < 2) day = "0" + day;

    return [year, month, day].join("-");
}

// https://stackoverflow.com/questions/13898423/javascript-convert-24-hour-time-of-day-string-to-12-hour-time-with-am-pm-and-no
function timeConvert(time) {
    // Check correct time format and split into components
    time = time
        .toString()
        .match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [
            time,
        ];

    if (time.length > 1) {
        // If time format correct
        time = time.slice(1); // Remove full string match value
        time[5] = +time[0] < 12 ? "AM" : "PM"; // Set AM/PM
        time[0] = +time[0] % 12 || 12; // Adjust hours
    }
    return time.join(""); // return adjusted time or original string
}

function dotMarkers(collection, daycounter, dayOfWeek) {
    let html = "";
    if (collection) {

        for (let x = 0; x < collection.length; x++) {
            // take courseworks that have that have due dates
            let element = collection[x];
            if (element.type == 'COURSEWORK' && element.dueDate) {
                if (element.dueDate.day == daycounter &&
                    element.dueDate.year == manipulate.year &&
                    element.dueDate.month == manipulate.month + 1) {
                    if (element.source == "CANVAS") {
                        html += '<span class="dot-canvas"></span>';
                    } else {
                        html += '<span class="dot-google"></span>';
                    }
                }
            }

            // take tasks
            if (element.type == 'TASK') {
                let d = new Date(element.remindDate.date);
                // if element is recurring, will discard the remind date and just keep on
                // putting dots on the said week we want the thing to reoccur
                // else the element.isRecurring is null therefore we will prioritize the 
                // exact date to know where to put the dot
                if (element.isRecurring) {
                    // FOR 0,1
                    // if (d.getDate() <= daycounter && d.getDay() == dayOfWeek) {
                    //     html += '<span class="dot-user"></span>';
                    // }
                    // FOR 0-6
                    if (element.isRecurring == dayOfWeek) {
                        html += '<span class="dot-user"></span>';
                    }
                } else {
                    if (d.getDate() == daycounter && d.getMonth() == manipulate.month && d.getFullYear() == manipulate.year) {
                        html += '<span class="dot-user"></span>';
                    }
                }
            }
        }
        return html;
    }
    return '';
}