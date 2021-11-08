var collection = [];

arrangeData();

async function getCourseWorks() {
    const response = await fetch('../process/get_announcements.php');
    const data = await response.text();
    let data_parse = await JSON.parse(data);

    collection = await data_parse;
    console.log(collection);
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

    await getCourseWorks();
    if (collection) {
        for (let x = 0; x < collection.length; x++) {
            let announcements = collection[x];
            switch (announcements.source) {
                case "CANVAS":
                    image = CANVAS;
                    break;

                case "GCLASS":
                    image = GCLASS;
                    break;
            }

            title = announcements.title == null ? "" : announcements.title;
            message = announcements.message;
            date_posted = announcements.datePosted;
            link = announcements.link
            notificationSection.innerHTML += notification_section(image, title, message, date_posted, link);
        }
    }
}

function notification_section(image, title, message, date_posted, link) {
    let html = "";

    html += '<div class="notification">';
    html += '<img src="' + image + '"" alt = "icon" width = "50" class="source_image"> ';
    html += '<div class="contents">';
    html += '<span class="title">' + title + '</span>';
    html += '<span class="datePosted">' + date_posted + '</span>';
    html += '<div class="message">' + message + '</div>';
    html += '<br><a href="' + link + '">Open in site..</a>'
    html += '</div>';
    html += '</div>';

    return html;
}