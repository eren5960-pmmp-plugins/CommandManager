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
use Eren5960\CommandManager\expections\CommandNotFoundExpection;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class DisableCommand extends BaseCommand{
    /**
     * @param CommandSender $sender
     * @param CommandManager $manager
     * @param array $args
     */
    public function run(CommandSender $sender, CommandManager $manager, array $args){
        $command = array_shift($args);
        if(is_null($command)){
            $sender->sendMessage($manager::PREFIX . TextFormat::RED . 'usage: /command disable command-name <world:optional>');
            return;
        }

        $world = array_shift($args);
        if(is_null($world)){
            try {
                if($manager->disableCommandByName($command)){
                    $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::GREEN . " command disabled!");
                }
            } catch (CommandNotFoundExpection $e){
                $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::RED . " command not found!");
            }
        }elseif($manager->disableCommandPerWorld($world, $command)){
            $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::GREEN . " command disabled for " . $world);
        }else{
            $sender->sendMessage($manager::PREFIX . TextFormat::GOLD . $command . TextFormat::RED . " command already disabled for " . $world);
        }
    }

    /**
     * @return string
     */
    public function getSubName(): string{
        return "disable";
    }

    /**
     * @return string
     */
    public function getSubDescription(): string{
        return "Disable a command!";
    }
}