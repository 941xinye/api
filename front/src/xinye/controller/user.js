'use strict';

import Base from './base.js';

export default class extends Base {
  /**
   * login action
   * @return {Promise} []
   */
  async loginAction(){
    //auto render template file index_index.html
    this.assign('title','用户登录');
    this.assign('navType','login');
    let csrf = await this.session("__CSRF__");
    this.assign('csrf',csrf);
    return this.display();
  }

  /**
   * loginvalid action
   * @return {Promise} []
   */
  async loginvalidAction(){
    let model = this.model("users");
    let data =  await model.login(this.post('mobile'),this.post('password'));
    if(data!=false){
      await this.session("user",data);
      var tokens = await this.session("user_token");
      if(!think.isEmpty(tokens)){
        tokens[data.mem_id] = data.token;
      }else{
        tokens = {};
        tokens[data.mem_id] = data.token;
      }
      await this.session("user_token",tokens);
      return this.success(data);
    }
    return this.fail(601,'用户名密码错误',data);
  }
}