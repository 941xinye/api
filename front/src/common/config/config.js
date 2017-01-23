'use strict';
/**
 * config
 */
export default {
  port: 8099, 
  host: '127.0.0.1',
  auto_reload: true,
  log_request: true,
  gc: {
    on: false
  },
  error: {
    detail: true
  },
  deny_module_list: ["home"],
  default_module: "xinye", //默认模块
  default_controller: "chat",  //默认的控制器
  default_action: "index", //默认的 Action
};
