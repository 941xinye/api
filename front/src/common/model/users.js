'use strict';
/**
 * model
 */
export default class extends think.model.base {
  tableName="user"
  /**
   * 根据用户名检测用户是否存在
   * @param account 用户名
   * @return {Promise}
   */
  async checkUserExists(account){
    let user = await this.where({"username":account}).field("id,password_hash,username,real_name,email").find();
    if(think.isEmpty(user)){
      return false;
    }
    return user;
  }




  async login(account,password){
    let user = await this.checkUserExists(account);
    if(user!=false && user["password_hash"]==password )
    {
        delete user["password_hash"];
        return user;
    }
    return false;
  }
}
