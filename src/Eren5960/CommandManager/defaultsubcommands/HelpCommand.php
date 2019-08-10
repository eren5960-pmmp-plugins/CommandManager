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

class HelpCommand extends BaseCommand{
    /** @var string  */
    const ONE_STAR = "*";

    /**
     * @param CommandSender $sender
     * @param CommandManager $manager
     * @param array $args
     */
    public function run(CommandSender $sender, CommandManager $manager, array $args){
        $title = TextFormat::BOLD . str_repeat("-", 10) . $manager::PREFIX . TextFormat::BOLD . str_repeat("-", 10);

        $sender->sendMessage($title);

        /**
         * @var string $name
         * @var BaseCommand $command
         */
        foreach (self::$subcommands as $name => $command){
            $sender->sendMessage(TextFormat::GOLD . self::ONE_STAR . " " . TextFormat::AQUA . "/command " . TextFormat::RESET . $command->getSubName() . " : " . TextFormat::LIGHT_PURPLE . $command->getSubDescription());
        }

        $sender->sendMessage($title);
    }

    /**
     * @return string
     */
    public function getSubName(): string{
        return "help";
    }

    /**
     * @return string
     */
    public function getSubDescription(): string{
        return "See CommandManager sub commands and descriptions";
    }
}