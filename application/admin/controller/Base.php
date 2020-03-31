<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Session;
use think\Config;
use think\facade\Request;

//后台管理基础类
class Base  extends Controller
{
    protected $adminModel;
    protected $admin_id; //管理员ID
    protected $role_id;  //角色ID
    protected $roleModel; //角色信息
    protected $adminurl='http://admin.zengjiuw.com';
    protected $menu = [];
    
    public function __construct()
    {
        //调用父类的构造：执行父类之后再用自己的
        parent::__construct(); 
        //验证权限
        $params=Session::get('login_admin');
        $login_token=Session::get('login_token');
        $this->adminModel=Db::table('db_admin')->where("id='".$params['id']."'")->find();
        if($this->adminModel['admin_user']!=$params['username']){
            $this->error('请先登录',url('Login/login'));
        }
        $admin=$this->adminModel;

        //print_r($admin);exit;

        $this->admin_id=$admin['id'];
        $this->role_id=$admin['role_id'];
        $paramsChe=array(
                'id'=>$admin['id'],
                'username'=>$admin['admin_user'],
        );

        $token=md5( http_build_query($paramsChe) );
        if($login_token != $token){
            $this->error('请先登录',url('Login/login'));
        }
        

        $this->roleModel=$role_info=db('admin_role')->find($this->role_id);
        $this->getMenu($role_info);
        //$this->getTitleNav();
        $this->btnIsShow();


        $this->enterAuth();

        $this->assign('adminurl', $this->adminurl);
        $this->assign('adminModel', $this->adminModel);

 
    }

    /**
     * 根据角色显示对应菜单
     * @param  [type] $role_info [description]
     * @return [type]            [description]
     */
    protected function getMenu($role_info){
       
        $menu=[];$where="";
        if($this->role_id==1){ //超级管理员，显示所有菜单
            $menu=db('admin_menu')->where("status=1 and pid=0")->select();
        }else{
            $arr=array();
            $aclSql='';
            $aclArr=explode(',',$role_info['aclList']);

            foreach ($aclArr as $key => $value) {
                if(strpos($value,'list') !== false){
                   
                    $arr[]=$value;
                }
            }

            //print_r($arr); exit;

            foreach ($arr as $key2 => $value2) {
                $aclSql.=" acl='".$value2."' or ";
            }
            $aclSql=trim(substr($aclSql,0,strlen($aclSql)-3)); 
       
            $menu=db('admin_menu')->where("status=1 and pid=0 and (".$aclSql.") ")->select();
            //echo "status=1 and pid=0 and (".$aclSql.") "; exit;
            
            $where = " and (".$aclSql.")";

        }
        
        foreach ($menu as $key => $value) {
            $menu[$key]['children']=db('admin_menu')->where("status=1 and pid='".$value['id']."' ".$where." ")->select();
            
            foreach ($menu[$key]['children'] as $k2 => $v2) {
                $menu[$key]['children'][$k2]['children']=db('admin_menu')->where("status=1 and pid='".$v2['id']."' ")->select();
            }
        }



        //print_r($menu); exit;
        
        $this->menu = $menu;
        $this->assign('menu', $menu);

    }

    /**
     * 进入权限通行证
     */
    protected function enterAuth($retType=''){
        
        
        if($this->role_id!=1){ //不是超级管理员，要通行证

            //$module = Request::instance()->module();
            $controller = Request::instance()->controller();
            $action = Request::instance()->action();
            $acl = strtolower($controller."/".$action);
            $colAuth = array('Index');
            if( in_array($controller,$colAuth) ){
                return true;
            }

            if( strstr($action, "json") ){
                return true;
            }

            $aclList=$this->roleModel['aclList'];

            $aclArr=explode(',',$aclList);
            if(!in_array($acl, $aclArr)){
                if($retType=='json'){
                    echo json_encode(array('code'=>400,'msg'=>'无权限操作')); exit;

                }else{
                    echo '无权限操作'; exit;
                }
                

            }

        }


    }

    /**
     * 具体按钮添加，修改，删除等权限判断 是否展示 
     */
    protected function btnIsShow(){
        $authArr=explode(',',$this->roleModel['aclList']);
        $btnshowArr=array();
        $this->assign('btnshowArr', $authArr);
    }

    /**
     * 根据控制器显示标题导航
     */
    protected function getTitleNav(){
        $acl=request()->controller().'/'.request()->action();
        $acl=strtolower($acl);
        $menu_info2=db('admin_menu')->where(" acl='".$acl."' ")->order("id desc")->find();
        $nav_title2=$menu_info2['menu_name'];
        $menu_info1=db('admin_menu')->where(" id='".$menu_info2['pid']."' ")->find();
        $nav_title1=$menu_info1['menu_name'];

        //echo $nav_title2; exit;
        $this->assign('nav_title2', $nav_title2);
        $this->assign('nav_title1', $nav_title1);



    }


}
