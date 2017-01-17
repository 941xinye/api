'use strict';

import Base from './base.js';

export default class extends Base {
  /**
   * index action
   * @return {Promise} []
   */
  indexAction(){
    //auto render template file index_index.html
    return this.display();
  }
  async ttAction(){
  	let model = this.model("students");
    let data =  await model.getStudentParentsMeetingVideoLog(this.get('student_id'));

    return this.success(data);
  }
}