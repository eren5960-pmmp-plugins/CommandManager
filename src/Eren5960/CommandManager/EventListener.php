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

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\utils\TextFormat;

class EventListener implements Listener{

    /**
     * @param CommandManager $manager
     */
    public function init(CommandManager $manager): void{
        $manager->getServer()->getPluginManager()->registerEvents($this, $manager);
    }

    /**
    * @param PlayerCommandPreprocessEvent $event
    * @Priority MONITOR
    */
    public function onActivate(PlayerCommandPreprocessEvent $event): void{
        $command_ = substr($event->getMessage(), 1);
        $world_ = $event->getPlayer()->getLevel()->getFolderName();
        $manager = CommandManager::getInstance();

        foreach ($manager->getConfig()->getToDisablesPerWorld() as $world => $commands){
            foreach ($commands as $command){
                if($world == $world_ && $command == $command_){
                    $event->setCancelled(true);
                    $event->getPlayer()->sendMessage(TextFormat::RED . $manager->getServer()->getLanguage()->translateString("commands.generic.notFound")); // lol
                }
            }
        }
    }
}