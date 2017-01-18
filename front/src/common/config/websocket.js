export default {
  on: true, //是否开启 WebSocket
  type: "socket.io", //使用的 WebSocket 库类型，默认为 socket.io
  allow_origin: "", //允许的 origin
  adp: undefined, // socket 存储的 adapter，socket.io 下使用
  path: "", //url path for websocket
  messages: {	
 //    open: 'xinye/socketio/open',					// WebSocket 建立连接时处理的 Action
 //    close: 'xinye/socketio/close',					// WebSocket 关闭时处理的 Action
 //    chat: 'xinye/socketio/chat',					// chat 事件处理的 Action
 //    typing: 'xinye/socketio/typing',				// typing 事件处理的 Action
 //    stoptyping: 'xinye/socketio/stoptyping',		// stoptyping 事件处理的 Action
	// adduser: 'xinye/socketio/adduser',				// adduser 事件处理的 Action
	// connected: 'xinye/socketio/connected',			// connected 事件处理的 Action

	open: 'xinye/chat/open',					// WebSocket 建立连接时处理的 Action
    close: 'xinye/chat/close',					// WebSocket 关闭时处理的 Action
    adduser: 'xinye/chat/adduser',
    chat: 'xinye/chat/chat'
  }
};