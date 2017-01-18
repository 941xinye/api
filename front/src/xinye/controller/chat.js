'use strict';

import Base from './base.js';

var usernames = {};
var numUsers = 0;

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
    if(!think.isEmpty(usernames[username])){
    	var sk = thinkCache(thinkCache.WEBSOCKET);
    	for(var s in sk){
		   if(s.username==username){
		    	s.emit("disconnect");	
		    	break;
		    }
		}
    }
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
    // we tell the client to execute 'chat'
    this.broadcast('chat', {
      username: socket.username,
      message: self.http.data
    });
  }
}