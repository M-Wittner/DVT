<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 201820182018-0202-2121 0808:0202:0101 --> 404 Page Not Found: Pages/tasks
ERROR - 201820182018-0202-2121 0808:0202:3939 --> 404 Page Not Found: Pages/tasks
ERROR - 201820182018-0202-2121 0808:0202:1010 --> 404 Page Not Found: Pages/tasks
ERROR - 201820182018-0202-2121 0909:0202:4444 --> Query error: Table 'dvt_60g_web.task_comments_view' doesn't exist - Invalid query: SELECT *
FROM `task_comments_view`
WHERE `task_id` = 1
ERROR - 201820182018-0202-2121 0909:0202:4646 --> Severity: error --> Exception: Call to undefined method CI_DB_mysqli_result::results() C:\xampp\htdocs\application\controllers\tasks.php 43
ERROR - 201820182018-0202-2121 1212:0202:5252 --> Severity: error --> Exception: syntax error, unexpected '$res' (T_VARIABLE) C:\xampp\htdocs\application\controllers\tasks.php 51
ERROR - 201820182018-0202-2121 1313:0202:1616 --> Query error: Unknown column 'status' in 'field list' - Invalid query: SELECT `status`
FROM `task_status`
WHERE `id` = '4'
ERROR - 201820182018-0202-2121 1313:0202:1212 --> Severity: Notice --> Undefined property: stdClass::$status C:\xampp\htdocs\application\controllers\tasks.php 63
ERROR - 201820182018-0202-2121 1313:0202:1212 --> Query error: Cannot add or update a child row: a foreign key constraint fails (`dvt_60g_web`.`tasks`, CONSTRAINT `task_status` FOREIGN KEY (`status_id`) REFERENCES `task_status` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION) - Invalid query: UPDATE `tasks` SET `status_id` = NULL
WHERE `id` = '1'
ERROR - 201820182018-0202-2121 1515:0202:5050 --> Severity: error --> Exception: syntax error, unexpected '$this' (T_VARIABLE) C:\xampp\htdocs\application\controllers\tasks.php 55
ERROR - 201820182018-0202-2121 1515:0202:4545 --> Query error: Cannot delete or update a parent row: a foreign key constraint fails (`dvt_60g_web`.`task_comments`, CONSTRAINT `task_id` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION) - Invalid query: DELETE FROM `tasks`
WHERE `id` = 1
ERROR - 201820182018-0202-2121 1515:0202:5959 --> Query error: Cannot delete or update a parent row: a foreign key constraint fails (`dvt_60g_web`.`task_comments`, CONSTRAINT `task_id` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION) - Invalid query: DELETE FROM `tasks`
WHERE `id` = 1
