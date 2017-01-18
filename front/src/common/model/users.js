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
    if(user!=false && think.utils.md5(think.utils.md5(password)+user["salt"])==user["password"])
    {
      let token = await this.updateUserToken(user);
      user["token"] = token;
      delete user["password"];
      delete user["salt"];
      return user;
    }
    return false;
  }

  /**
   * 更新用户token
   * @param user 用户
   * @return row
   */
  async updateUserToken(user){
    let token = think.utils.sha1(user);
    let affectedRows = await this.where({mem_id: user.mem_id}).update({access_token: token});
    return token;
  }
}
