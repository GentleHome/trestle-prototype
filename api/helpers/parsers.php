<?php

function parse_course($object, $source)
{
    $course = [];

    if ($source === SOURCE_GOOGLE_CLASSROOM) {

        $course["source"]   = SOURCE_GOOGLE_CLASSROOM;
        $course["id"]       = (int)$object->getId();
        $course["name"]     = $object->getName();
        $course["heading"]  = $object->getDescriptionHeading();
        $course["link"]     = $object->getAlternateLink();

    } else if ($source === SOURCE_CANVAS) {

        $course["source"]   = SOURCE_CANVAS;
        $course["id"]       = $object->id;
        $course["name"]     = $object->name;
        $course["heading"]  = null;
        $course["link"]     = "https://canvas.instructure.com/courses/" . $object->id;

    }

    return $course;
}

function parse_coursework($object, $source, $type)
{
    $coursework = [];

    if ($source === SOURCE_GOOGLE_CLASSROOM && $type === TYPE_COURSEWORK) {

        $coursework["source"]       = SOURCE_GOOGLE_CLASSROOM;
        $coursework["type"]         = TYPE_COURSEWORK;
        $coursework["id"]           = (int)$object["id"];
        $coursework["courseId"]     = (int)$object["courseId"];
        $coursework["title"]        = $object["title"];
        $coursework["description"]  = $object["description"];
        $coursework["datePosted"]   = $object["creationTime"];
        $coursework["dueDate"]      = $object["dueDate"];
        $coursework["unlockDate"]   = null;
        $coursework["link"]         = $object["alternateLink"];

    } else if ($source === SOURCE_CANVAS && $type === TYPE_ASSIGNMENT) {

        $coursework["source"]       = SOURCE_CANVAS;
        $coursework["type"]         = TYPE_ASSIGNMENT;
        $coursework["id"]           = (int)$object->id;
        $coursework["courseId"]     = (int)$object->course_id;
        $coursework["title"]        = $object->name;
        $coursework["description"]  = $object->description;
        $coursework["datePosted"]   = $object->created_at;
        $coursework["dueDate"]      = $object->due_at;
        $coursework["unlockDate"]   = $object->unlock_at;
        $coursework["link"]         = $object->html_url;

    } else if ($source === SOURCE_CANVAS && $type === TYPE_QUIZ) {

        $coursework["source"]       = SOURCE_CANVAS;
        $coursework["type"]         = TYPE_QUIZ;
        $coursework["id"]           = (int)$object->id;
        $coursework["courseId"]     = (int)$object->course_id;
        $coursework["title"]        = $object->title;
        $coursework["description"]  = $object->description;
        $coursework["datePosted"]   = $object->created_at;
        $coursework["dueDate"]      = $object->due_at;
        $coursework["unlockDate"]   = $object->unlock_at;
        $coursework["link"]         = $object->html_url;
    }

    return $coursework;
}

function parse_announcement($object, $source, $course_id){
    $announcement = [];

    if ($source === SOURCE_GOOGLE_CLASSROOM) {

        $announcement["source"]     = SOURCE_GOOGLE_CLASSROOM;
        $announcement["id"]         = $object["id"];
        $announcement["courseId"]   = $course_id;
        $announcement["title"]      = null;
        $announcement["message"]    = $object["text"];
        $announcement["link"]       = $object["alternateLink"];
        $announcement["datePosted"] = $object["creationTime"];

    } else if ($source === SOURCE_CANVAS) {

        $announcement["source"]     = SOURCE_CANVAS;
        $announcement["id"]         = $object->id;
        $announcement["courseId"]   = $course_id;
        $announcement["title"]      = $object->title;
        $announcement["message"]    = $object->message;
        $announcement["link"]       = $object->html_url;
        $announcement["datePosted"] = $object->posted_at;
    }

    return $announcement;
}

function parse_reminder(Reminder $object){
    $reminder = [];

    $user = $object->get_user();
    $reminder["id"] = $object->get_id();
    $reminder["userId"] = $user->get_id();
    $reminder["type"] = $object()->get_type();
    $reminder["dateCreated"] = $object()->get_date_created();
    $reminder["remindDate"] = $object()->get_remind_date();
    $reminder["targetCourse"] = $object()->get_target_course();
    $reminder["isChecked"] = $object()->get_is_checked();
    $reminder["isRecurring"] = $object()->get_is_recurring();

    return $reminder;
}