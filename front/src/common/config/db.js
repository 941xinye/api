'use strict';
/**
 * db config
 * @type {Object}
 */
export default {
  type: 'mysql',
  connectionLimit:10,
  adapter: {
    mysql: {
      host: '115.28.241.202',
      port: '3306',
      database: 'restful',
      user: 'restful',
      password: 'QyInD6llb5zSxt2P',
      prefix: 'pre_',
      encoding: 'utf8'
    }
  }
};
