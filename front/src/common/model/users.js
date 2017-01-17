'use strict';
/**
 * model
 */
export default class extends think.model.base {
  tableName="members"
  
  /**
   * 根据用户名检测用户是否存在
   * @param account 用户名
   * @return {Promise}
   */
  async checkUserExists(account){
    let user = await this.where({"mem_mobile":account}).field("mem_id,mem_name,password,salt").find();
    if(think.isEmpty(user)){
      return false;
    }
    return user;
  }

  async login(account,password){
    let user = await this.checkUserExists(account);
    const utils = await require('utility');
    if(user!=false && utils.md5(utils.md5(password)+user["salt"])==user["password"])
    {
        delete user["password"];
        delete user["salt"];
        return user;
    }
    return false;
  }
}
