<?php get_header(); ?>
        
	<div style="margin-left: 10px;color:#ff0000;font-size:30px;font-weight: bolder;">打开微信app扫码支付</div><br/>
	<img alt="支付二维码" src="<?php echo WCC_WEPAY_PLUGIN_URL.'inc/' ?>qrcode.php?data=<?php echo urlencode(urldecode($_GET['url']));?>" style="width:150px;height:150px;"/>
        <div id="myDiv" style="color:#ff0000;"></div>
        <div id="timer">0</div>
        
        <?php
//            echo "qrcode-show page.<br />";
//            echo "qrcode-show page qrcode_url is:".$_GET['url'].'<br />';
//            echo "qrcode-show page out_trade_no is:".$_GET['out_trade_no'].'<br />';
//            echo "qrcode-show page return_url is:". urldecode($_GET['return_url']) .'<br />';
//        ?>
        
        <script type="text/javascript">
            
            //设置每隔1000毫秒执行一次load() 方法  
    var myIntval=setInterval(function(){load()}, 3000);  
    function load(){  
       document.getElementById("timer").innerHTML=parseInt(document.getElementById("timer").innerHTML)+1; 
        var xmlhttp;    
        if (window.XMLHttpRequest){    
            // code for IE7+, Firefox, Chrome, Opera, Safari    
            xmlhttp=new XMLHttpRequest();    
        }else{    
            // code for IE6, IE5    
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");    
        }    
        xmlhttp.onreadystatechange=function(){    
            if (xmlhttp.readyState==4 && xmlhttp.status==200){    
                trade_state=xmlhttp.responseText;  
                if(trade_state=='SUCCESS'){  
                    document.getElementById("myDiv").innerHTML='支付成功';  
                    //alert(transaction_id);  
                    //延迟3000毫秒执行tz() 方法
                    clearInterval(myIntval);  
                    setTimeout("location.href='<?php echo urldecode($_GET['return_url']); ?>'", 2000);

                }else if(trade_state=='REFUND'){  
                    document.getElementById("myDiv").innerHTML='转入退款'; 
                    clearInterval(myIntval); 
                }else if(trade_state=='NOTPAY'){  
                    document.getElementById("myDiv").innerHTML='请扫码支付';  
                      
                }else if(trade_state=='CLOSED'){  
                    document.getElementById("myDiv").innerHTML='已关闭';  
                    clearInterval(myIntval);
                }else if(trade_state=='REVOKED'){  
                    document.getElementById("myDiv").innerHTML='已撤销';  
                    clearInterval(myIntval);
                }else if(trade_state=='USERPAYING'){  
                    document.getElementById("myDiv").innerHTML='用户支付中';  
                }else if(trade_state=='PAYERROR'){  
                    document.getElementById("myDiv").innerHTML='支付失败';
                    clearInterval(myIntval); 
                }  
                 
            }    
        }    
        //orderquery.php 文件返回订单状态，通过订单状态确定支付状态
        xmlhttp.open("POST","<?php echo get_option( 'wepay_order_query_url' );  ?>",false);
        //下面这句话必须有    
        //把标签/值对添加到要发送的头文件。    
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlhttp.send("out_trade_no=<?php echo $_GET['out_trade_no']; ?>");  
        } 
            
        </script>
	
<?php get_footer(); ?>







