<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 201820182018-1111-2323 1111:NovNov:2121 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:2626 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:2020 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:2222 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:0101 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:2121 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:1717 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:1212 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:1212 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:3737 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:2828 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:4242 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:1212 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:2727 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:0707 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:1818 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:3030 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:2323 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:0606 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:3939 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:1010 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:3232 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:0303 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:5050 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1111:NovNov:2929 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:1818 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:4141 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:5959 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:0505 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:1717 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:1111 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:1313 --> Query error: Unknown column 'top.user' in 'on clause' - Invalid query: SELECT 
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
														CONCAT(u.fname, ' ', u.lname) AS user,
														top.test_status AS status,
														statuses.status AS status
												FROM
														(Select * from dvt_60g.test_operation WHERE (date_time between DATE_SUB(now(), interval 6 MONTH) and now())) as top
														JOIN dvt_60g.chips as `ch_r` ON `ch_r`.chip_id = `top`.chip_r_id
														JOIN dvt_60g.chips as `ch_m` ON `ch_m`.chip_id = `top`.chip_m_id
														JOIN dvt_60g.statuses ON top.test_status = statuses.test_status
														JOIN dvt_60g.test_types as `tt` ON `tt`.test_name = `top`.test_type
														JOIN dvt_60g.work_stations as `ws` ON `ws`.name = `top`.work_station
														JOIN dvt_60g_web.test_v1 as `t1` ON `top`.plan_id = `t1`.test_id
														JOIN dvt_60g_web.users as `u` ON `top`.user = `u`.username
														order by start_date desc
ERROR - 201820182018-1111-2323 1212:NovNov:0808 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:2222 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:0202 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:5858 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:4040 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:2929 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:1414 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:4747 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:3939 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:1919 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:4545 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:1010 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:3030 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:4444 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:3636 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:4646 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:2222 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:0404 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:4747 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:4141 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:2424 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:0303 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:1616 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 1212:NovNov:3232 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 2020:NovNov:2121 --> 404 Page Not Found: Group-templatehtml/index
ERROR - 201820182018-1111-2323 2020:NovNov:4242 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 2020:NovNov:4747 --> 404 Page Not Found: Group-templatehtml/index
ERROR - 201820182018-1111-2323 2020:NovNov:0000 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 2020:NovNov:3636 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 2020:NovNov:5252 --> 404 Page Not Found: Assets/lib
ERROR - 201820182018-1111-2323 2020:NovNov:4949 --> 404 Page Not Found: Assets/lib
