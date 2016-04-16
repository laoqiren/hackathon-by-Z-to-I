/*
* @Author: anchen
* @Date:   2016-04-16 11:08:44
* @Last Modified by:   anchen
* @Last Modified time: 2016-04-17 01:29:20
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
    $.ajax({
        url:"api.php?action=eventlist",
        type:'get',
        dataType:'json',
        success:function(backData){
            if(backData.status == "200"){
                console.log("success");
                var objectSum = backData.data.length;
                for(var i=0;i<objectSum;i++){
                    var addString = "<div class='container";
                    if(i===0){
                        addString += " first";
                    }
                    addString += " id='c"+i+"'>"+
                "<div class='sub left-part'>"+"<img src='"+backData.data[i].imageUrl+"'>"+
                "</div>"+"<div class='sub right-part'>"+"<div class='title'>"+"<h2>"+backData.data[i].title+"</h2>"+"</div>"+"<div class='book'>"+"<button class='btn btn-default btn-lg btn-success left' type='button'>抢票中</button>"+"<button class='btn btn-default btn-lg btn-warning right' type='button'>进入抢票</button>"+"</div>"+"<p class='show-time'></p>"+"<p class='show-time'></p>"+
                "<div class='progress progress-striped active'>"+"<div class='progress-bar progress-bar-danger' style='width:80%''></div>"+"</div>"+"</div>"+"</div>";
                    var $addObject = $(addString);
                    console.log($addObject);
                    $(body).eq(0).append($addObject);
                }

            }
        }

    });

});