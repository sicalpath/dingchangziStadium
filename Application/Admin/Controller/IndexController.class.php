<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

  public function _initialize(){
    if(session('is_business') != 1){
      session(null);
      $this->redirect('Login/login');
      die();
    }
  }
  public function index(){
    	$this->display();    
  }
  public function history(){

      $map['dcz_order.stid'] = session('stid');
      $orders = M('order') -> join('dcz_sportstype on dcz_sportstype.stid = dcz_order.stid') ->
      where($map) -> order('ordertime desc')-> select();
      for ($i=0; $i < count($orders) ; $i++) { 
        # code...
        $json = json_decode($orders[$i]['fsids']);
        $orders[$i]['fsids'] = '';
        foreach ($json as $jns ) {
          # code...
          $jn = explode(':', $jns);
          $orders[$i]['fsids'] .= $jn[0] . '号场地:'. (intval($jn[1])%14+8) .':00-' . (intval($jn[1])%14+9) .':00<br />';
        }
      }
      $this->assign('orders',$orders);
      $this->display();

  }

}