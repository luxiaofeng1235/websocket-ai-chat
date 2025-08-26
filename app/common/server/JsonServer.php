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


use think\exception\HttpResponseException;
use think\Response;
use think\response\Json;

/**
 * 统一Json服务类
 * Class JsonServer
 * @Author FZR
 * @package app\common\server
 */
class JsonServer
{
    private static $SUCCESS = 1; //成功状态码
    private static $Error   = 0; //失败状态码

    /**
     * 统一返回JSON格式
    //  * @param int $code (状态码)
    //  * @param int $show (显示)
    //  * @param string $msg (提示)
    //  * @param array $data (返回数据集)
    //  * @param int $httpStatus (异常方式抛出)
     * @Author FZR
    //  * @return Json
     */
    // private static function result(int $code, int $show, string $msg='OK', array $data=[], int $httpStatus=200) :Json
    // {
    //     $result = array(
    //         'code' => $code,
    //         'show' => $show,
    //         'msg'  => $msg,
    //         'data' => $data
    //     );
    //     return json($result, $httpStatus);
    // }
    /**
     * 统一返回JSON格式
     * @param int $code (状态码)
     * @param int $show (显示)
     * @param string $msg (提示)
     * @param $data (返回数据集)
     * @param int $httpStatus (异常方式抛出)
     * @Author FZR
     * @return Json
     */
    private static function result(int $code, int $show, string $msg='OK', $data=[], int $httpStatus=200) :Json
    {
        $result = array(
            'code' => $code,
            'show' => $show,
            'msg'  => $msg,
            'data' => $data
        );
        return json($result, $httpStatus);
    }

    /**
     * 成功返回
    //  * @param string $msg (提示)
    //  * @param array $data (数据集)
    //  * @Author FZR
    //  * @return Json
     */
    // public static function success(string $msg='OK', array $data=[], int $code = 1, int $show = 0) : Json
    // {
    //     return self::result($code, $show, $msg, $data);
    // }
    /**
     * 成功返回
    //  * @param string $msg (提示)
    //  * @param array $data (数据集)
     * @Author FZR
     * @return Json
     */
    public static function success(string $msg='OK', $data=[], int $code = 1, int $show = 0) : Json
    {
        return self::result($code, $show, $msg, $data);
    }

    /**
     * 错误返回
     * @param string $msg (提示)
     * @param array $data (数据集)
     * @Author FZR
     * @return Json
     */
    public static function error(string $msg='Error', array $data=[],int $code = 0, int $show = 1) : Json
    {
        return self::result($code, $show, $msg, $data);
    }

    /**
     * Notes: 抛出JSON
     * @param string $msg
     * @param array $data
     * @param int $code
     * @Author FZR
     */
    public static function throw(string $msg='Error', array $data=[], int $code=0, int $show = 1)
    {
        $data = array('code'=>$code, 'show'=>$show, 'msg'=>$msg, 'data'=>$data);
        $response = Response::create($data, 'json', 200);
        throw new HttpResponseException($response);
    }
}
