<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {

  public function login(){
    if(session('is_business') == 1){
      $this->redirect('Index/index');
    }
    if($_POST){
        if(empty(I('post.username'))||empty(I('post.password'))){
            $this -> error("账号或密码不能为空");
          }
        $map['username'] = I('post.username');
        $who = M('buser') -> join('dcz_stadium on dcz_stadium.bid = dcz_buser.bid') ->
        join('dcz_sportstype on dcz_sportstype.sid = dcz_stadium.sid')-> where($map) ->find();
        if($who['password'] == I('post.password')){
          session('stid',$who['stid']);
          session('bid',$who['bid']);
          session('name',$who['name']);
          session('is_business',1);
          //$this->ajaxReturn("true");
          $this->success('登录成功','/index.php');
        }
        else{
          session(null);
          //$this->ajaxReturn("false");
          $this->error('登录失败,请检查账号或密码');
        }
    }
    else{
      $this->display();
    }
  }
  public function logout(){
    session(null);
    $this->redirect('Login/login');
  }
}