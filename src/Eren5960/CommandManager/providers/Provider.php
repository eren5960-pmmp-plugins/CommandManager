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
use Eren5960\CommandManager\defaultsubcommands\CommandDisablePerWorld;
use Eren5960\CommandManager\defaultsubcommands\EnableCommand;
use Eren5960\CommandManager\defaultsubcommands\DisableCommand;
use Eren5960\CommandManager\defaultsubcommands\HelpCommand;
use pocketmine\command\Command;
use pocketmine\utils\Config;

class Provider extends Config{
    /** @var CommandManager */
    private $plugin;
    /** @var string[] */
    private $toDisablesEveryone = [];
    private $toDisablesPerWorld = [];
    /** @var Command[] */
    private $cloudCommands = [];
    /** @var int  */
    const TO_EVERYONE = 1;
    const TO_PER_REMOVE = 2;

    /**
     * Provider constructor.
     * @param CommandManager $plugin
     */
    public function __construct(CommandManager $plugin){
        $this->plugin = $plugin;
        parent::__construct($plugin->getDataFolder() . "config.yml", self::YAML);
    }

    public function start(): void{
        if(!$this->__isset("toEveryoneDisables")){
            $this->set("toEveryoneDisables", ["version", "help"]);
        }

        if(!$this->__isset("disablePerWorld")){
            $this->set("disablePerWorld", ["lobby" => ["fly", "jump"]]);
        }

        $this->save();
        $this->toDisablesEveryone = $this->gets(self::TO_EVERYONE);
        $this->toDisablesPerWorld = $this->gets(self::TO_PER_REMOVE);
        foreach ($this->plugin->getServer()->getCommandMap()->getCommands() as $name => $command) {
            $this->cloudCommands[$name] = $command;
        }
    }


    /**
     * @param int $type
     * @return array|null
     */
    public function gets(int $type): ?array{
        return $type == self::TO_EVERYONE ? $this->get("toEveryoneDisables") : ($type == self::TO_PER_REMOVE ? $this->get("disablePerWorld") : null);
    }

    /**
     * @return string[]
     */
    public function getToDisablesEveryone(): array{
        return $this->toDisablesEveryone;
    }

    /**
     * @return array
     */
    public function getToDisablesPerWorld(): array{
        return $this->toDisablesPerWorld;
    }

    /**
     * @return Command[]
     */
    public function getCommands(): array{
        return $this->cloudCommands;
    }

    /**
     * @param string $world
     * @param string $command
     * @return bool
     */
    public function addDisable(string $world, string $command): bool{
        if(!array_key_exists($world, $this->toDisablesPerWorld)){
            $this->toDisablesPerWorld[$world] = [];
        }

        if(!array_key_exists($command, $this->toDisablesPerWorld[$world])){
            $this->toDisablesPerWorld[$world][$command] = $command;
            return true;
        }

        return false;
    }

    /**
     * @param string $world
     * @param string $command
     * @return bool
     */
    public function delDisable(string $world, string $command): bool{
        if(isset($this->toDisablesPerWorld[$world][$command])){
            unset($this->toDisablesPerWorld[$world][$command]);
            return true;
        }

        return false;
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