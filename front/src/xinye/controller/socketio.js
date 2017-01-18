'use strict'; 

import Base from './base.js';

export default class extends Base {
  /**
   * WebSocket 建立连接时处理
   * @param  {} self []
   * @return {}      []
   */
  openAction(self){
    var socket = self.http.socket;
    this.broadcast("new message", {
      username: socket.username,
      message: self.http.data
    });
  }

  chatAction(self){
    var socket = self.http.socket;
    //广播给除当前 socket 之外的所有 sockets
    this.broadcast("new message", {msg: "message", username: "xxx"});
  }

  connectedAction(){
    var socket = self.http.socket;
    //广播给除当前 socket 之外的所有 sockets
    this.broadcast("new message", {msg: "connected", username: "xxx"},true);
  }

  closeAction(){
    var socket = self.http.socket;
    //广播给除当前 socket 之外的所有 sockets
    this.broadcast("new message", {msg: "closed", username: "xxx"},true);
  }

  adduserAction(){
    var socket = self.http.socket;
    //广播给除当前 socket 之外的所有 sockets
    this.broadcast("new message", {msg: "add", username: "xxx"},true);
  }
}