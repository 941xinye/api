'use strict';
var user_tokens = {};
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
         let model = this.model("users");
         var check = await model.checkUserToken(user);
         user_tokens = await this.session("user_token");
         if(!check)
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