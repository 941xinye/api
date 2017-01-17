'use strict';
/**
 * model
 */
export default class extends think.model.base {
  tableName="students"
  
  /**
  * 获取学员家长会视频列表
  */
  async getStudentParentsMeetingVideoLog(student_id){
      let data = await this.alias('s')
      .join({table:'student_parents_meeting',join:'left',as:'c',on:['id','student_id']})
      .join({table:'student_parents_meeting_video_log',join:'left',as:'l',on:['c.id','l.pm_id']})
      .where({"s.id": student_id})
      .field(["c.student_id","c.type","l.title",'l.url'])
      .select();
      return data;
  }
}
