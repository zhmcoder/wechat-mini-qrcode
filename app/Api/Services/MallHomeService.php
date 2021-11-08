<?php


namespace App\Api\Services;


use Andruby\DeepAdmin\Models\Content;
use Andruby\HomeConfig\Models\HomeConfig;
use Andruby\HomeConfig\Models\HomeConfigId;
use Andruby\HomeConfig\Models\HomeItem;
use Andruby\HomeConfig\Models\HomeJump;
use Andruby\HomeConfig\Services\HomeConfigService;

class  MallHomeService extends HomeConfigService
{

    protected function goods($table_name, $id)
    {
        return ['table_name1' => $table_name, 'id' => $id];
    }

}
