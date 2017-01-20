// import adapter from "socket.io-redis";
// import redis from "redis";

// const pub = redis.createClient(7380, '115.28.241.202', { auth_pass: '6da192c7dd56a5ba917c59d2e72nneo2' });
// const sub = redis.createClient(7380, '115.28.241.202', { return_buffers: true, auth_pass: '6da192c7dd56a5ba917c59d2e72nneo2' });

export default {
  on: true, //是否开启 WebSocket
  type: "socket.io", //使用的 WebSocket 库类型，默认为 socket.io
  allow_origin: "", //允许的 origin
  adp: undefined, // socket 存储的 adapter，socket.io 下使用
  path: "", //url path for websocket
  messages: {	
	  open: 'xinye/chat/open',					// WebSocket 建立连接时处理的 Action
    close: 'xinye/chat/close',					// WebSocket 关闭时处理的 Action
    adduser: 'xinye/chat/adduser',
    chat: 'xinye/chat/chat'
  }
  // adapter: {
  //   "socket.io": {
  //     adp: function(){
  //       return adapter({ 
  //         pubClient: pub, subClient: sub 
  //       });
  //     }
  //   }
  // }
};