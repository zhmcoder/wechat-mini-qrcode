<?php

namespace App\Api\Controllers;

use App\Admin\Models\AppInfo;
use App\Admin\Models\ColumnInfo;
use App\Admin\Models\HomeColumn;
use App\Admin\Models\HomeColumnIds;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController{

    protected $url = "Xunji/Home/column_lists?homeColumnId=";
    protected $skipType = 6;
    protected $appUrl = '';

    public function __construct()
    {
        $this->appUrl = config('filesystems.disks.public.url').'/';
    }

    public function index()
    {
        $appId = request('app_id');
        $osType = request('os_type'); // todo 区分设备类型

        $data = [];

        // 应用信息
        $appInfo = AppInfo::query()->where('app_id', $appId)->first();

        if(!empty($appInfo)){
            // 栏目列表
            $homeColumnList = HomeColumn::query()->where('shelf_on', 1)
                ->where('show_app','like', "%".$appInfo['id']."%") // todo 此处需要优化
                ->orderBy('sort', 'asc')->orderBy('id','desc')->get()->toArray();

            foreach ($homeColumnList as $key=>$val) {
                $list = [];

                // 栏目关连专栏列表
                $homeColumnIdsList = HomeColumnIds::select('column_id')->where('home_column_id', $val['id'])->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
                if(empty($homeColumnIdsList)){
                    continue;
                }

                $homeColumnIds = []; // 专栏id列表
                foreach ($homeColumnIdsList as $k=>$v){
                    $homeColumnIds[] = $v['column_id'];
                }

                // 专栏列表
                $where = ['shelf_setting' => 0, 'is_stop_sell' => 0, 'state' => 0];
                $columnInfoModel = ColumnInfo::where($where)->whereIn('id', $homeColumnIds)
                    ->orderBy(DB::raw('FIND_IN_SET(id, "' . implode(",", $homeColumnIds) . '"' . ")"));

                $fields = ['xid as skip_target', 'img_url'];

                switch ($val['shelf_type']){
                    case 1 : // banner
                        $fields[] = 'id';
                        $columnInfoList = $columnInfoModel->select($fields)->limit($val['show_num'])->get()->toArray();
                        $list['data'] = $columnInfoList;
                        break;

                    case 2 : // 一张图 大Banner
                        $columnInfoList = $columnInfoModel->select($fields)->limit(1)->get()->toArray();

                        ($val['show_name']) ? $list['title'] = $val['name'] : '';
                        $list['hasMore'] = $val['show_more'];
                        $list['url'] = $this->url. $val['id'];
                        $list['data'] = $columnInfoList;
                        break;

                    case 3 : // 两张图 Banner2x
                    case 4 : // 三张图 Banner3x
                    case 5 : // 四张图 Banner4x
                        $limit = ($val['shelf_type'] - 1);
                        $count = $columnInfoModel->count();
                        if($count < $limit){
                            break;
                        }

                        $columnInfoList = $columnInfoModel->select($fields)->limit($limit)->get()->toArray();

                        ($val['show_name']) ? $list['title'] = $val['name'] : '';
                        $list['hasMore'] = $val['show_more'];
                        $list['url'] = $this->url. $val['id'];
                        $list['data'] = $columnInfoList;
                        break;

                    case 6 : // list展示
                    case 7 : // grid展示
                        $fields = ['id', 'xid as skip_target', 'title', 'summary', 'price', 'img_url_compressed'];
                        $columnInfoList = $columnInfoModel->select($fields)->limit($val['show_num'])->get()->toArray();

                        foreach($columnInfoList as &$column){
                            $column['price'] = ($column['price'] > 0) ? $column['price'] : 0;
                            $column['img_url_compressed'] = $this->get_url($column['img_url_compressed']);
                            $column['purchase_count'] = 0;
                            $column['is_available'] = 0;
                            $column['product_type'] = 0;
                        }

                        ($val['show_name']) ? $list['title'] = $val['name'] : '';
                        $list['hasMore'] = $val['show_more'];
                        $list['url'] = $this->url. $val['id'];
                        $list['data'] = $columnInfoList;
                        break;
                }

                if(!empty($list['data'])){
                    $list['view_type'] = $val['shelf_type'];
                    foreach ($list['data'] as $kk=>&$vv){
                        $vv['skip_type'] = $this->skipType;
                        $vv['img_url'] = isset($vv['img_url']) ? $this->get_url($vv['img_url']) : '';
                    }
                    $data[] = $list;
                }

            }
        }

        return $this->responseJson(0, 'success', $data);

    }


    public function column_lists(){

        $homeColumnId = request('homeColumnId');

        $data = [];

        if(!empty($homeColumnId)){

            // 栏目关连专栏列表
            $homeColumnIdsList = HomeColumnIds::select('column_id')->where('home_column_id', $homeColumnId)->orderby('sort','asc')->orderBy('id','asc')->get()->toArray();
            if(!empty($homeColumnIdsList)){
                $homeColumnIds = []; // 专栏id列表
                foreach ($homeColumnIdsList as $k=>$v){
                    $homeColumnIds[] = $v['column_id'];
                }

                if(!empty($homeColumnIds)){
                    // 专栏列表
                    $fields = ['xid', 'title', 'img_url', 'img_url_compressed', 'summary', 'price', 'resource_count'];
                    $where = ['shelf_setting' => 0, 'is_stop_sell' => 0, 'state' => 0];
                    $data = ColumnInfo::where($where)->whereIn('id', $homeColumnIds)
                        ->orderBy(DB::raw('FIND_IN_SET(id, "' . implode(",", $homeColumnIds) . '"' . ")"))
                        ->select($fields)->get()->toArray();

                    // data
                    foreach ($data as $key=>&$val){
                        $val['id'] = $val['xid'];
                        $val['img_url'] = isset($val['img_url']) ? $this->get_url($val['img_url']) : '';
                        $val['img_url_compressed'] = isset($val['img_url_compressed']) ? $this->get_url($val['img_url_compressed']) : '';
                        $val['price'] = ($val['price'] > 0 ) ? $val['price'] : 0;
                    }

                }
            }

        }

        return $this->responseJson(0, 'success', $data);
    }

    //
    public function get_url($url){

        if(empty($url)){
            return '';
        }

        if(strpos($url, 'http') !== false){
            return $url;
        }else{
            return $this->appUrl.$url;
        }

    }

}

