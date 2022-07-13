<?php

namespace app\admin\command;

use think\console\Input;
use think\console\Output;
use think\console\Command;
use app\admin\command\Crud\library\Stub;
use ba\Random;
use think\exception\ErrorException;
use think\Exception;


class Root extends Command
{

    protected function configure()
    {
        $this->setName('build:root')->setDescription('生成默认的后台入口文件和前端的入口文件');
    }

    protected function execute(Input $input, Output $output)
    {
        $adminPath         = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        $webPath           = root_path() . 'web' . DIRECTORY_SEPARATOR;
        $publicPath        = root_path() . 'public' . DIRECTORY_SEPARATOR;
        try {
            // 设置新的Token随机密钥key
            $newFileName   = Random::build('alnum', 7);

            // 生成后台入口文件
            Stub::writeToFile("{$publicPath}/{$newFileName}.php", "11111111");
            Stub::writeToFile("{$publicPath}/{$newFileName}.html", "11111111");

            //生成前台入口文件
            Stub::writeToFile("{$webPath}/{$newFileName}.html", "11111111");
            
        } catch (ErrorException $e) {
            throw new Exception('Code: ' . $e->getCode() . "\nLine: " . $e->getLine() . "\nMessage: " . $e->getMessage() . "\nFile: " . $e->getFile());
        }
        $output->info('Build Successed');
    }
}
