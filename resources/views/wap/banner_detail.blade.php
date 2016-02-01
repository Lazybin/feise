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
        *{margin:0px; padding: 0px; font-size: 24px;color: #565656;font-family: "Microsoft YaHei",Arial,Helvetica,sans-serif; }
        section{max-width: 640px;margin:0px auto;}
        section div.main{width: 90%; margin-left: 5%;}
        section div.main div{margin-bottom: 30px;}
        section h1{font-size: 32px; text-align: center; font-family: "黑体";margin:50px 0px; }
        section p{line-height: 40px; text-indent: 2em; }
        section p span{color: #2383c9;}
        section p.noindent{text-indent: 0px;}
        section img{max-width:100%;}
        @media screen and (max-width: 420px){
            section img{max-width:100%;}
            section h1{font-size: 24px;margin:20px 0px;}
            section p{line-height: 24px; font-size: 16px; }
            section div.main div{margin-bottom: 20px;}
            section p span{font-size: 16px;}
            section p strong{font-size: 16px;}
        }
        @media screen and (min-width:421px) and (max-width: 500px){
            section img{max-width:100%;}
            section h1{font-size: 28px;margin:20px 0px;}
            section p{line-height: 30px; font-size: 18px;}
            section div.main div{margin-bottom: 20px;}
            section p span{font-size: 18px;}
            section p strong{font-size: 18px;}
        }
    </style>
</head>
<body>
<section>
    <img src="{{url('/').$content['detail_image']}}" />
</section>
</body>
</html>