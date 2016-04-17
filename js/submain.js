/*
* @Author: anchen
* @Date:   2016-04-16 21:35:37
* @Last Modified by:   anchen
* @Last Modified time: 2016-04-17 08:54:38
*/

$(document).ready(function(){
    var url = window.location.href;
    var urlArray = url.split('#');
    var id = urlArray[1];
    var userId;
    var addString = '';
    userId = Math.floor(Math.random()*1000);
    var startTime,endTime;
    $.ajax({
        url:"api.php?action=event&eventId="+id,
        type:'get',
        dataType:'json',
        data:{
            'id':id
        },
        success:function(backData){
            var beginTime_b = backData.data.beginTime;
            var endTime_b = backData.data.endTime;
            startTime = new Date(parseInt(backData.data.beginTime)*1000).toLocaleString().
            replace(/年|月/g,"-").replace(/日/g," ");
            endTime = new Date(parseInt(backData.data.endTime)*1000).toLocaleString().
            replace(/年|月/g,"-").replace(/日/g," ");
            addString += "<div class='header'>"+"<h1>"+backData.data.title+"</h1>"+"</div>"+"<div class='poster'>"+"<img src='"+backData.data.imageUrl+"'>"+"</div>"+"<div class='content-detail'>"+"<dl>"+ "<dt>介绍</dt>"+"<dd>"+backData.data.detail+"</dd>"+"<dt>开始时间</dt>"+"<dd>"+startTime+"</dd>"+"<dt>结束时间</dt>"+"<dd>"+endTime+"</dd>"+"<dt>总票数</dt>"+"<dd>"+parseInt(backData.data.total)+"</dd>"+"<dt>剩余票数</dt>"+"<dd>"+parseInt(backData.data.last)+"</dd>"+"</dl>"+"<p>抢票倒计时: </p>"+"<p class='show-time'></p>"+"</div>";
            $('body').append(addString);
        }
    });
    var $showTime = $(".show-time").eq(0);
    var $alert = $('.alert').eq(0);
    var timer = setInterval(function(){
                    currentTime = new Date().getTime();
                    milusTime = endTime_b - currentTime;
                    lastDay=Math.floor(milusTime/(24*3600*1000));
                    leave1=milusTime%(24*3600*1000);
                    lastHour=Math.floor(leave1/(3600*1000));
                    leave2=leave1%(3600*1000);
                    lastMiute=Math.floor(leave2/(60*1000));
                    leave3=leave2%(60*1000);
                    lastSecond=Math.round(leave3/1000);
                    /*if((lastDay===0)&&(lastDay === lastHour)&&(lastHour===lastMiute)&&(lastMiute===lastSecond)){
                        clearInterval(timer);
                        return;
                    }*/
                    $showTime.innerHTML = lastDay+"天 "+lastHour+"时 "+lastMiute+"分 "+lastSecond+"秒";
                },1000);
    var $footer = $(".footer").eq(0);
    $(document).on("click",$footer,function(){
        $.ajax({
            url:"api.php?action=event&eventId="+id,
            type:'get',
            dataType:'json',
            data:{
                'id':id,
                'userId':userId
            },
            success:function(){
                $alert.innerHTML = "抢票成功";
                $alert.css({'display':'block'});
            }
        });
    });

});