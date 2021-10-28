<?php
// dummy class resembling data gotten from gclassroom api
    class CourseWork {
        // code goes here...
      }
    $collection = [];
    $courseWork = new CourseWork;
    $courseWork->courseName = 'sample course name';
    $courseWork->title = 'Sample Title';
    $courseWork->dueDate = array('day'=>'25', 'month'=>'10', 'year'=>'2021');
    $courseWork->dueTime = array('hours'=>'12', 'minutes'=>'00');
    $courseWork->description = 'kineme';
    $courseWork->alternateLink = 'hatdogdotcom'; 
    array_push($collection, $courseWork);
    $courseWork = new CourseWork;
    $courseWork->courseName = 'sample course name 2';
    $courseWork->title = 'Sample Title2';
    $courseWork->dueDate = array('day'=>'25', 'month'=>'10', 'year'=>'2021');
    $courseWork->dueTime = array('hours'=>'00', 'minutes'=>'00');
    $courseWork->description = 'eme eme';
    $courseWork->alternateLink = 'hatdogdotcom'; 
    array_push($collection, $courseWork);
    echo json_encode($collection);
?>