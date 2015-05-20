//购买和锁定的 

function buy(type){
    var url = "http://stadium.dingchangzi.net/index.php/Api/createOrder/status/"+type+".html";

    data = $('.book').serialize() ;//+"&price="+ $('.money-count').html();
    
    $.post(url,data,function(res){
        if(res == "true"){
            alert("成功");
            clearCart();
            $('#totalField').text(0);
            select_date($('#date').val());
        }
        else
            alert("失败");
    });
}

function lockF(url){
    data = $('.book').serialize() ;

    $.post(url,data,function(res){
        alert(res);
    });
}
