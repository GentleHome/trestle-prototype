document.addEventListener('DOMContentLoaded', () => {
    setButtons();
});

function setButtons() {
    // Get Courses
    const getCourses = document.querySelector('#get-courses-button');
    getCourses.addEventListener('click', () => {
        const textArea = document.querySelector('#get-courses-response');

        const req = new Request(
            `get_courses.php?canvas_token=${getCanvasToken()}`
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

    // Get Course
    const getCourse = document.querySelector('#get-course-button');
    getCourse.addEventListener('click', () => {
        const textArea = document.querySelector('#get-course-response');
        const selectedCourse = getCourseIdAndOption();
        var req;
        if (selectedCourse.source == "CANVAS") {
            req = new Request(
                `get_course.php?canvas_token=${getCanvasToken()}` +
                `&source=${selectedCourse.source}` +
                `&course_id=${selectedCourse.courseId}`
            );
        }
        else if (selectedCourse.source == "GCLASS") {
            req = new Request(
                `get_course.php?source=${selectedCourse.source}` +
                `&course_id=${selectedCourse.courseId}`
            );
        }
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
        const selectedCourse = getCourseIdAndOption();
        var req;
        if (selectedCourse.source == "CANVAS") {
            req = new Request(
                `get_courseworks.php?canvas_token=${getCanvasToken()}` +
                `&source=${selectedCourse.source}` +
                `&course_id=${selectedCourse.courseId}`
            );
        }
        else if (selectedCourse.source == "GCLASS") {
            req = new Request(
                `get_courseworks.php?source=${selectedCourse.source}` +
                `&course_id=${selectedCourse.courseId}`
            );
        }

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
        const typeInput = document.querySelector('#get-type-option');
        const selectedCourse = getCourseIdAndOption();
        var req;
        if (selectedCourse.source == "CANVAS") {
            req = new Request(
                `get_coursework.php?canvas_token=${getCanvasToken()}` +
                `&source=${selectedCourse.source}` +
                `&course_id=${selectedCourse.courseId}` +
                `&coursework_id=${assignIdInput.value}` +
                `&type=${typeInput.value}`
            );
        }

        else if (selectedCourse.source == "GCLASS") {
            req = new Request(
                `get_coursework.php?source=${selectedCourse.source}` +
                `&course_id=${selectedCourse.courseId}` +
                `&coursework_id=${assignIdInput.value}` +
                `&type=${typeInput.value}`
            );
        }

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
        const selectedCourse = getCourseIdAndOption();
        var req;
        if (selectedCourse.source == "CANVAS") {
            req = new Request(
                `get_announcements.php?canvas_token=${getCanvasToken()}` +
                `&source=${selectedCourse.source}` +
                `&course_id=${selectedCourse.courseId}`
            );
        }
        else if (selectedCourse.source == "GCLASS") {
            req = new Request(
                `get_announcements.php?source=${selectedCourse.source}` +
                `&course_id=${selectedCourse.courseId}`
            );
        }

       fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

}

function getCanvasToken() {
    const canvasToken = document.querySelector('#canvas-token');
    return canvasToken.value;
}

function getCourseIdAndOption() {

    const courseId = document.querySelector('#get-course-id');
    const selected = document.querySelector('#get-source-option');

    return {
        courseId: courseId.value,
        source: selected.value
    }
}
