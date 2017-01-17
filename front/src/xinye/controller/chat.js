'use strict';

import Base from './base.js';

export default class extends Base {
  /**
   * login action
   * @return {Promise} []
   */
  async indexAction(){
    let user = this.session("user");
    this.assign('user',user);
    return this.display();
  }
}