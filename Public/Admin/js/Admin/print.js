//打印票据

    function printOrder(data) {  
        AddTitle();
        var sites = '活动时间:\n';
        var iCurLine=50;//标题行之后的数据从位置80px开始打印   
        LODOP.ADD_PRINT_LINE(iCurLine,14,iCurLine,510,0,1);
        LODOP.ADD_PRINT_TEXT(iCurLine+5,65,290,30,"收费凭证");
        LODOP.SET_PRINT_STYLEA(1,"FontSize",13);
        LODOP.SET_PRINT_STYLEA(1,"Bold",1);
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"订单号:"+data[0]['oid']);
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"预定日期:"+data[0]['ordertime'].substr(0,10));
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"人员类型:学生");
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"姓名:"+data[0]['name']);
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"处理人:韩陌陌");
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"运动类型:网球");
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"活动日期:"+data[0]['usetimes']);
        iCurLine=iCurLine+20;
        for (var i = 0; i < data[1].length; i++) {
            sites += data[1][i]+"\n";
        };
        console.log(sites);
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20*data[1].length,sites);
        iCurLine=iCurLine+15*data[1].length+10;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"金额:"+data[0]['price']);
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"----------------------------");
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+5,15,200,20,"咨询电话:27891047,27405101");
        iCurLine=iCurLine+20;
        LODOP.ADD_PRINT_TEXT(iCurLine+15,15,200,20,"打印时间："+(new Date()).toLocaleDateString()+"\n"+(new Date()).toLocaleTimeString());
        LODOP.SET_PRINT_STYLEA(2,"FontSize",4);
        iCurLine=iCurLine+20;           
        LODOP.SET_PRINT_PAGESIZE(3,580,80,"");//这里3表示纵向打印且纸高“按内容的高度”；1385表示纸宽138.5mm；45表示页底空白4.5mm
        LODOP.print();  
    };

    function AddTitle(){    
        LODOP=getLodop();  
        LODOP.PRINT_INIT("订场子网");
        LODOP.ADD_PRINT_TEXT(15,20,290,30,"订场子网预定信息");
        LODOP.SET_PRINT_STYLEA(1,"FontSize",13);
        LODOP.SET_PRINT_STYLEA(1,"Bold",1);


    }; 