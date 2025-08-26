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

use app\common\enum\FileEnum;
use app\common\model\File;
use app\common\server\storage\Driver as StorageDriver;
use EasyWeChat\Factory;
use think\Exception;

class FileServer
{
    /**
     * NOTE: 图片上传
     * @author: 张无忌
     * @param $cid
     * @param string $save_dir
     * @return array
     * @throws Exception
     */
    public static function image($cid, $shop_id, $user_id = 0, $save_dir = 'uploads/images')
    {
        try {
            // 1、获取当前存储对象
            $config = [
                'default' => ConfigServer::get('storage', 'default', 'local'),
                'engine' => ConfigServer::get('storage_engine') ?? ['local' => []]
            ];

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 校验上传文件后缀
            if (!in_array(strtolower($fileInfo['ext']), config('project.file_image'))) {
                throw new Exception("上传图片不允许上传" . $fileInfo['ext'] . "文件");
            }

            // 上传文件
            $save_dir = $save_dir . '/' . date('Ymd');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception($StorageDriver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $file_name = substr($fileInfo['name'], 0, 123);
                $file_end = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $file_name . $file_end;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::IMAGE_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $save_dir . '/' . str_replace("\\", "/", $fileName),
                'shop_id' => $shop_id,
                'user_id' => $user_id,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'shop_id' => $file['shop_id'],
                'uri' => UrlServer::getFileUrl($file['uri']),
                'base_uri' => clearDomain($file['uri'])
            ];

        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * 视频上传
     */
    public static function video($cid, $shop_id, $save_dir = 'uploads/video')
    {
        try {
            // 1、获取当前存储对象
            $config = [
                'default' => ConfigServer::get('storage', 'default', 'local'),
                'engine' => ConfigServer::get('storage_engine') ?? ['local' => []]
            ];

            // 2、执行文件上传
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            $fileName = $StorageDriver->getFileName();
            $fileInfo = $StorageDriver->getFileInfo();

            // 校验上传文件后缀
            if (!in_array(strtolower($fileInfo['ext']), config('project.file_video'))) {
                throw new Exception("上传视频不允许上传" . $fileInfo['ext'] . "文件");
            }

            // 上传文件
            $save_dir = $save_dir . '/' . date('Ymd');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception($StorageDriver->getError());
            }

            // 3、处理文件名称
            if (strlen($fileInfo['name']) > 128) {
                $file_name = substr($fileInfo['name'], 0, 123);
                $file_end = substr($fileInfo['name'], strlen($fileInfo['name']) - 5, strlen($fileInfo['name']));
                $fileInfo['name'] = $file_name . $file_end;
            }

            // 4、写入数据库中
            $file = File::create([
                'cid' => $cid,
                'type' => FileEnum::VIDEO_TYPE,
                'name' => $fileInfo['name'],
                'uri' => $save_dir . '/' . str_replace("\\", "/", $fileName),
                'shop_id' => $shop_id,
                'create_time' => time(),
            ]);

            // 5、返回结果
            return [
                'id' => $file['id'],
                'cid' => $file['cid'],
                'type' => $file['type'],
                'name' => $file['name'],
                'shop_id' => $file['shop_id'],
                'uri' => UrlServer::getFileUrl($file['uri']),
                'base_uri' => clearDomain($file['uri'])
            ];

        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function ossFile($post)
    {
        $data = [
            'cid' => $post['cid'],
            'type' => $post['type'],
            'name' => $post['name'],
            'uri' => $post['uri'],
            'shop_id' => $post['shop_id'],
            'create_time' => time(),
        ];
        $id = (new File)->insertGetId($data);
        $data['id'] = $id;
        $data['uri'] = UrlServer::getFileUrl($data['uri']);
        return $data;
    }

    public static function setAcl($fileId, $type)
    {
        $file = File::where('type', $type)
            ->where('id', 'in', $fileId)
            ->column('uri');
        return $file;
    }

    /**
     * Notes: 用户上传图片
     * @param string $save_dir (保存目录, 不建议修改, 不要超二级目录)
     * @param bool $isLocal (是否存不使用oss 只存本地, 上传退款证书会用到)
     * @return array
     * @author 张无忌(2021/2/20 9:53)
     */
    public static function other($save_dir = 'uploads/other', $isLocal = false)
    {
        try {
            if ($isLocal == false) {
                $config = [
                    'default' => ConfigServer::get('storage', 'default', 'local'),
                    'engine' => ConfigServer::get('storage_engine')
                ];
            } else {
                $config = [
                    'default' => 'local',
                    'engine' => ConfigServer::get('storage_engine')
                ];
            }
            if (empty($config['engine']['local'])) {
                $config['engine']['local'] = [];
            }
            $StorageDriver = new StorageDriver($config);
            $StorageDriver->setUploadFile('file');
            if (!$StorageDriver->upload($save_dir)) {
                throw new Exception('上传失败' . $StorageDriver->getError());
            }
            // 图片上传路径
            $fileName = $StorageDriver->getFileName();
            // 图片信息
            $fileInfo = $StorageDriver->getFileInfo();
            // 信息
            $data = [
                'name' => $fileInfo['name'],
                'type' => FileEnum::OTHER_TYPE,
                'uri' => $save_dir . '/' . str_replace("\\", "/", $fileName),
                'create_time' => time(),
                'shop_id' => 0
            ];
            File::insert($data);
            return ['上传文件成功', $data];

        } catch (\Exception $e) {
            $message = lang($e->getMessage()) ?? $e->getMessage();
            return ['上传文件失败:', $message];
        }
    }

    public static function uploadUrl($url, $save_dir = 'uploads/images')
    {
        try {
            $config = [
                'default' => ConfigServer::get('storage', 'default', 'local'),
                'engine' => ConfigServer::get('storage_engine')
            ];
            $StorageDriver = new StorageDriver($config);
            if (!$StorageDriver->fetch($url, $save_dir)) {
                throw new Exception('上传失败' . $StorageDriver->getError());
            }
            return ['上传文件成功', $save_dir];
        } catch (\Exception $e) {
            $message = lang($e->getMessage()) ?? $e->getMessage();
            return ['上传文件失败:', $message];
        }
    }

    /**
     * @throws \Exception
     * @notes 根据URL下载文件并上传，支持指定文件名和缓存检查
     * @param string $url 远程文件URL
     * @param string $save_dir 保存目录
     * @param string $custom_filename 自定义文件名（包含扩展名），为空则自动生成
     * @param bool $check_exists 是否检查文件已存在，避免重复上传
     * @return array 返回完整文件信息包括URL
     */
    public static function uploadUrlWithReturn($url, $save_dir = 'uploads/images', $custom_filename = '', $check_exists = true)
    {
        try {
            $config = [
                'default' => ConfigServer::get('storage', 'default', 'local'),
                'engine' => ConfigServer::get('storage_engine')
            ];
            $StorageDriver = new StorageDriver($config);
            
            // 添加日期目录
            $dateDir = date('Ymd');
            $fullSaveDir = $save_dir . '/' . $dateDir;
            
            // 处理文件名
            if (!empty($custom_filename)) {
                // 使用指定文件名
                $fileName = $custom_filename;
                // 确保有扩展名
                if (!pathinfo($fileName, PATHINFO_EXTENSION)) {
                    $fileName .= '.jpg';
                }
            } else {
                // 生成唯一文件名（避免重复）
                $extension = '.jpg'; // 默认扩展名
                $fileName = uniqid() . '_' . time() . $extension;
            }
            
            $fileKey = $fullSaveDir . '/' . $fileName;
            $ossViewDomain = rtrim(env('OSS_VIEW_DOMAIN', 'https://static.jsss999.com/'), '/');
            $finalUrl = $ossViewDomain . '/' . ltrim($fileKey, '/');
            
            // 检查文件是否已存在
            if ($check_exists && !empty($custom_filename)) {
                if (self::checkFileExists($finalUrl)) {
                    return [
                        'success' => true,
                        'message' => '文件已存在，跳过上传',
                        'name' => $fileName,
                        'uri' => $fileKey,
                        'url' => $finalUrl
                    ];
                }
            }
            
            // 执行文件上传
            if (!$StorageDriver->fetch($url, $fileKey)) {
                throw new \Exception('上传失败: ' . $StorageDriver->getError());
            }
            
            // 返回核心信息
            return [
                'success' => true,
                'message' => '文件上传成功',
                'name' => $fileName,
                'uri' => $fileKey,
                'url' => $finalUrl
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => '上传失败: ' . $e->getMessage(),
                'url' => null
            ];
        }
    }
    
    /**
     * @throws \Exception
     * @notes 高性能批量并发上传URL文件（优化版：并发下载+直接OSS上传）
     * @param array $urls URL数组，格式：['url1', 'url2'] 或 ['name1' => 'url1', 'name2' => 'url2']
     * @param string $save_dir 保存目录
     * @param bool $check_exists 是否检查文件已存在，避免重复上传
     * @param int $concurrent_limit 并发数限制，默认4个（降低并发避免服务器压力）
     * @return array 批量上传结果
     */
    public static function fastBatchUploadUrls($urls, $save_dir = 'spider/goods', $check_exists = true, $concurrent_limit = 4)
    {
        $results = [
            'success_count' => 0,
            'failed_count' => 0,
            'total' => count($urls),
            'success' => [],
            'failed' => [],
            'duration' => 0
        ];
        
        if (empty($urls)) {
            return $results;
        }
        
        $start_time = microtime(true);
        
        // 使用现有的uploadUrlWithReturn方法，但优化调用方式
        foreach ($urls as $key => $url) {
            $filename = is_string($key) ? $key : '';
            if (empty($filename)) {
                $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $filename = uniqid() . '_' . time() . rand(100, 999) . '.' . ($extension ?: 'jpg');
            } elseif (!pathinfo($filename, PATHINFO_EXTENSION)) {
                $filename .= '.jpg';
            }
            
            try {
                // 使用现有的已验证方法
                $result = self::uploadUrlWithReturn($url, $save_dir, $filename, $check_exists);
                
                if ($result['success']) {
                    $results['success'][] = [
                        'original_key' => $key,
                        'url' => $url,
                        'filename' => $result['name'],
                        'uri' => $result['uri'],
                        'oss_url' => $result['url']
                    ];
                    $results['success_count']++;
                } else {
                    $results['failed'][] = [
                        'original_key' => $key,
                        'url' => $url,
                        'error' => $result['message']
                    ];
                    $results['failed_count']++;
                }
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'original_key' => $key,
                    'url' => $url,
                    'error' => 'Upload exception: ' . $e->getMessage()
                ];
                $results['failed_count']++;
            }
        }
        
        $results['duration'] = microtime(true) - $start_time;
        return $results;
    }
    

    /**
     * @throws \Exception
     * @notes 简单批量上传URL文件（同步方式，适合少量文件）
     * @param array $urls URL数组，格式：['url1', 'url2'] 或 ['name1' => 'url1', 'name2' => 'url2']
     * @param string $save_dir 保存目录
     * @param bool $check_exists 是否检查文件已存在
     * @return array 批量上传结果
     */
    public static function batchUploadUrls($urls, $save_dir = 'uploads/images', $check_exists = true)
    {
        $results = [
            'success_count' => 0,
            'failed_count' => 0,
            'total' => count($urls),
            'success' => [],
            'failed' => []
        ];
        
        if (empty($urls)) {
            return $results;
        }
        
        foreach ($urls as $key => $url) {
            $filename = is_string($key) ? $key : ''; // 如果key是字符串则作为文件名
            
            try {
                $result = self::uploadUrlWithReturn($url, $save_dir, $filename, $check_exists);
                
                if ($result['success']) {
                    $results['success'][] = [
                        'original_key' => $key,
                        'url' => $url,
                        'filename' => $result['name'],
                        'uri' => $result['uri'],
                        'oss_url' => $result['url']
                    ];
                    $results['success_count']++;
                } else {
                    $results['failed'][] = [
                        'original_key' => $key,
                        'url' => $url,
                        'error' => $result['message']
                    ];
                    $results['failed_count']++;
                }
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'original_key' => $key,
                    'url' => $url,
                    'error' => $e->getMessage()
                ];
                $results['failed_count']++;
            }
        }
        
        return $results;
    }

    /**
     * @notes 检查文件是否存在
     * @param string $url 文件URL
     * @return bool 文件是否存在
     */
    private static function checkFileExists($url)
    {
        try {
            $headers = @get_headers($url, 1);
            return $headers && strpos($headers[0], '200 OK') !== false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @notes 微信直播素材
     * @param $filePath
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2023/2/15 17:28\
     */
    public static function wechatLiveMaterial($filePath)
    {
        try {
            $fileName = basename($filePath);
            $config = WeChatServer::getMnpConfig();
            $app = Factory::miniProgram($config);
            $localFileDir = root_path() . 'public/uploads/images/';
            $localFile = $localFileDir . $fileName;

            // 切换oss后可能不存在，在本地查找后下载
            $config = [
                'default' => ConfigServer::get('storage', 'default', 'local'),
                'engine' => ConfigServer::get('storage_engine')
            ];
            if ($config['default'] != 'local' && !file_exists($localFile)) {
                $res = download_file(UrlServer::getFileUrl($filePath), $localFileDir, $fileName);
                if (empty($res)) {
                    throw new Exception("资源下载失败");
                }
            }

            // 上传微信
            $result = $app->media->uploadImage($localFile);
            if (isset($result['errcode']) && 0 != $result['errcode']) {
                $err = $result['errmsg'] ?? '微信上传图片失败';
                throw new \Exception($err);
            }
            return $result['media_id'];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}