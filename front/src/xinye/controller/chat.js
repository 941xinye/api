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
    io.socket
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
    usernames[username] = username;
	    ++numUsers;
	    this.emit('lg', {
	      numUsers: numUsers,
	      username: username
	    });
  }

  chatAction(self){
  	var socket = self.http.socket;
  	
  	// var clients = this.sockets.clients();
  	console.log(socket.id);
  	// console.log(this.http.io.sockets.sockets);
  	//console.log(this.http.io.sockets.sockets[socket.id].emit("displacement"));//顶号
    // we tell the client to execute 'chat'
    this.broadcast('chat', {
	      username: socket.username,
	      message: self.http.data
	    });
  }
}