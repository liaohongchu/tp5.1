<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\facade\Session;

class Admin extends Base
{
	/**
	 * 管理员列表
	 */
	public function adminlist(){
		
        // $this->assign('list', $list);
        // $this->assign('page', $page); 
    	
    	return view();

	}
    public function adminlistJson(){
        $getParam=array();
        $where=array();
        $list = db("admin")->where($where)->order("id desc")->paginate(10, false, $getParam);

        $page = $list->render();


        $list  = $list ->all();
        foreach ($list as $key => $value) {
            $list[$key]['role_name']=$this->getRoleName($value['role_id']);
            if($value['status']==1){
                $list[$key]['status'] = '开启中';
            }else{
                $list[$key]['status'] = '已禁用';
            }
            
        }

        $msg = array(
            'code'=>0,
            'msg'=>'ok',
            'count'=>count($list),
            'data'=>$list
        );
        echo json_encode($msg); exit;
    }

	public function getRoleName($role_id){
		$model=db('admin_role')->find($role_id);
		return $model['role_name'];

	}

	/**
	 * 添加管理员
	 */
	public function adminadd(){

        $id=intval(input('id'));
        $model=db('admin')->find($id);

        if($id>0){
            $this->enterAuth('admin/adminedit');

        }else{
            $this->enterAuth('admin/adminadd');
        }

        if($_POST){
            $data=$_POST;
            //print_r($data); exit;
        
            if($id>0){

                if($data['admin_password']){
                    $data['admin_password']=md5($data['admin_password']);
                }else{
                    $data['admin_password']=$model['admin_password'];
                }

                db('admin')->where("id='".$id."'")->update($data);
                echo json_encode(array('code'=>0,'msg'=>'修改成功')); exit();
            }else{

                if(!$data['admin_user'] || !$data['admin_password'] ){
                    echo json_encode(array('code'=>400,'msg'=>'名称和密码不能为空')); exit();
                }
                if(!$data['status']){
                    $data['status']=1;
                }

                $data['admin_password']=md5($data['admin_password']);
                
                db('admin')->insert($data);
                echo json_encode(array('code'=>0,'msg'=>'添加成功')); exit();
            }
            

        }

        //读取角色
        $roleArr=db('admin_role')->select();
        $this->assign('roleArr',$roleArr);

        
        $this->assign('model', $model);
        return view();

    }

    /**
     * 管理员删除
     */
    public function admindel(){
        $id=intval(input('id'));

        //$this->enterAuth('admin/admindel','json');

        //echo getcwd();
        if($id){
            $model=db('admin')->find($id);
       
            $ret=db('admin')->where("id='".$id."'")->delete();
            if($ret){
                echo json_encode(array('code'=>0,'msg'=>'删除成功')); exit();
            }
            

        }
    }


    /**
     * 密码修改
     * @return [type] [description]
     */
    public function pwdedit()
    {

    	if($_POST){
             
            $data=$_POST;

            if(!isset($data['pwd1']) || !isset($data['pwd2'])){
                echo json_encode(array('code'=>-1,'msg'=>'密码不能为空')); exit;
            }
            if($data['pwd1'] !=$data['pwd2'] ){
                echo json_encode(array('code'=>-1,'msg'=>'确认密码错误')); exit;
            }

            $password = $data['password'];
            if($this->adminModel['admin_password']!=md5($data['password'])){
                echo json_encode(array('code'=>-1,'msg'=>'原密码错误')); exit;
            }
            if(strlen($data['pwd1'])<3){
                echo json_encode(array('code'=>-1,'msg'=>'密度长度太短')); exit;
            }
            
            $updata=array();
            $updata['admin_password']=md5($data['pwd1']);
            db('admin')->where("id='".$this->admin_id."'")->update($updata);
            
            echo json_encode(array('code'=>0,'msg'=>'success')); exit;

        }
        
        $model=$this->adminModel;
        //print_r ($model); exit;

        $this->assign('model', $model);
    	
    	return view();
    }

    /**
	 * 角色列表
	 */
	public function rolelist(){
		/*
		$getParam=array();
		$where=array();
		$list = db("admin_role")->where($where)->order("id desc")->paginate(10, false, $getParam);

		$page = $list->render();

        //print_r ($page);

        $this->assign('list', $list);
        $this->assign('page', $page);*/
    	
    	return view();

	}
    public function rolelistJson(){
        
        $getParam=array();
        $where=array();
        $list = db("admin_role")->where($where)->order("id desc")->paginate(10, false, $getParam);

        $page = $list->render();

        $list  = $list ->all();

        $msg = array(
            'code'=>0,
            'msg'=>'ok',
            'count'=>count($list),
            'data'=>$list
        );
        echo json_encode($msg); exit;

    }

	/**
	 * 添加角色
	 */
	public function roleadd(){

        $id=intval(input('id'));
        $model=db('admin_role')->find($id);

        //var_dump($model); 

        if($_POST){
            $data=$_POST;
            
            $data['acls']=array_unique($data['acls']);
            
            if(isset($data['acls'])){
            	$aclList=implode(",",$data['acls']);
            }else{
            	$aclList='';
            }
       
            $data2=array();
            
            if($id>0){
                $data2['role_name']=$data['role_name'];
                $data2['mark']=$data['mark'];
                $data2['aclList']=$aclList;

                db('admin_role')->where("id='".$id."'")->update($data2);
                echo json_encode(array('code'=>0,'msg'=>'修改成功')); exit();
            }else{

               
                $data2['role_name']=$data['role_name'];
                $data2['mark']=$data['mark'];
                $data2['aclList']=$aclList;

                db('admin_role')->insert($data2);
                echo json_encode(array('code'=>0,'msg'=>'添加成功')); exit();
            }
            

        }

        //拥有的权限
        $aclHas=explode(",", $model['aclList']);
        

        //print_r ($aclHas);

        $this->assign('aclHas', $aclHas);


        
        $this->assign('model', $model);
        return view();

    }

    /**
     * 角色删除
     */
    public function roledel(){
        $id=intval(input('id'));

        //echo getcwd();
        if($id){
            $model=db('admin_role')->find($id);
       
            $ret=db('admin_role')->where("id='".$id."'")->delete();
            if($ret){
                echo json_encode(array('code'=>0,'msg'=>'删除成功')); exit();
            }
            

        }
    }

    /**
	 * 菜单列表
	 */
	public function menulist(){
		
    	return view();

	}
    public function menulistJson(){
        
        $getParam=array();
        $where=array();
        if(input('pid')){
            $where['pid']=input('pid');
        }else{
            $where['pid']=0;
        }
        $pid=input('pid');

        $list = $this->menutree();
     
        foreach ($list as $key => $value) {
            if($value['status']==1){
                $list[$key]['status'] = '开启中';
            }else{
                $list[$key]['status'] = '已禁用';
            }
        }
        $list = array_values($list);

        $msg = array(
            'code'=>0,
            'msg'=>'ok',
            'count'=>count($list),
            'data'=>$list
        );
        echo json_encode($msg); exit;

    }
    
    protected function menutree($pid=0){
        $where['pid'] = $pid;
        $res = db('admin_menu')->where($where)->select();;
        if(empty($res)) return false;
        foreach($res as $k=>$v){
            //$result[$v['id']] = $v;
            $result[$v['id']]['id'] = $v['id'];
            $result[$v['id']]['menu_name'] = $v['menu_name'];
            $result[$v['id']]['pid'] = $v['pid'];
            $result[$v['id']]['status'] = $v['status'];
            $rf = $this->menutree($v['id']);
            if($rf){
                foreach($rf as $k=>$vv){
                    //$result[$vv['id']] = $vv;
                    $result[$vv['id']]['id'] = $vv['id'];
                    $result[$vv['id']]['menu_name'] = "   |- ".$vv['menu_name'];
                    $result[$vv['id']]['pid'] = $vv['pid'];
                    $result[$vv['id']]['status'] = $vv['status'];
                }
            }
        }
        return $result;
    }
	/**
	 * 添加菜单
	 */
	public function menuadd(){

        $id=intval(input('id'));
        $pid=intval(input('pid'));
        $model=db('admin_menu')->find($id);
        if($model['pid']){
        	$this->assign('pid', $model['pid']);

        }else{
        	$this->assign('pid', $pid);
        }
     
        //var_dump($model); 

        if($_POST){
            $data=$_POST;
            //print_r ($data); exit;
            if(!$data['menu_name']){
            	echo json_encode(array('code'=>400,'msg'=>'名称不能为空')); exit();
            }
   
            if($id>0){

                db('admin_menu')->where("id='".$id."'")->update($data);
                echo json_encode(array('code'=>0,'msg'=>'修改成功')); exit();
            }else{

                
                if(!$data['status']){
                    $data['status']=1;
                }

               
                db('admin_menu')->insert($data);
                echo json_encode(array('code'=>0,'msg'=>'添加成功')); exit();
            }
            

        }

        //读取角色
        $menuArr=$this->CycleData();
        $this->assign('menuArr',$menuArr);

        
        $this->assign('model', $model);
        return view();

    }
    //多级分类下拉选择展示
    protected function CycleData($pid=0){
        $where['pid'] = $pid;
        $res = db('admin_menu')->where($where)->select();;
        if(empty($res)) return false;
        foreach($res as $k=>$v){
            $result[$v['id']]['id'] = $v['id'];
            $result[$v['id']]['menu_name'] = $v['menu_name'];
            $result[$v['id']]['pid'] = $v['pid'];
            $rf = $this->CycleData($v['id']);
            if($rf){
                foreach($rf as $k=>$vv){
                    $result[$vv['id']]['id'] = $vv['id'];
                    $result[$vv['id']]['menu_name'] = "   |- ".$vv['menu_name'];
                    $result[$vv['id']]['pid'] = $vv['pid'];
                }
            }
        }
        return $result;
    }

    /**
     * 菜单删除
     */
    public function menudel(){
        $id=intval(input('id'));

        //echo getcwd();
        if($id){
            $model=db('admin_menu')->find($id);
       
            $ret=db('admin_menu')->where("id='".$id."'")->delete();
            if($ret){
                echo json_encode(array('code'=>0,'msg'=>'删除成功')); exit();
            }
            

        }
    }

    
}
