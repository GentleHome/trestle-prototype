<!DOCTYPE html>
<html>

<head>
    <title></title>
    <script src="test-buttons.js"></script>
</head>

<body>
    <input id='canvas-token' type="text" placeholder="Canvas Token Here">(Teacher Token: 7~eP0MHZP46nz5Y3oNNkLeCA7i4Zo5b2kojJI5V0gZeoN2RTmrNYyWIHtFI37Qou0q)</br></br>

    <!-- Get Courses -->
    <button id="get-courses-button">Get Courses</button> = <textarea id="get-courses-response" disabled placeholder="Response"></textarea></br></br>
    </br></br>
    
    <!-- Get Course -->
    <input type="text" id="get-course-id" placeholder="Course ID">
    <select id="get-source-option">
        <option selected value="CANVAS">Canvas</option>
        <option value="GCLASS">Google Classroom</option>
    </select></br></br>

    <button id="get-course-button">Get Course</button> = <textarea id="get-course-response" disabled placeholder="Response"></textarea></br></br>

    <!-- Get Courseworks -->
    <button id="get-courseworks-button">Get Courseworks</button> = <textarea id="get-courseworks-response" disabled placeholder="Response"></textarea></br></br>

    <!-- Get Coursework -->
    <select id="get-type-option">
        <option selected value="ASSIGNMENT">Assignment</option>
        <option value="COURSEWORK">Coursework</option>
        <option value="QUIZ">Quiz</option>
    </select>
    <input type="text" id="get-coursework-id" placeholder="Coursework ID">
    <button id="get-coursework-button">Get Coursework</button> = <textarea id="get-coursework-response" disabled placeholder="Response"></textarea></br></br>

    <button id="get-announcements-button">Get Announcements</button> = <textarea id="get-announcements-response" disabled placeholder="Response"></textarea></br></br>

</body>

</html>