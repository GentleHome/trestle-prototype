<?php

function get_canvas_assignments($course_id, $course_name, $headers, $source)
{
    $courseworks = [];
    $response = Requests::get('https://canvas.instructure.com/api/v1/courses/' . $course_id . '/assignments', $headers);
    $courseworks_response = json_decode($response->body);
    foreach ($courseworks_response as $coursework) {
        array_push($courseworks, parse_coursework($coursework, $source, $course_name, null, null, null));
    }
    return $courseworks;
}

function get_google_assignments($course_id, $course_name, $service, $source)
{
    $courseworks = [];
    $response = $service->courses_courseWork;
    foreach ($response->listCoursesCourseWork($course_id) as $coursework) {
        array_push($courseworks, parse_coursework($coursework, $source, $course_name, $service, $course_id, $coursework["id"]));
    }
    return $courseworks;
}

function get_canvas_announcements($course_id, $course_name, $headers, $source)
{
    $announcements = [];
    $response = Requests::get('https://canvas.instructure.com/api/v1/announcements?context_codes[]=course_' . $course_id, $headers);
    $announcements_response = json_decode($response->body);
    foreach ($announcements_response as $announcement) {
        array_push($announcements, parse_announcements($announcement, $course_name, $course_id, $source));
    }
    return $announcements;
}

function get_google_announcements($course_id, $course_name, $service, $source)
{
    $announcements = [];
    $response = $service->courses_announcements;
    foreach ($response->listCoursesAnnouncements($course_id) as $announcement) {
        array_push($announcements, parse_announcements($announcement, $course_name, $course_id, $source));
    }
    return $announcements;
}

function parse_coursework($object, $source, $course_name, $service, $google_courseId, $google_courseworkId)
{
    $coursework = [];
    if ($source === SOURCE_GOOGLE_CLASSROOM) {
        $coursework["type"]         = "COURSEWORK";
        $coursework["source"]       = SOURCE_GOOGLE_CLASSROOM;
        $coursework["courseName"]   = $course_name;
        $coursework["id"]           = (int)$object["id"];
        $coursework["courseId"]     = (int)$object["courseId"];
        $coursework["title"]        = $object["title"];
        $coursework["description"]  = $object["description"];
        $coursework["datePosted"]   = $object["creationTime"];
        $coursework["dueDate"]      = $object["dueDate"];
        $coursework["unlockDate"]   = null;
        $coursework["link"]         = $object["alternateLink"];
        $coursework["isQuiz"]       = (bool)false;

        $studentSubmissions = $service->courses_courseWork_studentSubmissions;
        foreach ($studentSubmissions->listCoursesCourseWorkStudentSubmissions($google_courseId, $google_courseworkId)  as $submissions) {
            if ($submissions->getState() == 'CREATED' || $submissions->getState() == 'RECLAIMED_BY_STUDENT') {
                $coursework["hasSubmitted"] = (bool) false;
            } else {
                $coursework["hasSubmitted"] = (bool) true;
            }
        }
    }

    if ($source === SOURCE_CANVAS) {
        $coursework["type"]         = "COURSEWORK";
        $coursework["source"]       = SOURCE_CANVAS;
        $coursework["courseName"]   = $course_name;
        $coursework["id"]           = (int)$object->id;
        $coursework["courseId"]     = (int)$object->course_id;
        $coursework["title"]        = $object->name;
        $coursework["description"]  = $object->description;
        $coursework["datePosted"]   = $object->created_at;
        $dueDate = $object->due_at;
        if ($dueDate === null) {
            $coursework["dueDate"]  = $object->due_at;
        } else {
            $coursework["dueDate"]  = date_parse($object->due_at);
        }

        $coursework["unlockDate"]   = $object->unlock_at;
        $coursework["link"]         = $object->html_url;
        $coursework["isQuiz"]       = (bool)$object->is_quiz_assignment;
        $coursework["hasSubmitted"] = (bool) $object->has_submitted_submissions;
    }


    return $coursework;
}

function parse_announcements($object, $course_name, $course_id, $source)
{
    $announcement = [];
    if ($source === SOURCE_GOOGLE_CLASSROOM) {
        $announcement["type"]       = "ANNOUNCEMENT";
        $announcement["source"]     = SOURCE_GOOGLE_CLASSROOM;
        $announcement["courseName"] = $course_name;
        $announcement["id"]         = $object["id"];
        $announcement["courseId"]   = $course_id;
        $announcement["title"]      = null;
        $announcement["message"]    = $object["text"];
        $announcement["link"]       = $object["alternateLink"];
        $announcement["datePosted"] = $object["creationTime"];
    }

    if ($source === SOURCE_CANVAS) {
        $announcement["type"]       = "ANNOUNCEMENT";
        $announcement["source"]     = SOURCE_CANVAS;
        $announcement["courseName"] = $course_name;
        $announcement["id"]         = $object->id;
        $announcement["courseId"]   = $course_id;
        $announcement["title"]      = $object->title;
        $announcement["message"]    = $object->message;
        $announcement["link"]       = $object->html_url;
        $announcement["datePosted"] = $object->posted_at;
    }
    return $announcement;
}
