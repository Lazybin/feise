<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>活动详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <!--<link rel="apple-touch-icon-precomposed" href="http://st.360buyimg.com/m/images/apple-touch-icon.png?v=jd2015030537">-->
    <style type="text/css">
        body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,label,fieldset,input,textarea,p,blockquote,th,td,section,canvas{margin: 0px; padding: 0px;}
        img{margin: 0px; padding:0px; border:none;width: 100%; }
        body{font-family: 'Microsoft YaHei', Arial;  font-size: 16px; min-width: 320px;}
        section{max-width: 640px;margin: 0px auto;}
        li{list-style: none;}
        p.img{width: 100%;}


        .thumb{
            position:relative;
            animation:myfirst 0.5s linear 0s infinite alternate;
            /* Firefox: */
            -moz-animation:myfirst 0.5s linear 0s infinite alternate;
            /* Safari and Chrome: */
            -webkit-animation:myfirst 0.5s linear 0s infinite alternate;
            /* Opera: */
            -o-animation:myfirst 0.5s linear 0s infinite alternate;
        }

        @keyframes myfirst
        {
            0%   {left:0px;top:0px;}
            100%  {left:0px; top:10px;}
        }

        @-moz-keyframes myfirst /* Firefox */
        {
            0%   {left:0px;top:0px;}
            100%  {left:0px;top:10px;}
        }

        @-webkit-keyframes myfirst /* Safari and Chrome */
        {
            0%   {left:0px;top:0px;}
            100%  {left:0px;top:10px;}
        }

        @-o-keyframes myfirst /* Opera */
        {
            0%   {left:0px;top:0px;}
            100%  {left:0px; top:10px;}
        }




        .box{
            position:relative;
            animation:open_box 0.5s ease-in 0s 1 normal forwards ;
            /* Firefox: */
            -moz-animation:open_box 0.5s ease-in 0s 1 normal forwards ;
            /* Safari and Chrome: */
            -webkit-animation:open_box 0.5s ease-in 0s 1 normal forwards ;
            /* Opera: */
            -o-animation:open_box 0.5s ease-in 0s 1 normal forwards ;
        }

        @keyframes open_box
        {
            0%   {left:0px;top:0em;width:45%;}
            50%  {left:0; top:1em;width:40%;}
            100%  {left:0; top:3em;width:35%;}
        }

        @-moz-keyframes open_box /* Firefox */
        {
            0%   {left:0px;top:0em;width:45%;}
            50%  {left:0; top:1em;width:40%;}
            100%  {left:0; top:4em;width:35%;}
        }

        @-webkit-keyframes open_box /* Safari and Chrome */
        {
            0%   {left:0px;top:0em;width:45%;}
            50%  {left:0; top:1em;width:40%;}
            100%  {left:0; top:4em;width:35%;}
        }

        @-o-keyframes open_box /* Opera */
        {
            0%   {left:0px;top:0em;width:45%;}
            50%  {left:0; top:1em;width:40%;}
            100%  {left:0; top:4em;width:35%;}
        }




        .base_pos{
            left:0px;top:0px;position:absolute;
        }
        .hidden{
            z-index:-100;
        }
        .show{
            z-index:-1;
        }


        .zhufubiaoti{left:58%;top:70%;position:absolute;width: 2%;float: left;line-height: 1.1em; font-size:1.5em;font-weight:600;color: #FCCDA3;}
        .zhuwenzi{left:45%;top:70%;position:absolute;width: 2%;float: left;line-height: 1.2em; font-size:1.8em;font-weight:700;color: #FCCDA3;}


        @media screen and (max-width: 580px){
            body{font-size: 14px;}
            .itemlist li{font-size: 1em;}
            .itemlist p.text1{font-size: 1.2em;}
            .tishi1{line-height: 1em; font-size:3em;color: #FCCDA3;}
            .tishi2{line-height: 1em; font-size:2em;color: #FCCDA3;}
        }
        @media screen and (max-width: 520px){
            body{font-size: 12px;}
            .itemlist li{font-size: 1em;}
            .itemlist p.text1{font-size: 1.2em;}
            .tishi1{line-height: 1em; font-size:1.4em;color: #FCCDA3;}
            .tishi2{line-height: 1em; font-size:1.2em;color: #FCCDA3;}

            #tishi1{margin: auto;left:0;right:0;bottom:-190%;position:absolute;display: none}
            #tishi2{margin: auto;left:0;right:0;bottom:-205%;position:absolute;display: none}

            .zhufubiaoti{
                left:57%;top:70%;
                position:absolute;
                width: 2%;float: left;line-height: 1em;
                font-size:1.8em;font-weight:600;color: #FCCDA3;
            }

            .zhuwenzi{
                left:47%;top:70%;
                position:absolute;width: 2%;
                float: left;line-height: 1.2em;
                font-size:2em;font-weight:700;
                color: #FCCDA3;
            }


        }
        @media screen and (max-width: 320px){
            p.text{ line-height: 1.5em; font-size: 1em; }
            .itemlist p.text2{font-size: 1.2em;width: 90%; margin-left: 5%;}
            .tishi1{line-height: 1em; font-size:1.2em;color: #FCCDA3;}
            .tishi2{line-height: 1em; font-size:1em;color: #FCCDA3;}

            #tishi1{margin: auto;left:0;right:0;bottom:-190%;position:absolute;display: none}
            #tishi2{margin: auto;left:0;right:0;bottom:-205%;position:absolute;display: none}

            .zhufubiaoti{
                left:56%;top:65%;
                position:absolute;
                width: 2%;float: left;line-height: 1em;
                font-size:1.6em;font-weight:600;color: #FCCDA3;
            }

            .zhuwenzi{
                left:47%;top:65%;
                position:absolute;width: 2%;
                float: left;line-height: 1.2em;
                font-size:1.8em;font-weight:700;
                color: #FCCDA3;
            }
        }

    </style>
</head>
<body style="background-color:#AD1929 ">
<section style="position:relative;text-align: center">
    <p id="head" style="position:relative;"  class="img show"><img src="img/first_head.png" alt=""></p>
    <p id="aaaa" class="img base_pos test5 hidden" style="display: none"><img src="img/open_background.png" alt=""></p>
    <p class="img" style="position: absolute;bottom: -145%;">
        <img id="img_box" class=""  style="width:45%" onclick="onOpenClick()" src="img/first_box.png" alt="">
    </p>
    <p id="title" style="display: none;"><span class="zhufubiaoti">恭祝您</span></p>
    <p id="content" style="display: none;"><span class="zhuwenzi">运筹帷幄事业新</span></p>
    <p id="tishi1"><span class="tishi1">已找到今日宝藏，还有7天，请再接再厉！</span></p>
    <p id="tishi2"><span class="tishi2">要连续点击七天才能得大礼哦！</span></p>
    <p id="p_thumb" class="img" style="position: absolute;bottom: -200%;">
        <img class="thumb" onclick="onOpenClick()" style="width:15%" src="img/thumb.png" alt="">
    </p>
</section>
<script src="{{ url('../resources/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
    });
    function onOpenClick(){
        $("#aaaa").removeClass("hidden").addClass("show");
        $("#aaaa").css('display','inline');
        $("#head").removeClass("show").addClass("hidden");
        $("#p_thumb").addClass("hidden");
        $("#img_box").addClass("box");
        document.getElementById("img_box").addEventListener("webkitAnimationEnd",function(e){
            //console.log("animationend",e);
            //alert(111);
            $("#img_box").attr('src','img/box_open.png');
            $("#content").css('display','inline');
            $("#title").css('display','inline');
            $("#tishi1").css('display','inline');
            $("#tishi2").css('display','inline');
            //e.target.style.display="none";
        },false);
    }
</script>
</body>
</html>