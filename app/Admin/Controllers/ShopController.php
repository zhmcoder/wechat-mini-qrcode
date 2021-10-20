<?php


namespace App\Admin\Controllers;


use Andruby\DeepAdmin\Controllers\ContentController;
use App\Models\Brand;
use App\Models\Goods;
use App\Models\GoodsAttr;
use App\Models\GoodsAttrMap;
use App\Models\GoodsAttrValue;
use App\Models\GoodsAttrValueMap;
use App\Models\GoodsClass;
use Illuminate\Http\Request;
use SmallRuralDog\Admin\Components\Attrs\SelectOption;
use SmallRuralDog\Admin\Components\Form\Cascader;
use SmallRuralDog\Admin\Components\Form\CSwitch;
use SmallRuralDog\Admin\Components\Form\DatePicker;
use SmallRuralDog\Admin\Components\Form\Input;
use SmallRuralDog\Admin\Components\Form\Radio;
use SmallRuralDog\Admin\Components\Form\RadioGroup;
use SmallRuralDog\Admin\Components\Form\Select;
use SmallRuralDog\Admin\Components\Form\Upload;
use SmallRuralDog\Admin\Components\Form\WangEditor;
use SmallRuralDog\Admin\Components\Grid\Image;
use SmallRuralDog\Admin\Components\Grid\Tag;
use SmallRuralDog\Admin\Components\Widgets\Divider;
use SmallRuralDog\Admin\Controllers\AdminController;
use SmallRuralDog\Admin\Form;
use SmallRuralDog\Admin\Grid;
use Andruby\DeepAdmin\Components\GoodsSku;

class ShopController extends ContentController
{

}
