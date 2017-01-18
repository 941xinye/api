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
       let user = await this.session("user");
       if(!think.isEmpty(user)){
         let token = user.token;
         if(!token)
         {
            this.redirect('/xinye/user/login');
           // this.error("TOKEN_VALID");
         }
       }else{
         this.redirect('/xinye/user/login');
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