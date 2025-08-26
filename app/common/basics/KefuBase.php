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

namespace app\common\basics;


use think\App;


/**
 * 客服模块基类
 * Class KefuBase
 * @package app\common\basics
 */
abstract class KefuBase
{
    /**
     * Request实例
     */
    protected $request;

    /**
     * 应用实例
     */
    protected $app;

    /**
     * 商家信息
     * @var
     */
    protected $shop;

    /**
     * 商家id
     * @var
     */
    protected $shop_id;

    /**
     * 客服id
     * @var
     */
    protected $kefu_id;


    /**
     * 客服名称
     * @var
     */
    protected $kefu_name;

    /**
     * 客服信息
     * @var array
     */
    public $kefu_info = [];

    /**
     * 客户端
     * @var null
     */
    public $client = null;

    /**
     * 页码
     * @var int
     */
    public $page_no = 1;

    /**
     * 每页显示条数
     * @var int
     */
    public $page_size = 15;

    /**
     * 无需登录即可访问的方法
     * @var array
     */
    public $like_not_need_login = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = app();
        $this->request = request();


        // 控制器初始化
        $this->initialize();
    }

    /**
     * 初始化
     */
    protected function initialize()
    {
        //客服信息
        if (isset($this->request->kefu_info) && $this->request->kefu_info) {
            $this->kefu_info = $this->request->kefu_info ?? [];
        }

        if(boolval($this->kefu_info)) {
            $this->shop_id = $this->kefu_info['shop_id'] ?? 0;
            $this->kefu_id = $this->kefu_info['id'] ?? null;
            $this->kefu_name = $this->kefu_info['nickname'] ?? null;
            $this->client = $this->kefu_info['client'] ?? null;
        }

        //分页参数
        $page_no = (int)$this->request->get('page_no');
        $this->page_no = $page_no && is_numeric($page_no) ? $page_no : $this->page_no;
        $page_size = (int)$this->request->get('page_size');
        $this->page_size = $page_size && is_numeric($page_size) ? $page_size : $this->page_size;
        $this->page_size = min($this->page_size, 100);
    }
}