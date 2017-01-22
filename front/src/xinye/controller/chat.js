'use strict';

import Base from './base.js';

var usernames = {};
var numUsers = 0;
var socketids = {};
var rooms = {};
export default class extends Base {
  /**
   * login action
   * @return {Promise} []
   */
  indexAction(){
    let user = this.session("user");
    this.assign('user',user);
    let room_id = this.get('room_id');
    let room_name = this.get('room_name');

    if(think.isEmpty(room_id)){
    	room_id = '';
    }
    if(think.isEmpty(room_name)){
    	room_name = '大厅';
    }
    if(room_id!=''){
    	rooms[room_id] = room_name;
    }
    this.assign('room_id',room_id);
    return this.display();
  }

  openAction(self){
    var socket = self.http.socket;
    this.emit("connected", "connected");
  }

  closeAction(self){
    var socket = self.http.socket;
    // remove the username from global usernames list
    if (socket.username) {
      delete usernames[socket.username];
      socket.leave(socket.room_id);
      --numUsers;
      // echo globally that this client has left
      this.broadcast('close', {
        username: socket.username,
        numUsers: numUsers
      });
    }
  }

  adduserAction(self){
  	var socket = self.http.socket;
  	var data = self.http.data;
  	var room_id = data.room_id;
  	var username = data.username;
  	socket.join(room_id);
  	socket.room_id = room_id;
    // add the client's username to the global list
    socket.username = username;
    console.log(socketids);
    if(!think.isEmpty(socketids[username])){
    	this.http.io.sockets.sockets[socketids[username]].emit("displacement");//顶号
    	delete socketids[username];
    }
    usernames[username] = username;
    socketids[username] = socket.id;
	    ++numUsers;
	    this.emit('lg', {
	      numUsers: numUsers,
	      username: username
	    });
  }

  chatAction(self){
  	var socket = self.http.socket;
  	// console.log(socket);
  	// console.log(this.http.io.sockets.sockets);
  	// console.log(this.http.io.sockets.clients('3'));
    // we tell the client to execute 'chat'
    this.broadcast('chat', {
	      username: socket.username,
	      message: self.http.data
	    });
  }
}