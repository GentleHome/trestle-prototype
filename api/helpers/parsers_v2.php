<?php

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
        $coursework["datePosted"]   = date_parse($object["creationTime"]);
        $dueDate = $object["dueDate"];
        if ($dueDate === null) {
            $coursework["dueDate"]      =  $object["dueDate"];
        } else {
            $coursework["dueDate"]      =  (object) array_merge((array) $object["dueDate"], (array) $object["dueTime"]);
        }
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
        $coursework["datePosted"]   = date_parse($object->created_at);
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
        $announcement["datePosted"] = date_parse($object["creationTime"]);
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
        $announcement["datePosted"] = date_parse($object->posted_at);
    }
    return $announcement;
}
