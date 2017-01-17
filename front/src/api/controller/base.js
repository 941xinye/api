'use strict';

export default class extends think.controller.base {
  /**
   * some base method in here
   */
   async __before()
   {
     let tokenConfig = await think.config("tokenneed");
     let noTokenList = tokenConfig["notoken"];
     if(!think.utils.has(noTokenList,this.getUri())){
       let token = this.http.header("token");
       if(!token)
       {
         this.error("TOKEN_VALID");
       }
     }
   }
   getUri(){
     return [
       this.http.module,
       this.http.controller,
       this.http.action]
       .join("/");
   }
}
