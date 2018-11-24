<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 201820182018-1111-2222 1818:NovNov:1515 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:3737 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:5050 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:0101 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:0202 --> 404 Page Not Found: Faviconico/index
ERROR - 201820182018-1111-2222 1818:NovNov:3838 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:2323 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:0404 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:3737 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:4040 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '.`chips` `ch_m` ON `ch_m`.`chip_id` = `top`.`chip_m_id`
														JOIN `sta' at line 20 - Invalid query: SELECT 
														`top`.`test_id` AS `operation_id`,
														`top`.`date_time` AS `start_date`,
														`tt`.`type_idx` AS `test_type_id`,
														`t1`.`plan_id` AS `plan_id`,
														`t1`.`test_id` AS `test_id`,
														`tt`.`test_name` AS `test_name`,
														`ws`.`idx` AS `work_station_id`,        
														`ws`.`name` AS `work_station`,
														`top`.`chip_m_id` AS `chip_m_id`,
														`ch_m`.`chip_sn` AS `chip_m_sn`,
														`top`.`chip_r_id` AS `chip_r_id`,
														`ch_r`.`chip_sn` AS `chip_r_sn`,
														`top`.`operator` AS `user`,
														`top`.`test_status` AS `status`,
														`statuses`.`status` AS `status`
												FROM
														(Select * from `dvt_60g`.`test_operation` WHERE (date_time between DATE_SUB(now(), interval 6 MONTH) and now())) `top`
														JOIN `dvt_60g`.`chips` `ch_r` ON `ch_r`.`chip_id` = `top`.`chip_r_id`
														JOIN `dvt_60g`.`dvt_60g`.`chips` `ch_m` ON `ch_m`.`chip_id` = `top`.`chip_m_id`
														JOIN `statuses` ON `top`.`test_status` = `statuses`.`test_status`
														JOIN `dvt_60g`.`test_types` `tt` ON `tt`.`test_name` = `top`.`test_type`
														JOIN `dvt_60g`.`work_stations` `ws` ON `ws`.`name` = `top`.`work_station`
														JOIN `dvt_60g_web`.`test_v1` `t1`ON `top`.`plan_id` = t1.test_id
														order by start_date desc
ERROR - 201820182018-1111-2222 1818:NovNov:5757 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:5959 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '.chips ch_m ON ch_m.chip_id = top.chip_m_id
														JOIN statuses ON top.' at line 20 - Invalid query: SELECT 
														top.test_id AS operation_id,
														top.date_time AS start_date,
														tt.type_idx AS test_type_id,
														t1.plan_id AS plan_id,
														t1.test_id AS test_id,
														tt.test_name AS test_name,
														ws.idx AS work_station_id,        
														ws.name AS work_station,
														top.chip_m_id AS chip_m_id,
														ch_m.chip_sn AS chip_m_sn,
														top.chip_r_id AS chip_r_id,
														ch_r.chip_sn AS chip_r_sn,
														top.operator AS user,
														top.test_status AS status,
														statuses.status AS status
												FROM
														(Select * from dvt_60g.test_operation WHERE (date_time between DATE_SUB(now(), interval 6 MONTH) and now())) top
														JOIN dvt_60g.chips ch_r ON ch_r.chip_id = top.chip_r_id
														JOIN dvt_60g.dvt_60g.chips ch_m ON ch_m.chip_id = top.chip_m_id
														JOIN statuses ON top.test_status = statuses.test_status
														JOIN dvt_60g.test_types tt ON tt.test_name = top.test_type
														JOIN dvt_60g.work_stations ws ON ws.name = top.work_station
														JOIN dvt_60g_web.test_v1 t1ON top.plan_id = t1.test_id
														order by start_date desc
ERROR - 201820182018-1111-2222 1818:NovNov:3737 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:3939 --> Severity: Parsing Error --> syntax error, unexpected 'echo' (T_ECHO) C:\xampp\htdocs\application\controllers\admin.php 154
ERROR - 201820182018-1111-2222 1818:NovNov:5151 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:5353 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '.chips as ch_m ON ch_m.chip_id = top.chip_m_id
														JOIN statuses ON t' at line 20 - Invalid query: SELECT 
														top.test_id AS operation_id,
														top.date_time AS start_date,
														tt.type_idx AS test_type_id,
														t1.plan_id AS plan_id,
														t1.test_id AS test_id,
														tt.test_name AS test_name,
														ws.idx AS work_station_id,        
														ws.name AS work_station,
														top.chip_m_id AS chip_m_id,
														ch_m.chip_sn AS chip_m_sn,
														top.chip_r_id AS chip_r_id,
														ch_r.chip_sn AS chip_r_sn,
														top.operator AS user,
														top.test_status AS status,
														statuses.status AS status
												FROM
														(Select * from dvt_60g.test_operation WHERE (date_time between DATE_SUB(now(), interval 6 MONTH) and now())) as top
														JOIN dvt_60g.chips as ch_r ON ch_r.chip_id = top.chip_r_id
														JOIN dvt_60g.dvt_60g.chips as ch_m ON ch_m.chip_id = top.chip_m_id
														JOIN statuses ON top.test_status = statuses.test_status
														JOIN dvt_60g.test_types as tt ON tt.test_name = top.test_type
														JOIN dvt_60g.work_stations as ws ON ws.name = top.work_station
														JOIN dvt_60g_web.test_v1 as t1 ON top.plan_id = t1.test_id
														order by start_date desc
ERROR - 201820182018-1111-2222 1818:NovNov:3636 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:3939 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'from dvt_60g.test_operation as top
														JOIN dvt_60g.chips as ch_r ON ' at line 18 - Invalid query: SELECT 
														top.test_id AS operation_id,
														top.date_time AS start_date,
														tt.type_idx AS test_type_id,
														t1.plan_id AS plan_id,
														t1.test_id AS test_id,
														tt.test_name AS test_name,
														ws.idx AS work_station_id,        
														ws.name AS work_station,
														top.chip_m_id AS chip_m_id,
														ch_m.chip_sn AS chip_m_sn,
														top.chip_r_id AS chip_r_id,
														ch_r.chip_sn AS chip_r_sn,
														top.operator AS user,
														top.test_status AS status,
														statuses.status AS status
												FROM
														from dvt_60g.test_operation as top
														JOIN dvt_60g.chips as ch_r ON ch_r.chip_id = top.chip_r_id
														JOIN dvt_60g.dvt_60g.chips as ch_m ON ch_m.chip_id = top.chip_m_id
														JOIN statuses ON top.test_status = statuses.test_status
														JOIN dvt_60g.test_types as tt ON tt.test_name = top.test_type
														JOIN dvt_60g.work_stations as ws ON ws.name = top.work_station
														JOIN dvt_60g_web.test_v1 as t1 ON top.plan_id = t1.test_id
														order by start_date desc
ERROR - 201820182018-1111-2222 1818:NovNov:2828 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:3030 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '.chips as ch_m ON ch_m.chip_id = top.chip_m_id
														JOIN statuses ON t' at line 20 - Invalid query: SELECT 
														top.test_id AS operation_id,
														top.date_time AS start_date,
														tt.type_idx AS test_type_id,
														t1.plan_id AS plan_id,
														t1.test_id AS test_id,
														tt.test_name AS test_name,
														ws.idx AS work_station_id,        
														ws.name AS work_station,
														top.chip_m_id AS chip_m_id,
														ch_m.chip_sn AS chip_m_sn,
														top.chip_r_id AS chip_r_id,
														ch_r.chip_sn AS chip_r_sn,
														top.operator AS user,
														top.test_status AS status,
														statuses.status AS status
												FROM
														(Select * from dvt_60g.test_operation WHERE (date_time between DATE_SUB(now(), interval 6 MONTH) and now())) as top
														JOIN dvt_60g.chips as `ch_r` ON `ch_r`.chip_id = top.chip_r_id
														JOIN dvt_60g.dvt_60g.chips as ch_m ON ch_m.chip_id = top.chip_m_id
														JOIN statuses ON top.test_status = statuses.test_status
														JOIN dvt_60g.test_types as tt ON tt.test_name = top.test_type
														JOIN dvt_60g.work_stations as ws ON ws.name = top.work_station
														JOIN dvt_60g_web.test_v1 as t1 ON top.plan_id = t1.test_id
														order by start_date desc
ERROR - 201820182018-1111-2222 1818:NovNov:3434 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1818:NovNov:3737 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '.chips as `ch_m` ON `ch_m`.chip_id = `top`.chip_m_id
														JOIN statuse' at line 20 - Invalid query: SELECT 
														top.test_id AS operation_id,
														top.date_time AS start_date,
														tt.type_idx AS test_type_id,
														t1.plan_id AS plan_id,
														t1.test_id AS test_id,
														tt.test_name AS test_name,
														ws.idx AS work_station_id,        
														ws.name AS work_station,
														top.chip_m_id AS chip_m_id,
														ch_m.chip_sn AS chip_m_sn,
														top.chip_r_id AS chip_r_id,
														ch_r.chip_sn AS chip_r_sn,
														top.operator AS user,
														top.test_status AS status,
														statuses.status AS status
												FROM
														(Select * from dvt_60g.test_operation WHERE (date_time between DATE_SUB(now(), interval 6 MONTH) and now())) as top
														JOIN dvt_60g.chips as `ch_r` ON `ch_r`.chip_id = `top`.chip_r_id
														JOIN dvt_60g.dvt_60g.chips as `ch_m` ON `ch_m`.chip_id = `top`.chip_m_id
														JOIN statuses ON top.test_status = statuses.test_status
														JOIN dvt_60g.test_types as `tt` ON `tt`.test_name = `top`.test_type
														JOIN dvt_60g.work_stations as `ws` ON `ws`.name = `top`.work_station
														JOIN dvt_60g_web.test_v1 as `t1` ON `top`.plan_id = `t1`.test_id
														order by start_date desc
ERROR - 201820182018-1111-2222 1818:NovNov:0505 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:5050 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:2525 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:5555 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:1818 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:5555 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:1414 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:4747 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:2626 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:5454 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:0505 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2222 1919:NovNov:3939 --> 404 Page Not Found: Assets/lib
