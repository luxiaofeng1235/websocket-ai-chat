<?php
// +----------------------------------------------------------------------
// | likeshop开源商城系统
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | gitee下载：https://gitee.com/likeshop_gitee
// | github下载：https://github.com/likeshop-github
// | 访问官网：https://www.likeshop.cn
// | 访问社区：https://home.likeshop.cn
// | 访问手册：http://doc.likeshop.cn
// | 微信公众号：likeshop技术社区
// | likeshop系列产品在gitee、github等公开渠道开源版本可免费商用，未经许可不能去除前后端官方版权标识
// |  likeshop系列产品收费版本务必购买商业授权，购买去版权授权后，方可去除前后端官方版权标识
// | 禁止对系统程序代码以任何目的，任何形式的再发布
// | likeshop团队版权所有并拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeshop.cn.team
// +----------------------------------------------------------------------


namespace app\common\server;


use app\common\model\Config as configModel;
use think\facade\Cache;
use think\facade\Config;

/**
 * 配置 服务类
 * Class ConfigServer
 * @Author FZR
 * @package app\common\server
 */
class ConfigServer
{

    /**
     * @notes
     * @param string $type
     * @param string $name
     * @param mixed $value
     * @param int $shop_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 令狐冲
     * @date 2022/10/20 18:21
     */
    public static function set($type, $name = null, $value = '', $shop_id = 0)
    {
        $cacheKey = 'config' . '-' . $type . '-' . $name . '-' . $shop_id;
        Cache::delete($cacheKey);
        $original = $value;
        $update_time = time();
        if (is_array($value)) {
            $value = json_encode($value, true);
        }

        $data = configModel::where(['type' => $type, 'name' => $name, 'shop_id' => $shop_id])->find();

        if (empty($data)) {
            configModel::create([
                'type' => $type,
                'name' => $name,
                'value' => $value,
                'shop_id' => $shop_id
            ]);
        } else {
            configModel::update([
                'value' => $value,
                'update_time' => $update_time
            ], ['type' => $type, 'name' => $name, 'shop_id' => $shop_id]);
        }
        return $original;
    }


    /**
     * @notes
     * @param string $type
     * @param string $name
     * @param null $defaultValue
     * @param int $shop_id
     * @return array|mixed|null
     * @author 令狐冲
     * @date 2022/10/20 18:35
     */
    public static function get($type, $name = null, $defaultValue = null, $shop_id = 0)
    {
        //有缓存取缓存
        $cacheKey = 'config' . '-' . $type . '-' . $name . '-' . $shop_id;
        $result = Cache::get($cacheKey);
        $value = $result['config_server'] ?? null;
        if ($value !== null) {
            return $value;
        }

        //单项配置
        if ($name) {
            $result = configModel::where([
                'type' => $type,
                'name' => $name,
                'shop_id' => $shop_id
            ])->value('value');

            //数组配置需要自动转换
            //这里转义特殊处理下，不然PHP8会报错
            $json = json_decode($result  ?? '', true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $result = $json;
            }
            //获取调用默认配置
            if ($result === NULL) {
                $result = $defaultValue;
            }
            //获取系统配置文件的配置
            if ($result === NULL) {
                $result = Config::get('default.' . $type . '.' . $name);
            }

            Cache::set($cacheKey, ['config_server' => $result]);
            return $result;
        }

        //多项配置
        $data = configModel::where([
            'type' => $type,
            'shop_id' => $shop_id
        ])->column('value', 'name');

        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $json = json_decode($v, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data[$k] = $json;
                }
            }
        }
        if ($data === []) {
            $data = $defaultValue;
        }
        if ($data === NULL) {
            $data = Config::get('default.' . $type . '.' . $name);
        }


        Cache::set($cacheKey, ['config_server' => $data]);
        return $data;

    }

}