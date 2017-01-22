'use strict';
/**
 * model
 */
export default class extends think.model.base {
  tableName="room"
  
  /**
   * 根据房间类型获取房间列表
   * @param type 用户名
   * @return {Rooms}
   */
  async roomsByType(type){
    var where = {'type':type,'is_delete':0};
    if(type==''){
      where = {'is_delete':0};
    }
    let res = await this.where(where).field("id,name,type,num,read_num").order({'type':'asc','id':'desc'}).select();
    if(think.isEmpty(res)){
      return false;
    }
    return res;
  }

  async createRoomByType(name,type){
    var timestamp = Number(new Date().getTime()/1000);
    let insertId = await this.add({name:name,type: type, created: timestamp});
  }
}
