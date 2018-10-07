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

namespace Eren5960\CommandManager\expections;

use Throwable;

class SubcommandNotFoundExpection extends \RuntimeException{

    public function __construct(string $class, int $code = 0, Throwable $previous = null){
        parent::__construct("sub command not found in " . $class, $code, $previous);
    }
}