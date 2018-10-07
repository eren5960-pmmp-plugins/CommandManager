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

namespace Eren5960\CommandManager\providers;

use Eren5960\CommandManager\CommandManager;
use Eren5960\CommandManager\defaultsubcommands\EnableCommand;
use Eren5960\CommandManager\defaultsubcommands\DisableCommand;
use Eren5960\CommandManager\defaultsubcommands\HelpCommand;
use pocketmine\command\Command;
use pocketmine\utils\Config;

class Provider extends Config{
    /** @var CommandManager */
    private $plugin;
    /** @var string[] */
    private $toRemoves = [];
    /** @var Command[] */
    private $cloudCommands = [];

    /**
     * Provider constructor.
     * @param CommandManager $plugin
     */
    public function __construct(CommandManager $plugin){
        $this->plugin = $plugin;
        parent::__construct($plugin->getDataFolder() . "config.yml", self::YAML);
    }

    public function start(): void{
        if(!$this->__isset("toOtomaticDisables")){
            $this->set("toOtomaticDisables", ["version", "help"]);
        }

        $this->save();
        $this->toRemoves = $this->getAll();
        $this->cloudCommands  = $this->plugin->getServer()->getCommandMap()->getCommands();
    }

    /**
     * @param bool $force
     * @return array
     */
    public function getAll(bool $force = false): array{
        return $this->get("toOtomaticDisables");
    }

    /**
     * @return string[]
     */
    public function getToRemoves(): array{
        return $this->toRemoves;
    }

    /**
     * @return Command[]
     */
    public function getCommands(): array{
        return $this->cloudCommands;
    }

    /**
     * @return array
     */
    public function getDefaultSubCommands(): array{
        return [
            new DisableCommand(),
            new EnableCommand(),
            new HelpCommand()
        ];
    }
}