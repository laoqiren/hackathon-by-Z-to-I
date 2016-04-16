/*
* @Author: anchen
* @Date:   2016-04-16 11:08:44
* @Last Modified by:   anchen
* @Last Modified time: 2016-04-16 21:49:35
*/

$(document).ready(function(){
    var lastDay,lastHour,lastMiute,lastSecond;
    var currentTime;
    var milusTime;
    var leave1,leve2,level3;
    var showPs = document.getElementsByClassName("show-time");
    console.log(showPs.length);
    var endTime = new Date(2016,3,28);
    var timer = setInterval(function(){
        currentTime = new Date();
        milusTime = endTime - currentTime;
        lastDay=Math.floor(milusTime/(24*3600*1000));
        leave1=milusTime%(24*3600*1000);
        lastHour=Math.floor(leave1/(3600*1000));
        leave2=leave1%(3600*1000);
        lastMiute=Math.floor(leave2/(60*1000));
        leave3=leave2%(60*1000);
        lastSecond=Math.round(leave3/1000);
        if((lastDay===0)&&(lastDay === lastHour)&&(lastHour===lastMiute)&&(lastMiute===lastSecond)){
            clearInterval(timer);
            return;
        }
        for(var i=0; i<showPs.length; i++){
            showPs[i].innerHTML = lastDay+"天 "+lastHour+"时 "+lastMiute+"分 "+lastSecond+"秒";
        }
    },1000);
    $("button.right").click(function(){
        location.href = "sub.html";
    });
});