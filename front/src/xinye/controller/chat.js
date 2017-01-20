'use strict';

import Base from './base.js';

var usernames = {};
var numUsers = 0;
var socketids = {};
export default class extends Base {
  /**
   * login action
   * @return {Promise} []
   */
  indexAction(){
    let user = this.session("user");
    this.assign('user',user);
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
  	var username = self.http.data;
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
  	console.log(socketids);
    // we tell the client to execute 'chat'
    this.broadcast('chat', {
	      username: socket.username,
	      message: self.http.data
	    });
  }
}