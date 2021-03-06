'use strict';

/**
 * session configs
 */
export default {
  name: 'xinye',
  type: 'db',
  secret: 'EAJ7VBG9',
  timeout: 24 * 3600,
  cookie: { // cookie options
    length: 32,
    httponly: true
  },
  adapter: {
    file: {
      path: think.RUNTIME_PATH + '/session',
    }
  }
};