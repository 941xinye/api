'use strict';

export default class extends think.service.base {
  /**
   * init
   * @return {}         []
   */
  init(...args){
    super.init(...args);
  }
  async auth(username,password)
  {
    let model = this.model("users");
    let identify =  await model.login(username,password);
    return this.releaseToken(identify);
  }
  /**
   * 生成token
   */
  async releaseToken(identify)
  {
    if(identify!=false)
    {
      let data = think.utils.sha1(identify);
      // await this.session("token",data);
      identify["token"] = data;
      return identify;
    }
    return false;
  }
  /**
   * 检测token是否有效
   */
  async checkToken(token)
  {
    return false;
  }
}
