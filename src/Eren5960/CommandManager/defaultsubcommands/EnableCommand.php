<?php
/**
 *  _____                    ____   ___    __     ___  
 * | ____| _ __  ___  _ __  | ___| / _ \  / /_   / _ \ 
 * |  _|  | '__|/ _ \| '_ \ |___ \| (_) || '_ \ | | | |
 * | |___ | |  |  __/| | | | ___) |\__, || (_) || |_| |
 * |_____||_|   \___||_| |_||____/   /_/  \___/  \___/ 
 * 
 * @author Eren5960
 * @link https://github.com/Eren5960
 */
declare(strict_types=1);

namespace Eren5960\CommandManager\defaultsubcommands;

use Eren5960\CommandManager\BaseCommand;
use Eren5960\CommandManager\CommandManager;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class EnableCommand extends BaseCommand{
    /**
     * @param CommandSender $sender
     * @param CommandManager $manager
     * @param array $args
     */
    protected function run(CommandSender $sender, CommandManager $manager, array $args){
        $command = array_shift($args);
        if(is_null($command)) {
            $sender->sendMessage($manager::PREFIX . TextFormat::RED . "usage: /command enable command-name");
            return;
        }

        $world = array_shift($args);
        if(is_null($world)){
            $state = $manager->enableCommandByName($command);

            switch ($state){
                case $manager::COMMAND_ALREADY_ENABLED:
                    $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::RED . " command already enable!");
                    break;
                case $manager::COMMAND_NOT_FOUND:
                    $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::RED . " command not found in plugins and " . \pocketmine\NAME . "!");
                    break;
                case $manager::COMMAND_ENABLED:
                    $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::GREEN . " command enabled!");
                    break;
            }
        }elseif($manager->enableCommandPerWorld($world, $command)){
            $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::GREEN . " command enabled!");
        }else{
            $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::RED . " command already enabled!");
        }
    }

    /**
     * @return string
     */
    public function getSubName(): string{
        return "enable";
    }

    /**
     * @return string
     */
    public function getSubDescription(): string{
        return "Enable a command!";
    }
}