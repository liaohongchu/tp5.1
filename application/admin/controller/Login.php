<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Session;

class Login extends Controller
{
    public function Login()
    {

    	if($_POST){
            $yzmcode=input('yzmcode');
            /*
            $captcha = new \think\captcha\Captcha();
            $result=$captcha->check($yzmcode);
            if($result===false){
                $this->error('验证码错误');
            }*/

    		if(!$_POST['username'] || !$_POST['password'] ){
    			$this->error('用户名和密码错误');
    		}
    		$admin = Db::table('db_admin')->where("admin_user='".trim($_POST['username'])."'")->find();
    		if(!$admin){
    			$this->error('用户名错误');
    		}
    		$pwd=md5(trim($_POST['password']));
    		if($admin['admin_password'] != $pwd ){
    			$this->error('密码错误');
    		}

            $params=array(
                'id'=>$admin['id'],
                'username'=>$admin['admin_user'],
            );
            $token=md5( http_build_query($params) );
            $params['token']=$token;

    		Session::set('login_admin',$params);
            Session::set('login_token',$token);
            //$this->success('登陆成功', 'index/index');
            $this->redirect('index/index',302);

    	}
    	return view();
    }

    /**
     * 退出
     */
    public function logout(){
        Session::delete('login_admin');
        Session::delete('login_token');
        $this->success('退出成功', 'Login/login');
    }

    /**
     * 显示验证码
     */
    public function showyzm(){
        $captcha = new \think\captcha\Captcha();
        $captcha->imageW=121;
        $captcha->imageH = 32;  //图片高
        $captcha->fontSize =14;  //字体大小
        $captcha->length   = 4;  //字符数
        $captcha->fontttf = '5.ttf';  //字体
        $captcha->expire = 30;  //有效期
        $captcha->useNoise = false;  //不添加杂点
        return $captcha->entry();

    }

}
