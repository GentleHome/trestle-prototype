var collection = [];

arrangeData();

async function getCourseWorks() {
    const response = await fetch('../process/get_courseworks.php');
    const data = await response.text();
    let data_parse = await JSON.parse(data);

    collection = await data_parse;
    console.log(collection);
}

async function arrangeData() {
    const CANVAS = "../static/icons/canvas_ico.png";
    const GCLASS = "../static/icons/classroom_ico.png";
    const USER = "../static/icons/user_ico.png";

    const tasksSection = document.querySelector("#tasks_section");
    var image;
    var title;
    var description;
    var date_posted;
    var link;
    var course_name;
    var due_date;

    await getCourseWorks();
    if (collection) {
        for (let x = 0; x < collection.length; x++) {
            let courseworks = collection[x];
            switch (courseworks.source) {
                case "CANVAS":
                    image = CANVAS;
                    break;

                case "GCLASS":
                    image = GCLASS;
                    break;

                default:
                    image = USER;
                    break;
            }

            title = courseworks.title == null ? "" : courseworks.title;
            description = courseworks.description;
            date_posted = courseworks.datePosted;
            link = courseworks.link;
            course_name = courseworks.courseName;
            due_date = courseworks.dueDate;
            tasksSection.innerHTML += notification_section(image, course_name, title, description, date_posted, due_date, link);
        }
    }
}

function notification_section(image, course_name, title, description, date_posted, due_date, link) {
    let html = "";
    let datePosted = date_posted.month + '-' + date_posted.day + '-' + date_posted.year + ' | ' +
        date_posted.hour + ':' + date_posted.minute;

    let dueDate;

    if (due_date != null) {
        let hour = "hours" in due_date ? due_date.hours : due_date.hour;
        let minute = "minutes" in due_date ? due_date.minutes : due_date.minute;

        console.log(due_date["hours"] in due_date ? due_date.hours : due_date.hour);
        dueDate = due_date.month + '-' + due_date.day + '-' + due_date.year + ' | ' +
            hour + ":" + minute;
    } else {
        dueDate = "no due date"
    }

    html += '<div class="task">';
    html += '<img src="' + image + '"" alt = "icon" width = "50" class="source_image"> ';
    html += '<div class="contents">';
    html += '<div class="courseName"><b>' + course_name + '</b></div>'
    html += '<span class="title">' + title + '</span>';
    html += '<span class="dueDate"><b> | Date posted: </b>' + datePosted + '</span>';
    html += '<span class="dueDate"><b> | Due date: </b>' + dueDate + '</span>';
    html += '<div class="description">' + description + '</div>';
    html += '<br><a href="' + link + '">Open in site..</a>'
    html += '</div>';
    html += '</div>';

    return html;
}