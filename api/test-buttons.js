document.addEventListener('DOMContentLoaded', () => {
    setButtons();
});

function setButtons() {

    // Get Courses
    const getCourses = document.querySelector('#get-courses-button');
    getCourses.addEventListener('click', () => {
        const textArea = document.querySelector('#get-courses-response');

        const req = new Request(
            `get_courses.php`
        );

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                // Do stuff here
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get All Courseworks
    const getAllCourseworks = document.querySelector('#get-all-courseworks-button');
    getAllCourseworks.addEventListener('click', () => {
        const textArea = document.querySelector('#get-all-courseworks-response');
        const selectedCourse = getSelectedCourse();
        var req;
        
        req = new Request(
            `get_all_courseworks.php`
        );

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get All Announcements
    const getAllAnnouncements = document.querySelector('#get-all-announcements-button');
    getAllAnnouncements.addEventListener('click', () => {
        const textArea = document.querySelector('#get-all-announcements-response');
        const selectedCourse = getSelectedCourse();
        var req;

        req = new Request(
            `get_all_announcements.php`
        );

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Course
    const getCourse = document.querySelector('#get-course-button');
    getCourse.addEventListener('click', () => {
        const textArea = document.querySelector('#get-course-response');
        const selectedCourse = getSelectedCourse();
        var req;

        req = new Request(
            `get_course.php?source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}`
        );

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Courseworks
    const getCourseworks = document.querySelector('#get-courseworks-button');
    getCourseworks.addEventListener('click', () => {
        const textArea = document.querySelector('#get-courseworks-response');
        const selectedCourse = getSelectedCourse();
        var req;

        req = new Request(
            `get_courseworks.php?source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}`
        );

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Coursework
    const getCoursework = document.querySelector('#get-coursework-button');
    getCoursework.addEventListener('click', () => {
        const textArea = document.querySelector('#get-coursework-response');
        const assignIdInput = document.querySelector('#get-coursework-id');
        const typeInput = document.querySelector('#get-coursework-type-option');
        const selectedCourse = getSelectedCourse();
        var req;

        req = new Request(
            `get_coursework.php?source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}` +
            `&coursework_id=${assignIdInput.value}` +
            `&type=${typeInput.value}`
        );

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Announcements
    const getAnnouncements = document.querySelector('#get-announcements-button');
    getAnnouncements.addEventListener('click', () => {
        const textArea = document.querySelector('#get-announcements-response');
        const selectedCourse = getSelectedCourse();
        var req;

        req = new Request(
            `get_announcements.php?source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}`
        );

       fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Reminders
    const getReminders = document.querySelector('#get-reminders-button');
    getReminders.addEventListener('click', () => {
        const textArea = document.querySelector('#get-reminders-response');
        const selected = getValueFromInput("#get-reminder-type-option");
        var req; 
        
        req = new Request(
            `get_reminders.php?type=${selected}`
        );

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Post Reminder
    const postReminder = document.querySelector('#post-reminder-button');
    postReminder.addEventListener('click', () => {
        const textArea = document.querySelector('#post-reminder-response');
        var req;

        const form = document.querySelector("#post-reminder-form");
        const formData = new URLSearchParams();

        for (const pair of new FormData(form)) {
            formData.append(pair[0], pair[1]);
        }

        var data = {
                method: 'POST',
                body: formData
        };

        req = new Request('post_reminder.php', data);

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Edit Reminder
    const editReminder = document.querySelector('#edit-reminder-button');
    editReminder.addEventListener('click', () => {
        const textArea = document.querySelector('#edit-reminder-response');
        var req;

        const form = document.querySelector("#edit-reminder-form");
        const formData = new URLSearchParams();

        for (const pair of new FormData(form)) {
            formData.append(pair[0], pair[1]);
        }

        var data = {
            method: 'POST',
            body: formData
        };

        req = new Request('edit_reminder.php', data);

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Delete Reminder
    const deleteReminders = document.querySelector('#delete-reminder-button');
    deleteReminders.addEventListener('click', () => {
        const textArea = document.querySelector('#delete-reminder-response');
        var req;

        const form = document.querySelector("#delete-reminder-form");
        const formData = new URLSearchParams();

        for (const pair of new FormData(form)) {
            formData.append(pair[0], pair[1]);
        }

        var data = {
            method: 'POST',
            body: formData
        };

        req = new Request('delete_reminder.php', data);

        fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });
}

function getSelectedCourse() {

    const courseId = document.querySelector('#get-course-id');
    const selected = getValueFromInput('#get-source-option');

    return {
        courseId: courseId.value,
        source: selected
    }
}

function getValueFromInput(id){
    const element = document.querySelector(id);
    const selected = element.value;

    return selected;
}