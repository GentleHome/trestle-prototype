var collection;

arrangeData();

async function getAnnouncements() {
    const response = await fetch('../../api/get_all_announcements.php');
    const data = await response.text();
    let data_parse = await JSON.parse(data);
    if ('error' in data_parse) {
        collection = null;
    } else {
        collection = await data_parse;
    }
}

async function arrangeData() {
    const CANVAS = "../static/icons/canvas_ico.png";
    const GCLASS = "../static/icons/classroom_ico.png";
    const USER = "../static/icons/user_ico.png";

    const notificationSection = document.querySelector("#notification_section");
    var image;
    var title;
    var message;
    var date_posted;
    var link;
    var course_name;

    await getAnnouncements();
    if (collection) {
        let sortedCollection = collection.sort((a, b) => { new Date(format_date_time(a.datePosted)) - new Date(format_date_time(b.datePosted)) });
        for (let x = 0; x < sortedCollection.length; x++) {
            let announcements = sortedCollection[x];
            switch (announcements.source) {
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
            title = announcements.title == null ? "" : announcements.title;
            message = announcements.message;
            date_posted = announcements.datePosted;
            link = announcements.link
            course_name = announcements.courseName
            notificationSection.innerHTML += notification_section(image, course_name, title, message, date_posted, link);
        }
    }
}

function notification_section(image, course_name, title, message, date_posted, link) {
    let html = "";
    let datePosted = format_date_time(date_posted).toLocaleString('en-US', {
        weekday: 'long',
        day: 'numeric',
        year: 'numeric',
        month: 'long',
    });
    message = message.replaceAll('\n', '<br>');
    html += '<div class="notification">';
    html += '<div class="notification-head">';
    html += '<div class="image"><img src="' + image + '"" alt = "icon" class="source_image"> </div>';
    html += '<div class="contents">';
    html += '<div class="courseName"><b>' + course_name + '</b></div>'
    html += '<div class="title">' + title + '</div>';
    html += '<div class="datePosted"><b>Date Posted:</b> ' + datePosted + '</div>';
    html += '</div>';
    html += '</div>';
    html += '<div class="message"><p>' + message + '</p></div>';
    html += '<br><div class="button-container"><button class="link"><a style="text-decoration: none; color: white;text-align: center;text-transform: uppercase;" href="' + link + '">Open in site..</a></button></div>'
    html += '</div>';
    

    return html;
}

function format_date_time(date_posted) {

    let months, days, years, hrs, mins, secs;
    months = date_posted.month;
    days = date_posted.day;
    years = date_posted.year;
    hrs = date_posted.hour;
    secs = date_posted.second;
    mins = date_posted.minute;
    if (date_posted.month <= 9) {
        months = "0" + date_posted.month;
    }
    if (date_posted.day <= 9) {
        days = "0" + date_posted.day;
    }
    if (date_posted.year <= 9) {
        years = "0" + date_posted.year;
    }
    if (date_posted.hour <= 9) {
        hrs = "0" + date_posted.hour;
    }
    if (date_posted.minute <= 9) {
        mins = "0" + date_posted.minute;
    }
    if (date_posted.second <= 9) {
        secs = "0" + date_posted.second;
    }
    let dateTime = new Date(years + "-" + months + "-" + days + "T" + hrs + ":" + mins + ":" + secs + "Z");
    return dateTime;
}
