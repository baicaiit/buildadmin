<?php

namespace app\admin\command;

use app\admin\command\Root\library\Stub;
use think\console\Input;
use think\console\Output;
use think\console\Command;
use ba\Random;
use think\exception\ErrorException;
use think\Exception;
use think\facade\Config;


class Root extends Command
{
    static $buildConfigFileName = 'buildadmin.php';
    static $buildEnvFileName = '.env';

    protected $stub = null;


    protected function configure()
    {
        $this->setName('build:root')->setDescription('生成默认的后台入口文件和前端的入口文件');
    }

    protected function execute(Input $input, Output $output)
    {
        $this->stub = Stub::instance();
        $adminPath = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $webPath = root_path() . 'web' . DIRECTORY_SEPARATOR;
        $publicPath = root_path() . 'public' . DIRECTORY_SEPARATOR;
        try {
            // 旧的后台入口名字
            $oldFileName = Config::get('buildadmin.base_file_name');
            $publicHtmlContent = @file_get_contents("{$publicPath}{$oldFileName}.html");
            if (!empty($oldFileName)) {
                // 删除旧文件
                @unlink("{$publicPath}{$oldFileName}.php");
                @unlink("{$publicPath}{$oldFileName}.html");
                @unlink("{$webPath}{$oldFileName}.html");
            }
            // 在后端设置后台入口名字
            $newFileName = Random::build('alnum', 7);
            $buildConfigFile = config_path() . self::$buildConfigFileName;
            $buildConfigContent = @file_get_contents($buildConfigFile);
            $buildConfigContent = preg_replace("/'base_file_name'(\s+)=>(\s+)'.*'/", "'base_file_name'\$1=>\$2'{$newFileName}'", $buildConfigContent);
            $result = @file_put_contents($buildConfigFile, $buildConfigContent);
            if (!$result) {
                $output->error(__('File has no write permission:%s', ['config/' . self::$buildConfigFileName]));
            }
            // 在前端设置后台入口名字
            $buildEnvFile = $webPath . (self::$buildEnvFileName);
            $buildEnvContent = @file_get_contents($buildEnvFile);
            $buildEnvContent = preg_replace("/VITE_BASE_FILE_NAME(\s+)=(\s+)'.*'/", "VITE_BASE_FILE_NAME\$1=\$2'{$newFileName}'", $buildEnvContent);
            $result = @file_put_contents($buildEnvFile, $buildEnvContent);
            if (!$result) {
                $output->info(__('File has no write permission:%s', ['web/' . self::$buildEnvFileName]));
            }
            // 生成后台入口文件
            Stub::writeToFile("{$publicPath}{$newFileName}.php", $this->stub->getReplacedStub('php', []));
            Stub::writeToFile("{$publicPath}{$newFileName}.html", $publicHtmlContent);

            //生成前台入口文件
            Stub::writeToFile("{$webPath}{$newFileName}.html", $this->stub->getReplacedStub('html', []));

        } catch (ErrorException $e) {
            throw new Exception('Code: ' . $e->getCode() . "\nLine: " . $e->getLine() . "\nMessage: " . $e->getMessage() . "\nFile: " . $e->getFile());
        }
        $output->info('Build Successed');
    }
}
