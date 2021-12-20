document.addEventListener('DOMContentLoaded', () => {
    loadData();
});

const dataFetch = new DataFetch(); //create instance of DataFetch()

let url = new Update();
url.checkState();

// these category holders will change throughout async loading
// especially category_reminder with its crud
let category_reminder = [];
let category_canvas = [];
let category_google = [];

// collection holds everything
let collection;

async function loadData() {
    calendar(); // build the calendar
    await reminders();
    await canvas();
    await google();
}

// reminders function is reusable whenver we add, update, or delete a reminder
async function reminders() { // fetch reminders
    dataFetch.endpoint = '../../api/get_reminders.php?type=ALL';
    category_reminder = await dataFetch.fetching();

    // checks if no data is fetched will return
    if (category_reminder == undefined || category_reminder.length <= 0) {
        category_reminder = []; // bypassing the undefined value
    }

    collection = [...category_reminder, ...category_canvas, ...category_google];
    renderer() // call the renderer
}

async function canvas() { // fetch canvas courseworks
    dataFetch.endpoint = '../../api/get_all_canvas_courseworks.php';
    category_canvas = await dataFetch.fetching();

    // checks if no data is fetched will return
    if (category_canvas === undefined || category_canvas.length <= 0) {
        return
    }

    collection = [...category_reminder, ...category_canvas, ...category_google];
    renderer() // call the renderer
}

async function google() { // fetch google courseworks
    dataFetch.endpoint = '../../api/get_all_google_courseworks.php';
    category_google = await dataFetch.fetching();

    // checks if no data is fetched will return
    if (category_google === undefined || category_google.length <= 0) {
        return;
    }

    collection = [...category_reminder, ...category_canvas, ...category_google];
    renderer() // call the renderer
}

