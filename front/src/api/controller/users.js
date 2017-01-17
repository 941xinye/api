'use strict';

import Base from './base.js';

export default class extends Base {
  /**
   * index action
   * @return {Promise} []
   */
  async indexAction(){
    //auto render template file index_index.html
    return this.json([1]);
  }
  async loginAction(){
    let account = this.post("account");
    let password = this.post("password");
    let LoginService = think.service("login");
    let instance = new LoginService();
    account = "admin";
    password = "zhuyongAdmin2016";
    let user = await instance.auth(account,password);
    if(user==false){return this.error("ACCOUT_OR_PASSWORD_ERR")}
    return this.success(user);
  }
}
