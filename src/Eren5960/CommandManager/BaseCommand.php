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

namespace Eren5960\CommandManager;

use Eren5960\CommandManager\expections\SubcommandNotFoundExpection;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class BaseCommand extends Command{
    /** @var array  */
    protected static $subcommands = [];

    public function __construct(){
        parent::__construct("commandmanager", "Command Manager", "/cm help", ["cm", "cmd"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args){
        $manager = CommandManager::getInstance();
        if(empty($args)){
            $sender->sendMessage($manager::PREFIX . "use: /cm help");
            return;
        }

        $subcommand = array_shift($args);

        if(!$this->hasPermission($sender, $subcommand)){
            $sender->sendMessage($manager::PREFIX . TextFormat::DARK_PURPLE . "you have not permission!");
            return;
        }

        if(array_key_exists($subcommand, self::$subcommands)){
            self::$subcommands[$subcommand]->run($sender, $manager, $args);
        }else{
            $sender->sendMessage($manager::PREFIX . "use: /cm help");
        }
    }

    /**
     * @param CommandSender $sender
     * @param CommandManager $manager
     * @param array $args
     */
    protected function run(CommandSender $sender, CommandManager $manager, array $args){}

    /**
     * @return null|string
     */
    public function getSubName(): ?string{
        return null;
    }

    /**
     * @return string
     */
    public function getSubDescription(): string{
        return "No description given.";
    }

    /**
     * @param BaseCommand $command
     * @throws SubcommandNotFoundExpection
     */
    public static function registerSubcommand(BaseCommand $command): void{
        if ($command->getSubName() === null) {
            throw new SubcommandNotFoundExpection(get_class($command));
        }

        self::$subcommands[$command->getSubName()] = $command;
    }

    /**
     * @param CommandSender $sender
     * @param string $subcommand
     * @return bool
     */
    protected function hasPermission(CommandSender $sender, string $subcommand){
        return $sender->hasPermission("use.commandmanager.allcommands") ? true : $sender->hasPermission("use.commandmanager." . $subcommand);
    }
}