<?php

namespace App\Api\Controllers;

use App\Api\Validates\HomeValidate;
use App\Models\AppInfo;
use App\Models\Goods;
use App\Models\HomeColumn;
use App\Models\HomeColumnIds;
use App\Models\HomeConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{
    public function index(Request $request, HomeValidate $validate)
    {
        $appId = request('appid', 'deep.mall');
        $osType = request('os_type', '3');
        $dateTime = date('Y-m-d H:i:s');

        $validate_result = $validate->index($request->only([]));
        if ($validate_result) {
            $data = [];
            $appInfo = AppInfo::query()->where('app_id', $appId)->first();
            if (!empty($appInfo)) {
                $fields = ['id', 'name', 'shelf_type', 'show_num', 'show_name'];
                $homeList = HomeColumn::query()->select($fields)
                    ->where('shelf_on', 1)
                    ->where('show_app', 'like', '%"' . $appId . '"%')
                    ->where(function ($query) use ($dateTime) {
                        $query->where('publish_up', '<=', $dateTime)->orWhere(function ($query) {
                            $query->where('publish_up', null);
                        });
                    })->where(function ($query) use ($dateTime) {
                        $query->where('publish_down', '>=', $dateTime)->orWhere(function ($query) {
                            $query->where('publish_down', null);
                        });
                    })
                    ->orderBy('sort', 'desc')->orderBy('id', 'desc')
                    ->get()->toArray();

                foreach ($homeList as $key => &$val) {
                    $list = [];

                    $homeColumnIdsList = HomeColumnIds::query()->select('column_id')
                        ->where('home_column_id', $val['id'])
                        ->orderBy('sort', 'asc')->orderBy('id', 'asc')
                        ->get()->toArray();
                    if (empty($homeColumnIdsList)) {
                        continue;
                    }
                    $homeColumnIds = [];
                    foreach ($homeColumnIdsList as $ids) {
                        $homeColumnIds[] = $ids['column_id'];
                    }

                    // 商品列表
                    if ($val['shelf_type'] == 3) {
                        $where = ['on_shelf' => 1];
                        $fields = ['id', 'name', 'on_shelf', 'price'];
                        $list = Goods::query()->select($fields)
                            ->where($where)->whereIn('id', $homeColumnIds)
                            // todo ->where('show_app', 'like', '%"' . $appId . '"%')
                            ->orderBy(DB::raw('FIND_IN_SET(id, "' . implode(",", $homeColumnIds) . '"' . ")"))
                            ->limit($val['show_num'])
                            ->get()->toArray();

                        foreach ($list as &$goods) {
                            $goods['images'] = http_path($goods['images'][0]['path']);
                        }

                    } else if ($val['shelf_type'] == 1 || $val['shelf_type'] == 2) {
                        $where = ['is_show' => 1, 'config_type' => $val['shelf_type']];
                        $fields = ['id', 'title as name', 'thumb as images'];

                        $list = HomeConfig::query()->select($fields)
                            ->where($where)->whereIn('id', $homeColumnIds)
                            ->orderBy(DB::raw('FIND_IN_SET(id, "' . implode(",", $homeColumnIds) . '"' . ")"))
                            ->limit($val['show_num'])
                            ->get()->toArray();

                        foreach ($list as &$config) {
                            $config['images'] = http_path($config['images']);
                        }
                    }

                    //
                    $val['name'] = $val['show_name'] ? $val['name'] : '';
                    $val['data'] = $list;

                    $data[] = $val;
                }
            }

            $this->responseJson(0, 'success', $data);
        } else {
            $this->responseJson('-1', $validate->message);
        }
    }
}

