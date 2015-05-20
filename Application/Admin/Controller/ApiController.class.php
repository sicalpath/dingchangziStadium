<?php
namespace Admin\Controller;
use Think\Controller;
class ApiController extends Controller {

    public function _initialize(){
    if(session('is_business') != 1){
      session(null);
      $this->redirect('Login/login');
      die();
    }
  }
    public function index(){
    	echo "";   
        }

    //获取状态 按每一小块场地(场次)
    function getStatus(){
        $fid = I("get.fid");
        $ftime = I("get.ftime");
        $arrayName = array('status' => rand(0,1) , 'fid' => 1  ,'price' => '60');
    	echo json_encode($arrayName);
        }
        //获取状态 按每块场地某一天
    function getStatus2(){
        $arrayName = array();
        $weekday = date('w',strtotime(I("get.date")));      //0为星期日 6为星期六
        $map['stid'] = session('stid');
        $map['fid'] = intval(I('get.fid')) - 1;
        $arrayName = array();
        for ($k = 0 ; $k < 3; $k++) { 
            # code...
            $map['utype'] = $k;
            $fids = M('field') -> join ('dcz_fstatus on dcz_field.ffid = dcz_fstatus.ffid and ftime =\''.I("get.date").'\'','LEFT')-> where($map) -> order('fno') -> select();
            $j = 0;
            for ($i= 14 * $weekday; $i < 14 * ($weekday + 1) ; $i++) { 
                //...............................
            	//$arrayName[$j] =  ( !isset($arrayName[$j]) || $fids[$i]['status'] == '0' ) ? (array('status' => 0 ,'price' => substr($fids[$i]['price'],0,2) )) : $arrayName[$j];
                ( ( $fids[$i]['status'] == '0' || $fids[$i]['locked'] == '1') && ( $arrayName[$j] = array('status' => 0 ,'price' => substr($fids[$i]['price'],0,2) )) ) || ( !isset($arrayName[$j]) && ( $arrayName[$j] = array('status' => 1 ,'price' => substr($fids[$i]['price'],0,2) ) )); 
                $j++;
        	}
        }
    	echo json_encode($arrayName);
      }
      //用于切换日期 （待优化冗余信息）
    function getDateStatus(){
        $weekday = date('w',strtotime(I("get.date"))) ;
        $arrayName = array();
        $map['stid'] = session('stid');
        $fnum = M('field') -> count('distinct fid');        
        for ($i=0; $i < $fnum ; $i++) { 
            $map['fid'] = $i;
            $fids = M('field') -> join ('dcz_fstatus on dcz_field.ffid = dcz_fstatus.ffid','LEFT')-> where($map) -> order('fno') -> select();
            //$arrayName[$i]['fid'] = $i + 1;
            for ($j= 14 * $weekday; $j < 14 * ($weekday+1) ; $j++) { 
                # code...
                $arrayName[$i][] = array('ftime' => $j , 'status' => $fids[$j]['status'] ? $fids[$j]['status'] : 1 , 'price' => $fids[$j]['price'] ,'fid' => $i + 1);
            }
            
        }
        //dump($arrayName);
        echo json_encode($arrayName);
        
    }
    //同步前后台场地信息(废弃)
    function getSyncStatus(){

        $status = array();

        $map['ftime'] = I('post.date');

        $res = M('fstatus') -> where($map) -> select();

        $status[0]['fid'] = rand(1,8);

        $status[0]['date'] = '4/1'.rand(4,9);

        $status[0]['ftime'] = rand(0,10);

        echo json_encode($status);

    }
    //验证订单
    function verify(){

        $map['verify'] = I('post.verify');
        $map['phone'] = I('post.phone');
        $map['stid'] = session('stid');  // 不安全 session('stid');
        $res = M('order') -> where($map) ->find();
        $fsids = json_decode($res['fsids']);
        foreach ($fsids as $jns ) {
                # code...
                $jn = explode(':', $jns);         //2月1日（周日）8:00-9:00
                $msg .= $jn[0] . '号场地:'. (intval($jn[1])%14+8) .':00-' . (intval($jn[1])%14+9) .':00;';
        }
        if($res)
            $this -> ajaxReturn(json_encode($res).'|'.$msg);
        else
            $this -> ajaxReturn("false");
        }
        //创建订单 用于后台现场预定
    function createOrder(){

      //根据传递的场次查询是否已经锁定       
        if(I('post.orders'))   {

            $map['phone'] = I('post.phone');

            $map['name'] = I('post.name');

            $map['price'] = I('post.price');

            $orders = I('post.orders');

            $stid = session('stid');

            if(count($orders) > 5){
                echo "所选场次过多";
                die();
            }
            $oid = 'F'.$map['uid'].NOW_TIME;
            foreach ($orders as $order) {
                # code...
                $order = explode(":",$order);
                $data['fid'] = intval($order[0])-1;
                $data['fno'] = $order[1];
                $data['stid'] = $stid;
                $data['locked'] = 0;
                $field = M('field') -> where($data) -> find();
                if($field == null){
                    $this->ajaxReturn("false");
                    die();
                }
                else{
                    $data2['ffid'] =  $field['ffid'];
                    $data2['ftime'] = I('post.date');
                    $is_exist = M('fstatus') -> where ($data2) -> find();
                    if($is_exist != null){
                        $this -> error('存在已被预订时间');
                        die();
                   }
                   else{
                        $data2['oid'] = $oid;
                        $data2['status'] = '0' ; //状态 0为未付款 1为付款未消费 2为已消费 3为已退 ……
                        $res = M('fstatus') -> add($data2);
                        if(!$res){
                         $this->ajaxReturn("false");
                        }
                   }
               }
            }
            $map['usetimes'] = I('post.date');

            $map['stid'] = $stid;

            $map['uid'] = 0;
            
            $map['fsids'] = json_encode($orders);

            $map['oid'] = $oid ;        //生产订单编号

            $map['status'] = 1 ;        //订单状态 0为未付款 1为已付款 2为已消费完成

            $map['operator'] = session('name');

            $res = M('order') -> add($map);
            
            if($res){
                $st['stid'] = $stid;
                $stadium = M('sportstype') -> join('dcz_stadium on dcz_stadium.sid = dcz_sportstype.sid') -> where($st) -> find();
                $map['id'] = $res;
                $order = M('order') -> where($map) -> find();
                $this->ajaxReturn("true");
            }
            else
                $this->ajaxReturn("false");
        }
        else{
            $this->ajaxReturn("false");
        }
    }
    function lockField(){
        $orders = I('post.orders');

        $map['stid'] = session('stid');

        foreach ($orders as $order) {

            $order = explode(":",$order);

            $map['fid'] = intval($order[0])-1;

            $map['fno'] = $order[1];

            $data['locked'] = 1;

            if( !(M('field')->where($map)->save($data)) )   $this->ajaxReturn("失败");
        }

    }
  function testOrder(){
    dump($_POST);
    $orders = array();

    $orders = I('post.orders');

    dump($orders);
  }
}