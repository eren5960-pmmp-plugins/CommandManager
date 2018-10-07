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

        if(strpos(get_headers($url)[0], "404") !== false){
            $version = 0;
        }else{
            $version = yaml_parse(file_get_contents($url))["version"];
        }

        return $version > $current_version;
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
            $this->plugin->getLogger()->info("CommandManager is old!, please update » github.com/eren5960/CommandManager");
        }

        if($this->isFakeWebSite()){
            $state = false;
            $this->send("This plugin is edited as a leak! Shutting down...");
        }

        return $state;
    }

}