<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
        return view();
    }
    public function welcome()
    {
        return view(); 
    }
    public function menuapi(){
    	$menu1 = $this->menu[0];
    	$menu2 = $this->menu[1];
    	//print_r($this->menu); exit;
    	
    	foreach ($menu1 as $key => $value) {
    		$arr1[$key]['title'] = $value['menu_name'];
			$arr1[$key]['href'] = "/index.php/admin/".$value['acl'];
			$arr1[$key]['icon'] = "fa fa-file-text";
			$arr1[$key]['target'] = "_self";

            foreach ($menu2[$value['id']] as $key2 => $value2) {
            	$arr2['title'] = $value2['menu_name'];
				$arr2['href'] = "/index.php/admin/".$value2['acl'];
				$arr2['icon'] = "fa fa-tachometer";
				$arr2['target'] = "_self";
            	$arr1[$key]['child'][$key2] = $arr2;
            }
			
    	}

    	//print_r( json_encode(($arr1)) ); exit;
		

    	$json='{
		  "clearInfo": {
		    "clearUrl": "api/clear.json"
		  },
		  "homeInfo": {
		    "title": "首页",
		    "icon": "fa fa-home",
		    "href": "/index.php/admin/index/welcome.html"
		  },
		  "logoInfo": {
		    "title": "后台管理系统",
		    "image": "/ui/images/logo.png",
		    "href": ""
		  },
		  "menuInfo": {
		    "currency": {
		      "title": "常规管理",
		      "icon": "fa fa-address-book",
		      "child": 

		      '.json_encode(($arr1)).'

		    }
		  }
		}';

		return $json;
    }
}
