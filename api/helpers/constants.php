<?php
define("TEST_MODE", false);

// Sources and types
define("SOURCE_GOOGLE_CLASSROOM", "GCLASS");
define("SOURCE_CANVAS", "CANVAS");
define("SOURCE_USER", "USER");
define("TYPE_COURSEWORK", "COURSEWORK");
define("TYPE_ASSIGNMENT", "ASSIGNMENT");
define("TYPE_QUIZ", "QUIZ");
define("TYPE_REMINDER", "RMDR");
define("TYPE_TASK", "TASK");

// For Sessions
define("USER_ID", "user_id");
define("MESSAGES", "messages");
define("ERRORS", "errors");

// Error Messages
define("ERROR_CANVAS_TOKEN_NOT_SET", "Canvas Access Token is not set for the current user.");
define("ERROR_GOOGLE_TOKEN_NOT_SET", "Google Access Token is not set for the current user.");
define("ERROR_MISSING_LOGGED_IN_USER", "User is not currently logged in.");
define("ERROR_MISSING_COURSE_ID", "Course ID missing or cannot be found.");
define("ERROR_MISSING_COURSEWORK_ID", "Coursework ID missing or cannot be found.");
define("ERROR_MISSING_ANNOUNCEMENT_ID", "Announcement ID missing or cannot be found.");
define("ERROR_MISSING_REMINDER_ID", "Task or Reminder ID missing or cannot be found.");
define("ERROR_MISSING_VALUE", "Value missing or cannot be found");
define("ERROR_INVALID_ACCESS", "Currently logged in user cannot access this resource.");
define("ERROR_INVALID_VALUE", "Value invalid");