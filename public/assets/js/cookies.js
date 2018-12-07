function lireCookie(name){
   if(document.cookie.length == 0)
     return null;

   var regSepCookie = new RegExp('(; )', 'g');
   var cookies = document.cookie.split(regSepCookie);

   for(var i = 0; i < cookies.length; i++){
     var regInfo = new RegExp('=', 'g');
     var infos = cookies[i].split(regInfo);
     if(infos[0] == name){
       return unescape(infos[1]);
     }
   }
   return null;
}

function setCookie(key, value, expireDays = 365, expireHours = 0, expireMinutes = 0, expireSeconds = 0) {
        var expireDate = new Date();
        if (expireDays) {
            expireDate.setDate(expireDate.getDate() + expireDays);
        }
        if (expireHours) {
            expireDate.setHours(expireDate.getHours() + expireHours);
        }
        if (expireMinutes) {
            expireDate.setMinutes(expireDate.getMinutes() + expireMinutes);
        }
        if (expireSeconds) {
            expireDate.setSeconds(expireDate.getSeconds() + expireSeconds);
        }
        document.cookie = key +"="+ escape(value) +
            ";domain="+ window.location.hostname +
            ";path=/"+
            ";expires="+expireDate.toUTCString();
    }

function deleteCookie(name) {
        setCookie(name, "delete", null , null , null, 1);
}
