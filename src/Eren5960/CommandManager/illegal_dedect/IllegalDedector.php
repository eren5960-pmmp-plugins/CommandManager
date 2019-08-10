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

namespace Eren5960\CommandManager\illegal_dedect;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginDescription;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Internet;
use pocketmine\utils\InternetExpection;

class IllegalDedector{
    /** @var Plugin */
    private $plugin;

    /**
     * IllegalDedector constructor.
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @return PluginDescription
     */
    public function getDescription(): PluginDescription{
        return $this->plugin->getDescription();
    }

    /**
     * @return bool
     */
    public function isFakeAuthor(): bool{
        return $this->getDescription()->getAuthors() !== [base64_decode("RXJlbjU5NjA=")];
    }

    /**
     * @return bool
     */
    public function isOldVersion(): bool{
        $url = "https://raw.githubusercontent.com/Eren5960/" . $this->getDescription()->getName() . "/master/plugin.yml";
        $current_version = $this->getDescription()->getVersion();

        $err = '';
        $content = Internet::getURL($url, 10, [], $err);
        if(empty($err)){
            $version = yaml_parse($content)["version"];
            return floatval($version) > floatval($current_version);
        }else{
            $this->plugin->getLogger()->alert(substr($err, 0, 9) === "Could not" ? "You haven't internet connection for check CommandManager version." : $error);
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isFakeWebSite(): bool{
        return $this->getDescription()->getWebsite() !== "github.com/eren5960";
    }

    /**
     * @param string $str
     */
    public function send(string $str): void{
        $this->plugin->getLogger()->critical($str);
    }

    /**
     * @return bool
     */
    public function check(): bool{
        $state = true;
        if($this->isFakeAuthor()){
            $state = false;
            $this->send("This plugin author is fake, real author " . base64_decode("RXJlbjU5NjA=") . "! Shutting down...");
        }

        if($this->isOldVersion()){
            $this->plugin->getLogger()->info(TextFormat::RED . "CommandManager is old!, please update » github.com/eren5960/CommandManager");
        }

        if($this->isFakeWebSite()){
            $state = false;
            $this->send("This plugin is edited as a leak! Shutting down...");
        }

        return $state;
    }

}