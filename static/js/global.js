var deviceWidth = window.screen.width;
var atop,marTop_h,marTop,txtLen;
if(deviceWidth> 1600){
    atop = 130;
    marTop_h = 76;
    marTop = 7;
    txtLen = 27;
}


/**
 * 
 * @authors sbinzai (you@example.org)
 * @date    2016-04-28 16:56:46
 * @version $Id$
 */
 $(function() {
 	
    $(window).scroll(function() {
        var a=$("#top-bar").height();
        var b=$(".header-top").height();
        var c=1500;
    	if ((document.documentElement.scrollTop||window.pageYOffset||document.body.scrollTop)>(a+b+c)) {
    		$(".widget-tool .back-up").css("opacity",1);
            $(".right_fu").css({"position":"fixed","top":"12px","left":"50%","margin-left":"190px","height":"800px"});
    	} else {
    		$(".widget-tool .back-up").css("opacity",0)
            $(".right_fu").css({"position":"relative","top":"0","left":"0","margin-left":"0","height":"auto"});
    	}
    });
    //当点击跳转链接后，回到页面顶部位置
    $(".widget-tool .back-up").click(function() {
        $('body,html').animate({
        	scrollTop: 0
        },400);
        return false;
    });
    
    $(".header .favorite").html("");
    $(".header .tips").html("至诚-中国金融理财服务平台");

    $('#formStock').on('submit', function () {
        var stock = $('#q').val();
        if (stock.search(/^[a-z]{0,2}\d{5,6}$/) == 0) {
            location.href = '/center/' + stock + '.html';
        }
        return false;
    })
    
});
//添加书签
var TY = {};
TY.add_bookmark = function(title,url) {
  var title = title || document.title;
  var url = url || window.location.href;
  if(document.all){
        try{
            window.external.addFavorite(url,title);
        }catch(e){  
            alert("加入收藏失败，请使用Ctrl+D进行添加");
        }
    }else if(window.sidebar){  
        window.sidebar.addPanel(title, url, "");  
  }else{  
        alert("加入收藏失败，请使用Ctrl+D进行添加");  
  }  
}

$(function(){
    $('.switch').on('click', function() {
        var $newsLists = $(this).parent().parent().find('.label-list .slist');
        var $currentNews = $newsLists.filter('.show');
        var $nextNews = $currentNews.next();

        if($nextNews.length === 0) {
            $nextNews = $newsLists.eq(0);
        }

        $newsLists.removeClass('show');
        $nextNews.addClass('show');
    });
});

$(function() {
    $(window).scroll(function() {
        var h=$("#top-bar").height();
        var hi=$(".header-top").height();
        var i=200;
        var s=h+hi+i;
        if ($(window).scrollTop() > s) {
            $(".other_pos").css({"position":"fixed","top":"0","height":"55px","width":"690px","left":"50%","margin-left":"-540px"});
            $(".caicn-up").show();
        } else {
            $(".other_pos").css({"position":"relative","top":"0","height":"55px","width":"1080px"});
            $(".caicn-up").hide();
        }
    });
});

$(".cj-up").toggle(function(){
   $(".other_pos").css("height","auto");
   $(this).addClass("on");
},function(){
    $(".other_pos").css("height","55px");
    $(this).removeClass("on");
});

$(function() {
	$('.index-channel .logo').css('display','none');
    $(window).scroll(function() {
        if ($(window).scrollTop() > 140) {
            $(".index-channel").css({"position":"fixed","top":"15px"});
            $('.index-channel .logo').css('display','block');
        } else {
            $(".index-channel").css({"position":"absolute","top":"5px"});
            $('.index-channel .logo').css('display','none');
        }
    });
});

//选项卡切换
function onSelect(obj,ch){
     var parentNodeObj= obj.parentNode;
     var s=0;
     for(i=0;i<parentNodeObj.childNodes.length;i++){
        // alert("第" +i +"次")
        if (parentNodeObj.childNodes[i].nodeName=="#text"){
            continue;
        }
        parentNodeObj.childNodes[i].className="tab_1";
        var newObj=document.getElementById(ch + "_" + s);
        if(newObj!=null){
             newObj.style.display='none';
             if(parentNodeObj.childNodes[i]==obj){
                newObj.style.display='';
             }
        }
        s +=1;
     }
     obj.className="tab_2";
 }

$(document).ready(function() {
	$('.seahotid').live('click', function(event) {
        var id=$(this).attr('data');
        $.get("/api.php?op=seahot&id="+id);
    });
	
    $(".header_search .input-search,.search-box .serach_input").on("click", function(e){
        $(".hot-search-suggestion").show();
        $(document).one("click", function(){
            $(".hot-search-suggestion").hide();
        });
    });
    $(".lie_input").on("click", function(e){
        $(".lie_suggestion").show();
        $(document).one("click", function(){
            $(".lie_suggestion").hide();
        });
    });
    $(".input-search,.lie_input,.serach_input").on("click", function(e){
        e.stopPropagation();
    });

    $("#ztList li").hover(function(){
        var text = $('#ztList .zt-con-txt');
        for(var i = 0;i<text.length;i++){
            var str = $(text).eq(i).text();
            if(str.length>txtLen){
                str = str.substring(0, txtLen)+'...';
            }
            $(text).eq(i).text(str);
        }
        $(this).find('.zt-img-title').stop().animate({
            top: 0
        },500);
        $(this).find('.zt-con-title').stop().animate({
            fontSize : '16px',
            fontWeight:'800',
            marginTop :marTop_h+'px'
        },500);
    },function(){
        $(this).find('.zt-img-title').stop().animate({
            top:atop +'px'
        },500);
        $(this).find('.zt-con-title').stop().animate({
            fontSize : '14px',
            fontWeight:'0',
            marginTop:marTop+'px'
        },500)
    })

    $(".arrow-down").toggle(function(){
        $(".sq").css("display","block");
        $(this).css({"background-position-x":"-63px","background-position-y":"21px"});
    },function(){
        $(".sq").hide();
        $(this).css({"background-position-x":"0px","background-position-y":"21px"});
    });
    $(document).on("click", function(){
        $(".sq").hide();
        $(".arrow-down").css({"background-position-x":"0","background-position-y":"21px"});
    });
    $("body").delegate(".comment-reply","click",function(){
        var c=$(this),
        d=c.closest("li").find(".comment-nickname").text(),
        b=c.closest("li").attr("data-id"),
        a='<div class="reply-input"><textarea placeholder="回复：'+d+'" data-id="'+b+'" data-name=""/><p><span class="add-reply-btn">发表</span></p></div>';
        $(".reply-input").remove(),c.closest("li").append(a)  
    }),
    $("body").delegate(".reply-input textarea","focus",function(){
        $(this).parent().height(115);
    }),
    $("body").delegate(".reply-input textarea","blur",function(){
        $(this).parent().height(40);
    });
    
    $(".doc-remove").live("click", function(){
    	$(this).parent().fadeOut(1000);
    	event.preventDefault();
    });
    
    setTimeout(function(){
    	$(".refresh-result").fadeOut("slow");
    },1000);
    
    var ifpages=$(".zhicheng_news_list").html();
    if(ifpages){
    	if($(".zhicheng_news_list").attr('data')=="show"){
    		if($("#newsfeedlist").parent().html()){
    			/*NEWS_FEED({
		            w: 690,//必须大于300
		            showid : 'Tf0qPG',
		            placeholderId : 'item',//容器ID
		            fId : 'newsfeedlist',//信息流容器ID
		            inject : 'cads'
		        });*/
    		}
    	}else if($(".zhicheng_news_list").attr('data')=="list"){
    		/*NEWS_FEED({
	            w: 690,//必须大于300
	            showid : 'DFv62N',
	            placeholderId : 'item',//容器ID
	            fId : 'newsfeedlist',//信息流容器ID
	            inject : 'cads'
	        });*/
    	}
    }
    
    /*var ifhots = $(".hot-video-box").html();
    if(ifhots){
    	var html = '<li><a href="http://95p99.com/" target="_blank"><div class="video-img"><img src="http://{XFM_DOMAIN}/statics/aimg/xpz9.png" alt="三亿资金打造,配资老品牌,注册领取管理费"></div>';
    	html += '<div class="video_content"><div class="video_inline"><div class="video_title">鑫配资<span style="float:right;padding: 0 6px;border: #dfdfdf solid 1px;height: 20px;line-height: 20px;">广告</span></div>';
    	html += '<div class="video_info"><span style="color:#000;">三亿资金打造,配资老品牌,注册领取管理费</span></div></div></div></a></li>';
    	$(".hot-video-box").find("ul").prepend(html);
    }*/
   
   //首页位
   	var ifinlist = $(".zhicheng_news_list").html();
    if(ifinlist){
    	var html='';
    	/*if($(".zhicheng_news_list").attr("data")!="show"){
    		html += '<a href="http://www.pz997.com/" target="_blank" class="item"><div class="doc_img"><img src="//{XFM_DOMAIN}/statics/aimg/cycp.jpg" alt="股票开户送3000元 杠杆炒股1-10 月息0.6%"></div>';
    		html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title">赤盈操盘注册就送30000元 下载APP送88元现金</div>';
    		html += '<div class="doc_info"><b class="zd">广告</b></div></div></div>';
    		html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
    	
    		html += '<a href="http://www.yipz7.com/" target="_blank" class="item"><div class="doc_img"><img src="//{XFM_DOMAIN}/statics/aimg/yip6.png" alt="股票配资送30000元操盘金"></div>';
    		html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title">股票配资送30000元操盘金</div>';
    		html += '<div class="doc_info"><b class="zd">广告</b></div></div></div>';
    		html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
    		
    		html += '<a href="http://95p99.com/" target="_blank" class="item"><div class="doc_img"><img src="//{XFM_DOMAIN}/statics/aimg/xpz9.png" alt="三亿资金打造,配资老品牌,注册领取管理费"></div>';
    		html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title">三亿资金打造,配资老品牌,注册领取管理费</div>';
    		html += '<div class="doc_info"><b class="zd">广告</b></div></div></div>';
    		html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
    	}*/
    	
    	/*if($(".zhicheng_news_list").attr("data")=="index"){
    		html='';
    		html = '<a href="" target="_blank" class="item"><div class="doc_img"><img src="//{XFM_DOMAIN}/statics/aimg/snzx.jpg" alt="年中大促最后3天就结束了，错过就要等一年"></div>';
    		html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title">雅休配资开户即送3000元现金,直接提现！</div>';
    		html += '<div class="doc_info" style="height:auto;margin-top:5px">你炒股，我出钱，注册领取3000现金！1-12杠杆，1-12月操盘，利息只要0.6%，实名即送666管理费，实盘交易，单票满仓，提现即日到账，快来注册领取吧！</div><div class="doc_info"><b class="zd">广告</b></div></div></div>';
    		html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
    		
    		html = '<a href="http://zjpz6.com/#zccj" target="_blank" class="item"><div class="doc_img"><img src="//{XFM_DOMAIN}/statics/aimg/9893.png" alt="雅休配资：开户即送3000元现金,直接提现！你炒股，我出钱！1-12杠杆，1-12月操盘，利息只要0.6%，实名即送666管理费，实盘交易！"></div>';
    		html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title" style="font-size:14px;">雅休配资：开户即送3000元现金,直接提现！你炒股，我出钱！1-12杠杆，1-12月操盘，利息只要0.6%，实名即送666管理费，实盘交易！</div>';
    		html += '<div class="doc_info"><b class="zd">广告</b></div></div></div>';
    		html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
    		
    		html += '<a href="http://xlpz6.com/#zccj" target="_blank" class="item"><div class="doc_img"><img src="//{XFM_DOMAIN}/statics/aimg/8997.png" alt="牛市来袭，配资找牛弘，注册即送3000现金，充值送2%，直接提现！1-10倍杠杆，按天、按月灵活操盘，利息只要0.6%"></div>';
    		html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title" style="font-size:14px;">牛市来袭，配资找牛弘，注册即送3000现金，充值送2%，直接提现！1-10倍杠杆，按天、按月灵活操盘，利息只要0.6%</div>';
    		html += '<div class="doc_info"><b class="zd">广告</b></div></div></div>';
    		html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
    	}*/
    	
    	/*$(".rm").parent().parent().parent().parent().hide();
    	$(".zhicheng_news_list").prepend(html);*/
    }
    
   	/*var iffocus = $("#focus").html();
   	var zdtb = $(".other_pos").find(".active a").text();
	if(iffocus&&zdtb!="涨跌停板"){
		var html = '<div class="inl" style="position:relative;margin-bottom:10px;"><span class=\'piaofu-left-biaoshi2\'>广告</span><a href="http://yipz7.com" target="_blank"><img src="//{XFM_DOMAIN}/statics/img/y1800.jpg" alt="" width="1080"></a></div>';
		html += '<div class="inl" style="position:relative;margin-bottom:10px;"><span class=\'piaofu-left-biaoshi2\'>广告</span><a href="http://pz997.com" target="_blank"><img src="//{XFM_DOMAIN}/statics/img/cy1800.jpg" alt="" width="1080"></a></div>';
		$(".all_main").prepend(html);
	}*/
    
    //内容页位
    /*var ifhf = $(".ship_wrap h2").html();
    if(ifhf){
    	var html = '<span class="piaofu-left-biaoshi">广告</span><a href="http://www.pz997.com" target="_blank"><img src="//{XFM_DOMAIN}/statics/aimg/cypz1080.jpg" alt=""></a>';
    	html += '<div class="inl" style="position:relative;margin-bottom:10px;"><span class=\'piaofu-left-biaoshi2\'>广告</span><a href="http://www.95p99.com" target="_blank"><img src="//{XFM_DOMAIN}/statics/aimg/xpz1080.png" alt="" width="1080"></a></div>';
    	$(".mad_top").append(html);
    }*/
    
    //logo位
    /*var iflogo = $(".caicn_logo").html();
    if(iflogo){
    	var logo1 = $(".news_logo").html();
    	var logo2 = $(".techs_logo").html();
    	if(logo1||logo2){
    		var html = '<div class="newa2" style="position:relative;"><span class=\'piaofu-left-biaoshi2\'>广告</span><a href="https://www.993587.com/Register/e7e20ef0-4152-4145-bd27-088081b1cead" target="_blank"><img src="//{XFM_DOMAIN}/statics/img/ryf2.jpg" alt=""></a></div>';
    		$(".header_search").before(html);
    	}else{
    		var html = '<div class="newa" style="position:relative;"><span class=\'piaofu-left-biaoshi2\'>广告</span><a href="https://www.993587.com/Register/e7e20ef0-4152-4145-bd27-088081b1cead" target="_blank"><img src="//{XFM_DOMAIN}/statics/img/ryf.jpg" alt=""></a></div>';
    		$(".header_search").before(html);
    	}
    }*/
   
   	/*var ifrmwz = $(".h-a").html();
   	if(ifrmwz){
   		var html = '<div class="mt-20 bgc-fff" style="position:relative;"><span class="piaofu-left-biaoshi2">广告</span><a href="javascript:void(0)"><img src="//{XFM_DOMAIN}/statics/v4/aimg/370-140.jpg" alt=""></a></div>';
    	$(".h-a").before(html);
    }*/
   	var ifindexart = $(".main-index").html();
   	if(ifindexart){
   		//var html = '<div class="bgc-fff"><div class="index-1" style="position:relative;width:50%;float:left;"><a href="https://www.pz2777.com/index?recommendCode=1143" class="seahotid" data="354" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/img/c400-70.jpg" alt="" style="padding-right:20px"></a></div><div class="index-2" style="position:relative;width:50%;float:right;"><a href="https://www.pz4666.com/index?recommendCode=1226" target="_blank" class="seahotid" data="353"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/img/y400-70.jpg" alt=""></a></div></div>';
    	//var html='<div class="bgc-fff"><div class="all-1" style="position:relative;width:50%;float:left;"><a href="http://661952.com" class="seahotid" data="361" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/aimg/hx400-70.jpg" alt="" style="padding-right:20px"></a></div><div class="all-2" style="position:relative;width:50%;float:right;"><a href="http://lmpz998.cn/" target="_blank" class="seahotid" data="362"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/aimg/lm400-70.jpg" alt=""></a></div></div>';
    	//html+='<div class="bgc-fff"><div class="all-3" style="position:relative;width:50%;float:left;"><a href="http://xpz76.cn" class="seahotid" data="363" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/aimg/x400-70.jpg" alt="" style="padding-right:20px"></a></div><div class="all-3" style="position:relative;width:50%;float:right;"><a href="https://09685.com/" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/v4/aimg/400x70.jpg" alt=""></a></div></div>';
    	//html+='<div class="bgc-fff"><div class="all-4" style="position:relative;width:50%;float:left;"><a href="https://pz9918.com/" class="seahotid" data="364" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/aimg/dzjt.jpg" alt="" style="padding-right:20px"></a></div><div class="all-4" style="position:relative;width:50%;float:right;"><a href="https://sjcl6.com/" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/v4/aimg/sjcl.jpg" alt=""></a></div></div>';
    	
		//$(".main-index").prepend(html);
    }
   	var ifart = $(".main-left").html();
   	if(ifart&&!ifindexart){
   		//var html ='<div class="bgc-fff"><div class="all-1" style="position:relative;width:50%;float:left;"><a href="http://661952.com" class="seahotid" data="361" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/aimg/hx400-70.jpg" alt="" style="padding-right:20px"></a></div><div class="all-3" style="position:relative;width:50%;float:right;"><a href="http://lmpz998.cn/" target="_blank" class="seahotid" data="362"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/aimg/lm400-70.jpg" alt=""></a></div></div>';
    	//html+='<div class="bgc-fff"><div class="all-3" style="position:relative;width:50%;float:left;"><a href="http://xpz76.cn" class="seahotid" data="363" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/aimg/x400-70.jpg" alt="" style="padding-right:20px"></a></div><div class="all-4" style="position:relative;width:50%;float:right;"><a href="https://09685.com/" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/v4/aimg/400x70.jpg" alt=""></a></div></div>';
    	//html+='<div class="bgc-fff"><div class="all-4" style="position:relative;width:50%;float:left;"><a href="https://pz9918.com/" class="seahotid" data="364" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/aimg/dzjt.jpg" alt="" style="padding-right:20px"></a></div><div class="all-4" style="position:relative;width:50%;float:right;"><a href="https://sjcl6.com/" target="_blank"><span class="piaofu-left-biaoshi2">广告</span><img src="//{XFM_DOMAIN}/statics/v4/aimg/sjcl.jpg" alt=""></a></div></div>';
    	
		//$(".main-left").prepend(html);
    }
    
});

$(document).ready(function(){
  window.onload = myfun1;
});

function myfun1(){
  $.ajax({
    type:"GET",
    url:"http://{XFM_DOMAIN}/api.php?op=get_zs",
    dataType: 'jsonp',
    jsonp:"jsoncallback",
    jsonpCallback:"jsoncallback",
    success:function(data){
      var prod_name1 = data.data.snapshot["000001.SS"][0];
      var last_px1 = data.data.snapshot["000001.SS"][2];
      var px_change1 = data.data.snapshot["000001.SS"][3];

      px_change1 = px_change1.toFixed(2);
      var px_change_rate1 = data.data.snapshot["000001.SS"][4];
      px_change_rate1 = px_change_rate1.toFixed(2);
      var prod_name2 = data.data.snapshot["399001.SZ"][0];
      var last_px2 = data.data.snapshot["399001.SZ"][2];
      var px_change2 = data.data.snapshot["399001.SZ"][3];
      px_change2 = px_change2.toFixed(2);
      var px_change_rate2 = data.data.snapshot["399001.SZ"][4];
      px_change_rate2 = px_change_rate2.toFixed(2);
      var prod_name3 = data.data.snapshot["399006.SZ"][0];
      var last_px3 = data.data.snapshot["399006.SZ"][2];
      var px_change3 = data.data.snapshot["399006.SZ"][3];
      px_change3 = px_change3.toFixed(2);

      var px_change_rate3 = data.data.snapshot["399006.SZ"][4];
      px_change_rate3 = px_change_rate3.toFixed(2);
      var prod_name4 = data.data.snapshot["DXY.OTC"][0];
      var last_px4 = data.data.snapshot["DXY.OTC"][2];
      var px_change4 = data.data.snapshot["DXY.OTC"][3];
      px_change4 = px_change4.toFixed(2);
      var px_change_rate4 = data.data.snapshot["DXY.OTC"][4];
      px_change_rate4 = px_change_rate4.toFixed(2);
      var prod_name5 = data.data.snapshot["US10YR.OTC"][0];
      var last_px5 = data.data.snapshot["US10YR.OTC"][2];
      var px_change5 = data.data.snapshot["US10YR.OTC"][3];
      px_change5 = px_change5.toFixed(2);
      var px_change_rate5 = data.data.snapshot["US10YR.OTC"][4];
      px_change_rate5 = px_change_rate5.toFixed(2);
      var prod_name6 = data.data.snapshot["US500.OTC"][0];
      var last_px6 = data.data.snapshot["US500.OTC"][2];
      var px_change6 = data.data.snapshot["US500.OTC"][3];
      px_change6 = px_change6.toFixed(2);
      var px_change_rate6 = data.data.snapshot["US500.OTC"][4];
      px_change_rate6 = px_change_rate6.toFixed(2);
      var prod_name7 = data.data.snapshot["USDCNH.OTC"][0];
      var last_px7 = data.data.snapshot["USDCNH.OTC"][2];
      var px_change7 = data.data.snapshot["USDCNH.OTC"][3];
      px_change7 = px_change7.toFixed(2);
      var px_change_rate7 = data.data.snapshot["USDCNH.OTC"][4];
      px_change_rate7 = px_change_rate7.toFixed(2);
      var tan = '<li><div class="name">' + prod_name1 + '</div><div class="price">' + last_px1 + '</div><div class="px-change '+redOrGreen(px_change1)+'">' + px_change1 + '(' + px_change_rate1 + '%)' + '</div></li><li><div class="name">' + prod_name2 + '</div><div class="price">' + last_px2 + '</div><div class="px-change '+redOrGreen(px_change2)+'">' + px_change2 + '(' + px_change_rate2 + '%)' + '</div></li><li><div class="name">' + prod_name3 + '</div><div class="price">' + last_px3 + '</div><div class="px-change '+redOrGreen(px_change3)+'">' + px_change3 + '(' + px_change_rate3 + '%)' + '</div></li><li><div class="name">' + prod_name4 + '</div><div class="price">' + last_px4 + '</div><div class="px-change '+redOrGreen(px_change4)+'">' + px_change4 + '(' + px_change_rate4 + '%)' + '</div></li><li><div class="name">' + prod_name5 + '</div><div class="price">' + last_px5 + '</div><div class="px-change '+redOrGreen(px_change5)+'">' + px_change5 + '(' + px_change_rate5 + '%)' + '</div></li><li><div class="name">' + prod_name6 + '</div><div class="price">' + last_px6 + '</div><div class="px-change '+redOrGreen(px_change6)+'">' + px_change6 + '(' + px_change_rate6 + '%)' + '</div></li><li><div class="name">' + prod_name7 + '</div><div class="price">' + last_px7 + '</div><div class="px-change '+redOrGreen(px_change7)+'">' + px_change7 + '(' + px_change_rate7 + '%)' + '</div></li>';
      $('#quotation-bar ul').prepend(tan);
    },
    error:function(jqXHR){
       console.log(jqXHR.status);
    }
  });
}
function redOrGreen(n){
    return n>0?"hong":"lv";
}
$(function(){
	var ifhotzt=$(".aside-article").eq(2).find(".head .title").text();
	if(ifhotzt=="热点专题"){
		$.ajax({
            url: '/index.php?m=seahot&c=index&a=get_hot_zt',
            type: 'GET',
            dataType: 'jsonp',
            jsonpCallback: "jsonpshowdata",
            success:function(data){
            	var html='';
                if(data.status==1){
                    $.each(data.data, function(k, r) {
                    	html += '<a href="'+r.url+'" class="item" target="_blank">';
                    	html += '<div class="title">'+r.title+'</div>';
                    	html += '<div class="date">'+r.description+'</div></a>';
                    });
                    $(".aside-article:eq(2)").find(".list").html(html);
                }
            }
        });
	}
	
	var ifhotzt=$(".aside-article").eq(1).find(".head .title").text();
	if(ifhotzt=="热门文章"){
		$.ajax({
            url: '/index.php?m=seahot&c=index&a=get_hot_rd',
            type: 'GET',
            dataType: 'jsonp',
            jsonpCallback: "jsonpshowdata",
            success:function(data){
            	var html='';
                if(data.status==1){
                    $.each(data.data, function(k, r) {
                    	html += '<a href="'+r.url+'" class="item" target="_blank">';
                    	html += '<div class="title">'+r.title+'</div>';
                    	html += '<div class="date">'+r.updatetime+'</div></a>';
                    });
                    $(".aside-article:eq(2)").find(".list").html(html);
                }
            }
        });
	}
	
})

$(function(){
    /*$(".news-no-more").hide();
    $(".loading-more").hide();
    var list_loading = false;
    $(window).scroll(function(){
    　　var scrollTop = $(this).scrollTop();
    　　var scrollHeight = $(document).height();
    　　var windowHeight = $(this).height();
    　　if(scrollTop + windowHeight == scrollHeight&&!list_loading){
            var page=$(".more_list_page").attr('page');
            var catid=$(".more_list_page").attr('catid');
            var non=$(".more_list_page").attr('non');
            var type=$(".more_list_page").attr('type');
            if(!page && !catid) return false;
            if(non==1) return false;
            list_loading=true;
            if(type == 'search'){
            	var q=$(".more_list_page").attr('q');
            	var ajax=$(".more_list_page").attr('datafld');
            	var url = '//{XFM_DOMAIN}/index.php?m=search&c=index&a=search';
            	var dt = {'q':q,'page':page,'pagesize':10,'ajax':ajax};
            }else if(type == 'special'){
            	var url = '/index.php?m=special&c=index&a=special_content_ajax';
            	var dt = {'specialid':specialid,'page':page,'pagesize':15};
            }else{
            	var url = '//{XFM_DOMAIN}/index.php?m=seahot&c=index&a=get_more_list';
            	var dt = {'catid':catid,'page':page,'pagesize':10};
            }
    		$(".loading-more").show();
    		if(type == 'search'||type == 'special'){
    			setTimeout(function(){
		            $.ajax({
		                url: url,
		                type: 'GET',
		                dataType: 'json',
		                data: dt,
		                success:function(data){
		                    var html=ids='';
		                    if(data.status==1){
		                        $.each(data.data, function(k, r) {
		                        	if(!r.catname) r.catname='';
		                        	html += '<a href="'+r.url+'" target="_blank" class="item"><div class="doc_img"><img src="'+r.thumb+'" alt="'+r.title+'"></div>';
		                        	html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title">'+r.title+'</div>';
		                        	html += '<div class="doc_info"><img src="'+r.thumb+'" class="company-small-logo">';
		                        	html += '<span>'+r.catname+'</span><span>'+r.updatetime+'</span></div></div></div>';
		                        	html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
		                        });
		                        page++;
		                        $(".more_list_page").attr('page',page);
		                        $(".loading-more").hide();
		                        $(".more_list_page").parent().find(".zhicheng_news_list").append(html);
		                    }else{
		                    	$(".loading-more").hide();
		                        $(".news-no-more").show();
		                        setTimeout(function(){
		                            $(".more_list_page").fadeOut("slow");
		                        },2000);
		                    	$(".more_list_page").attr('non',1);
		                    }
	                        list_loading=false;
		                }
		            });
	            },200);
    		}else{
	            setTimeout(function(){
		            $.ajax({
		                url: url,
		                type: 'GET',
		                dataType: 'jsonp',
		                jsonpCallback: "jsonpshowdata",
		                data: dt,
		                success:function(data){
		                    var html=ids='';
		                    var idarr = new Array();
		                    if(data.status==1){
		                        $.each(data.data, function(k, r) {
		                        	if(!r.catname) r.catname='';
		                        	if(k==3||k==6||k==9&&type!='search'&&type!='special'){
		                                idarr[k]=ids=r['id'];
		                                html+='<div id="item'+ids+'"><div id="newsfeedlist'+ids+'"></div></div>';
		                            }
		                        	html += '<a href="'+r.url+'" target="_blank" class="item"><div class="doc_img"><img src="'+r.thumb+'" alt="'+r.title+'"></div>';
		                        	html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title">'+r.title+'</div>';
		                        	html += '<div class="doc_info"><img src="'+r.thumb+'" class="company-small-logo">';
		                        	html += '<span>'+r.catname+'</span><span>'+r.updatetime+'</span></div></div></div>';
		                        	html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
		                        });
		                        page++;
		                        $(".more_list_page").attr('page',page);
		                        $(".loading-more").hide();
		                        $(".more_list_page").parent().find(".zhicheng_news_list").append(html);
		                        if($(".zhicheng_news_list").attr('data')=="show"){
	                        	}else if($(".zhicheng_news_list").attr('data')=="list"){
						    	}
		                    }else{
		                    	$(".loading-more").hide();
		                        $(".news-no-more").show();
		                        setTimeout(function(){
		                            $(".more_list_page").fadeOut("slow");
		                        },2000);
		                    	$(".more_list_page").attr('non',1);
		                    }
	                        list_loading=false;
		                }
		            });
	            },200);
    		}
        }
    });*/
   	$(".news-no-more").hide();
    var country=$(".more_list_page").attr('country');
    $(".loading-more").text("点击加载更多");
    if(country&&country!=1){
        $(".loading-more").text("Trying to load");
    }
   	$(".more_list_page").click(function(){
   		var page=$(".more_list_page").attr('page');
        var catid=$(".more_list_page").attr('catid');
        var non=$(".more_list_page").attr('non');
        var type=$(".more_list_page").attr('type');
        var tem=$(".more_list_page").attr('tem');
        if(!page && !catid) return false;
        if(non==1) return false;
        if(type == 'search'){
        	var q=$(".more_list_page").attr('q');
        	var ajax=$(".more_list_page").attr('datafld');
        	var url = '//{XFM_DOMAIN}/index.php?m=search&c=index&a=search';
        	var dt = {'q':q,'page':page,'pagesize':10,'ajax':ajax};
        }else if(type == 'special'){
        	var url = '/index.php?m=special&c=index&a=special_content_ajax';
        	var dt = {'specialid':specialid,'page':page,'pagesize':15};
        }else if(type == 'hwsc'){
            country=$(".more_list_page").attr('country');
            var url = '/index.php?m=seahot&c=index&a=get_more_list_hwsc';
            var dt = {'catid':catid,'page':page,'pagesize':10,'country':country};
        }else{
        	var url = '//{XFM_DOMAIN}/index.php?m=seahot&c=index&a=get_more_list';
        	var dt = {'catid':catid,'page':page,'pagesize':10};
        }
		$(".loading-more").show();
		if(type == 'search'||type == 'special'){
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: dt,
                success:function(data){
                    var html=ids='';
                    if(data.status==1){
                        $.each(data.data, function(k, r) {
                        	if(!r.catname) r.catname='';
                        	html += '<a href="'+r.url+'" target="_blank" class="item"><div class="doc_img"><img src="'+r.thumb+'" alt="'+r.title+'"></div>';
                        	html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title">'+r.title+'</div>';
                        	html += '<div class="doc_info"><img src="'+r.thumb+'" class="company-small-logo">';
                        	html += '<span>'+r.catname+'</span><span>'+r.updatetime+'</span></div></div></div>';
                        	html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
                        });
                        page++;
                        $(".more_list_page").attr('page',page);
                        $(".more_list_page").parent().find(".zhicheng_news_list").append(html);
                    }else{
                    	$(".loading-more").hide();
                        $(".news-no-more").show();
                        setTimeout(function(){
                            $(".more_list_page").fadeOut("slow");
                        },2000);
                    	$(".more_list_page").attr('non',1);
                    }
                    list_loading=false;
                }
            });
		}else if (type == 'hwsc') {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'jsonp',
                data: dt,
                jsonpCallback: "jsonpshowdata",
                success:function(data){
                    var html=ids='';
                    if(data.status==1){
                        $.each(data.data, function(k, r) {
                            html += '' +
                                '<dl>\n' +
                                '    <dt>\n' +
                                '        <a href="'+r.url+'" target="_blank" title="'+r.title+'">\n' +
                                '        <img alt="'+r.title+'" width="240" height="180" data-type="article" src="'+r.thumb+'">\n' +
                                '        </a>\n' +
                                '    </dt>\n' +
                                '    <dd>\n' +
                                '        <h2>\n' +
                                '            <a href="'+r.url+'" target="_blank">'+r.title+'</a>\n' +
                                '        </h2>\n' +
                                '        <p class="ellipsis-2">'+r.description+'</p>\n' +
                                '        <label>\n' +
                                '            <a href="'+r.url+'" target="_blank">\n' +
                                '                <img src="'+r.thumb+'" data-type="author">'+r.catname+'\n' +
                                '            </a>\n' +
                                '            <span class="fr"><i class="icon-shijian"></i><span class="format-time" data-time="'+r.updatetime1+'">'+r.updatetime2+'</span></span>\n' +
                                '        </label>\n' +
                                '    </dd>\n' +
                                '</dl>';
                        });
                        page++;
                        $(".more_list_page").attr('page',page);
                        $(".more_list_page").parent().find(".zt_news_list").append(html);
                    }else{
                        $(".loading-more").hide();
                        $(".news-no-more").show();
                        setTimeout(function(){
                            $(".more_list_page").fadeOut("slow");
                        },2000);
                        $(".more_list_page").attr('non',1);
                    }
                }
            });
        }else{
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'jsonp',
                jsonpCallback: "jsonpshowdata",
                data: dt,
                success:function(data){
                    var html=ids='';
                    var idarr = new Array();
                    if(data.status==1){
                        $.each(data.data, function(k, r) {
                        	if(!r.catname) r.catname='';
                        	if(k==3||k==6||k==9&&type!='search'&&type!='special'){
                                idarr[k]=ids=r['id'];
                                html+='<div id="item'+ids+'"><div id="newsfeedlist'+ids+'"></div></div>';
                            }
                        	html += '<a href="'+r.url+'" target="_blank" class="item"><div class="doc_img"><img src="'+r.thumb+'" alt="'+r.title+'"></div>';
                        	html += '<div class="doc_content"> <div class="doc-content-inline"><div class="doc_title">'+r.title+'</div>';
                        	html += '<div class="doc_info"><img src="'+r.thumb+'" class="company-small-logo">';
                        	html += '<span>'+r.catname+'</span><span>'+r.updatetime+'</span></div></div></div>';
                        	html += '<div class="doc-remove">不感兴趣<span class="icon_close"></span></div></a>';
                        });
                        page++;
                        $(".more_list_page").attr('page',page);
                        $(".more_list_page").parent().find(".zhicheng_news_list").append(html);
                    }else{
                    	$(".loading-more").hide();
                        $(".news-no-more").show();
                        setTimeout(function(){
                            $(".more_list_page").fadeOut("slow");
                        },2000);
                    	$(".more_list_page").attr('non',1);
                    }
                    list_loading=false;
                }
            });
		}
   	})
   	
   	$(".more_list_page_2").click(function(){
   		var page=$(".more_list_page_2").attr('page');
        var non=$(".more_list_page_2").attr('non');
        var type=$(".more_list_page_2").attr('type');

        if(type == 'search'){
            var q=$(".more_list_page_2").attr('q');
            var ajax=$(".more_list_page_2").attr('datafld');
            var url = '//{XFM_DOMAIN}/index.php?m=search&c=index&a=search';
            var dt = {'q':q,'page':page,'pagesize':10,'ajax':ajax};
        }
        else {
            var catid=$(".more_list_page_2").attr('catid');
            var tem=$(".more_list_page_2").attr('tem');
            var url = '//{XFM_DOMAIN}/index.php?m=seahot&c=index&a=get_more_list';
            var dt = {'catid': catid, 'page': page, 'pagesize': 10};
        }
        if(!page && !catid) return false;
        if(non==1) return false;
        if (type == 'search') {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                data: dt,
                success:function (data) {
                    var html = '';
                    if (data.status == 1) {
                        $.each(data.data, function (k, r) {
                            if (!r.catname) r.catname = '';
                            html += '<div class="list-item"><div class="img"><a href="' + r.url + '"><img src="' + r.thumb + '" alt="' + r.title + '"></div>';
                            html += '<div class="content"><div class="content-title"><div class="title-tag"><a href="' + r.caturl + '">' + r.catname + '</a></div>';
                            html += '<a href="' + r.url + '"><h1 class="dan" title="' + r.title + '">' + r.title + '</h1></a></div>';
                            html += '<div class="content-desc shuang">' + r.description + '</div>';
                            html += '<div class="content-about"><div class="about-left"><span>' + r.updatetime + '</span></div></div></div></div>';
                        });
                        page++;
                        $(".more_list_page_2").attr('page', page);
                        $(".more_list_page_2").parent().parent().find(".article-list").append(html);
                    } else {
                        setTimeout(function () {
                            $(".more_list_page_2").fadeOut("slow");
                        }, 2000);
                        $(".more_list_page_2").attr('non', 1);
                    }
                    list_loading = false;
                }
            });
        }
        else {
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'jsonp',
                jsonpCallback: "jsonpshowdata",
                data: dt,
                success: function (data) {
                    var html = '';
                    if (data.status == 1) {
                        $.each(data.data, function (k, r) {
                            if (!r.catname) r.catname = '';
                            html += '<div class="list-item"><div class="img"><a href="' + r.url + '"><img src="' + r.thumb + '" alt="' + r.title + '"></div>';
                            html += '<div class="content"><div class="content-title"><div class="title-tag"><a href="' + r.caturl + '">' + r.catname + '</a></div>';
                            html += '<a href="' + r.url + '"><h1 class="dan" title="' + r.title + '">' + r.title + '</h1></a></div>';
                            html += '<div class="content-desc shuang">' + r.description + '</div>';
                            html += '<div class="content-about"><div class="about-left"><span>' + r.updatetime + '</span></div></div></div></div>';
                        });
                        page++;
                        $(".more_list_page_2").attr('page', page);
                        $(".more_list_page_2").parent().parent().find(".article-list").append(html);
                    } else {
                        setTimeout(function () {
                            $(".more_list_page_2").fadeOut("slow");
                        }, 2000);
                        $(".more_list_page_2").attr('non', 1);
                    }
                    list_loading = false;
                }
            });
        }
   	})
})

$(function() {
    var wHeight = $(window).height();
    if(wHeight<600){
    	$(".piaofu-left").css("top","4%");
    	$(".piaofu-left2").css("top","52%");
    	$(".piaofu-right").css("top","4%");
    	$(".piaofu-right2").css("top","52%");
    }
});


//特殊代码
tyMap = window.tyMap || {};
function tyViaJs(locationId) {
    var _f = undefined;
    var _fconv = 'tyMap[\"' + locationId + '\"]';
    try {
        _f = eval(_fconv);
        if (_f != undefined) {
            _f()
        }
    } catch(e) {}
}
function tyLoader(closetag) {
    var tyTest = null,
    tyTestPos = document.getElementsByTagName("span");
    for (var i = 0; i < tyTestPos.length; i++) {
        if (tyTestPos[i].className == "tyTestPos") {
            tyTest = tyTestPos[i];
            break
        }
    }
    if (tyTest == null) return;
    if (!closetag) {
        document.write("<span id=tyTestPos_" + tyTest.id + " style=display:none>");
        tyViaJs(tyTest.id);
        return
    }
    document.write("</span>");
    var real = document.getElementById("tyTestPos_" + tyTest.id);
    for (var i = 0; i < real.childNodes.length; i++) {
        var node = real.childNodes[i];
        if (node.tagName == "SCRIPT" && /closetag/.test(node.className)) continue;
        tyTest.parentNode.insertBefore(node, tyTest);
        i--
    }
    tyTest.parentNode.removeChild(tyTest);
    real.parentNode.removeChild(real)
}

//全站底部
tyMap['1'] = function() {

document.writeln("<div style=\'display:none\'>");

//百度自动推送js
document.writeln("<script>");
document.writeln("(function(){");
document.writeln("var bp = document.createElement(\'script\');");
document.writeln("var curProtocol = window.location.protocol.split(\':\')[0];");
document.writeln("if (curProtocol === \'https\') {");
document.writeln("bp.src = \'https://zz.bdstatic.com/linksubmit/push.js\';");
document.writeln("}else {");
document.writeln("bp.src = \'http://push.zhanzhang.baidu.com/push.js\';");
document.writeln("}");
document.writeln("var s = document.getElementsByTagName(\'script\')[0];");
document.writeln("s.parentNode.insertBefore(bp, s);");
document.writeln("})();");
document.writeln("</script>");

(function(){
var el = document.createElement("script");
el.src = "https://sf1-scmcdn-tos.pstatp.com/goofy/ttzz/push.js?0f6a3ce7f7eabcb38aac4b46cd178952b88ffc0b7bbef144be5ab6caf810a3105be956f20ca37b49806bec64d92729d0da1a2b3e942504c4273acad3e646741d2b8d7c8c6655c9b00211740aa8a98e2e";
el.id = "ttzz";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(el, s);
})(window)
}


//全站顶部通栏
tyMap['3'] = function() {
    //广告
	if(window.location.href.indexOf('www')>-1 && window.location.href.indexOf('/center') == -1 && window.location.pathname!='/'){
	    var astr = "<div class='ct1180 mt-20'>";

		astr += "<div style='margin-bottom: 5px;'>";
		astr += "<a href='javascript:void(0);'  class='ad_content' rel='nofollow' style='margin-right: 15px;'><img src='static/picture/410x70.jpg' /><span class='ad_tip'>广告</span></a>";
        
		//ycl
		// astr += "<a href='' target='_blank' class='ad_content'  style='margin-right: 15px;' rel='nofollow'><img src='//{XFM_DOMAIN}/statics/v4/aimg/ypz410-70.jpg' /><span class='ad_tip'>广告</span></a>";
        //cypz
		// astr += "<a href='' target='_blank' class='ad_content' rel='nofollow'><img src='//{XFM_DOMAIN}/statics/v4/aimg/cypz410-70.jpg' /><span class='ad_tip'>广告</span></a>";
		// astr += "</div>";

        // astr += "<div style='margin-bottom: 5px;'>";
		//申报
        // astr += "<a href='https://6121678.com/#/home?code=9380646' target='_blank' class='ad_content'  style='margin-right: 15px;' rel='nofollow'><img src='//{XFM_DOMAIN}/statics/v4/aimg/570756.com.jpg' /><span class='ad_tip'>广告</span></a>";
        astr += "<a href='javascript:void(0);'  class='ad_content' rel='nofollow' ><img src='static/picture/410x70.jpg' /><span class='ad_tip'>广告</span></a>";
        
		// astr += "<a href='https://178367.com/#/home?code=0138348' target='_blank'  class='ad_content' rel='nofollow'><img src='/statics/v4/aimg/178367.com.png' width='400' height='70' /><span class='ad_tip'>广告</span></a>";
        astr += "</div>";

		astr += "</div>";
        $('.main-content .main-right').css('margin-top','-165px');
		document.writeln(astr);
	}
}

//列表热门文章上方
tyMap['5'] = function() {
}
//列表热点专题上方
tyMap['6'] = function() {

}
//列表热点专题下方
tyMap['7'] = function() {
	
}
//列表页底部预留
tyMap['8'] = function() {
}

//内容页正文下方
tyMap['10'] = function() {
	
}

//内容页热点专题上方
tyMap['11'] = function() {
	// var host = window.location.host;
	// if(host.indexOf("news")>=0){
	// }else{
		// let str = "<a href='https://fenshen-1257706462.cos.ap-guangzhou.myqcloud.com/zhicheng.apk'><img src='//{XFM_DOMAIN}/statics/v4/aimg/zcapp.jpg' style='width:100%'></a>";
		// document.writeln(str);
	// }
}


//内容页热点专题下方
tyMap['13'] = function() {

}
//内容页评论上方
tyMap['14'] = function() {
}
//内容页评论下方
tyMap['15'] = function() {

}
//内容页标签下
tyMap['16'] = function() {
	// document.writeln("<script src='//1250.mbai.cn/books.php?id=1267'></script>");
}
//列表中第三条
tyMap['20'] = function() {
	
}

//右侧快讯上方
tyMap['21'] = function() {

}

$(function(){
	//var str = '<div class=\'piaofu-left\'><span class=\'piaofu-left-biaoshi2\'>广告</span><span class=\'guanbi\'>× 关闭</span><a href=\'https://www.pz2777.com/index?recommendCode=1143\' target=\'_blank\' class="seahotid" data="355"><img src=\'//{XFM_DOMAIN}/statics/v4/aimg/c100-180.jpg\' alt=\'\'></a></div>';
	//var str = '<div class=\'piaofu-right\'><span class=\'piaofu-left-biaoshi2\'>广告</span><span class=\'guanbi2\'>× 关闭</span><a href=\'https://www.pz4666.com/index?recommendCode=1226\' target=\'_blank\' class="seahotid" data="357"><img src=\'//{XFM_DOMAIN}/statics/v4/aimg/y100-180.jpg\' alt=\'\'></a></div>';
	//var str = '<div class=\'piaofu-left2\'><span class=\'piaofu-left-biaoshi2\'>广告</span><span class=\'guanbi3\'>× 关闭</span><a href=\'https://jxpz888.com/\' target=\'_blank\' class="seahotid" data="356"><img src=\'//{XFM_DOMAIN}/statics/v4/aimg/j100-180.jpg\' alt=\'\'></a></div>';
	//str += '<div class=\'piaofu-right2\'><span class=\'piaofu-left-biaoshi2\'>广告</span><span class=\'guanbi4\'>× 关闭</span><a href=\'https://jlpz8.com/\' target=\'_blank\' class="seahotid" data="358"><img src=\'//{XFM_DOMAIN}/statics/v4/aimg/jl100-180.jpg\' alt=\'\'></a></div>';
	//$('body').append(str);
	$('.guanbi').click(function(){
		$('.piaofu-left').hide();
	});
	$('.guanbi2').click(function(){
		$('.piaofu-right').hide();
	})
	$('.guanbi3').click(function(){
		$('.piaofu-left2').hide();
	});
	$('.guanbi4').click(function(){
		$('.piaofu-right2').hide();
	})
})
/*热门标签*/
$(function(){
    $.ajax({
        url: "//{XFM_DOMAIN}/datacache/labels.js",
        type: 'GET',
        dataType: 'jsonp',
        jsonpCallback: "success_jsonpCallbacklabels",
        data: {},
        success: function (data) {
            let html='';
                html +=' <div class="slist show"><ul>';
            $.each(data, function(k, r) {
                if(k>11) return false;
                html += '<li><a target="_blank" href="/label-'+r.catid+'-'+r.id+'-1.html">'+r.name+'</a></li>\n';
            });
            html +='</ul></div>';
            console.log(data.length)
            if(data.length>12) {
                html += '<div class="slist"><ul>';
                $.each(data, function (kk, rr) {
                    if (kk < 12) return true;
                    html += '<li><a target="_blank" href="/label-' + rr.catid + '-' + rr.id + '-1.html">' + rr.name + '</a></li>\n';
                });
                html += '</ul></div>';
            }
            $('.label-list').html(html);
        }
    })
    
    // var adstr = '<a href="http://www.guyouzhan.com/baozhang" target="_blank"><span style="position: absolute;display: inline-block;background: #eee;">广告</span><img src="//{XFM_DOMAIN}//statics/v4/aimg/guyouzhan.com.jpg" alt="广告" style="width: 100%"></a>';
    // $('.shangzheng').after(adstr)

})


