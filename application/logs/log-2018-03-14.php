<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 201820182018-0303-1414 1010:0303:0606 --> Query error: Unknown column 'RX' in 'where clause' - Invalid query: SELECT *
FROM `lineup_types`
WHERE `RX` NOT LIKE '%%' ESCAPE '!'
ERROR - 201820182018-0303-1414 1010:0303:3737 --> Severity: Notice --> Array to string conversion C:\xampp\htdocs\system\database\DB_query_builder.php 979
ERROR - 201820182018-0303-1414 1010:0303:5959 --> Query error: Unknown column 'chip_type' in 'where clause' - Invalid query: SELECT *
FROM `lineup_params`
WHERE `lineup_type` = '8'
AND `chip_type` = '2'
AND `parameter_name` NOT IN('XIF_0', 'XIF_1', 'XIF_2', 'XIF_3', 'XIF_4', 'XIF_5', 'XIF_6', 'XIF_7')
ERROR - 201820182018-0303-1414 1111:0303:5454 --> Severity: Notice --> Undefined offset: 0 C:\xampp\htdocs\application\controllers\lineups.php 35
ERROR - 201820182018-0303-1414 1111:0303:5454 --> Severity: Notice --> Trying to get property 'type_id' of non-object C:\xampp\htdocs\application\controllers\lineups.php 35
ERROR - 201820182018-0303-1414 1111:0303:5454 --> Severity: Notice --> Undefined variable: title C:\xampp\htdocs\application\controllers\lineups.php 59
ERROR - 201820182018-0303-1414 1414:0303:3636 --> Query error: Unknown column 'lineup_type' in 'where clause' - Invalid query: SELECT *
FROM `work_stations`
WHERE `lineup_type` NOT LIKE '%RX%' ESCAPE '!'
AND  `lineup_type` NOT LIKE '%General%' ESCAPE '!'
AND `id` IN(1, 2, 3)
