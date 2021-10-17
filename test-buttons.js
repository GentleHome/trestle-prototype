document.addEventListener('DOMContentLoaded', () => {
    setButtons();
});

function setButtons() {
    // Get Courses
    const getCourses = document.querySelector('#get-courses-button');
    getCourses.addEventListener('click', () => {
        const textArea = document.querySelector('#get-courses-response');

        const req = new Request(
            `get_courses.php/?canvas_token=${getCanvasToken()}`
        );

        const data = fetch(req)
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

        const req = new Request(
            `get_course.php/?canvas_token=${getCanvasToken()}` +
            `&source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}`
        );

        const data = fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Assignments
    const getAssignments = document.querySelector('#get-assignments-button');
    getAssignments.addEventListener('click', () => {
        const textArea = document.querySelector('#get-assignments-response');
        const selectedCourse = getCourseIdAndOption();

        const req = new Request(
            `get_assignments.php/?canvas_token=${getCanvasToken()}` +
            `&source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}`
        );

        const data = fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Assignment
    const getAssignment = document.querySelector('#get-assignment-button');
    getAssignment.addEventListener('click', () => {
        const textArea = document.querySelector('#get-assignment-response');
        const assignIdInput = document.querySelector('#get-assignment-id')
        const selectedCourse = getCourseIdAndOption();

        const req = new Request(
            `get_assignment.php/?canvas_token=${getCanvasToken()}` +
            `&source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}` +
            `&assignment_id=${assignIdInput.value}`
        );

        const data = fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Quizzes
    const getQuizzes = document.querySelector('#get-quizzes-button');
    getQuizzes.addEventListener('click', () => {
        const textArea = document.querySelector('#get-quizzes-response');
        const selectedCourse = getCourseIdAndOption();

        const req = new Request(
            `get_quizzes.php/?canvas_token=${getCanvasToken()}` +
            `&source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}`
        );

        const data = fetch(req)
            .then((res) => res.text())
            .then((data) => {
                textArea.value = data;
                var parsed = JSON.parse(data);
                console.log(parsed);
            });
    });

    // Get Quiz
    const getquiz = document.querySelector('#get-quiz-button');
    getquiz.addEventListener('click', () => {
        const textArea = document.querySelector('#get-quiz-response');
        const quizIdInput = document.querySelector('#get-quiz-id')
        const selectedCourse = getCourseIdAndOption();

        const req = new Request(
            `get_quiz.php/?canvas_token=${getCanvasToken()}` +
            `&source=${selectedCourse.source}` +
            `&course_id=${selectedCourse.courseId}` +
            `&quiz_id=${quizIdInput.value}`
        );

        const data = fetch(req)
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

function getCourseIdAndOption(){

    const courseId = document.querySelector('#get-course-id');
    const selected = document.querySelector('#get-course-option');

    return {
        courseId: courseId.value,
        source: selected.value
    }
}
