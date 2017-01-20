'use strict';

import Base from './base.js';

export default class extends Base {
  /**
   * login action
   * @return {Promise} []
   */
  async indexAction(){
    //auto render template file index_index.html
    this.assign('title','大厅');
    let model = this.model("rooms");
    let rooms = await model.roomsByType(this.get('type'));
    console.log(rooms);
    this.assign('rooms',rooms);
    // var moment = require('moment');
    // console.log(moment().format('YYYY-MM-DD hh:mm:ss'));
    return this.display();
  }

  /**
   * create action
   * @return {Promise} []
   */
  async createAction(){
    let model = this.model("rooms");
    let data = await model.createRoomByType(this.get('name'),this.get('type'));
    return this.success(data);
  }
}