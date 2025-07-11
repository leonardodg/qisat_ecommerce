<?php

namespace App\Lib\DetectSystem;

class DetectSystem
{

    private $user_agent;

    private $os_array = array(
        '/windows nt 6.2/i'     => 'Windows 8',
        '/windows nt 6.1/i'     => 'Windows 7',
        '/windows nt 6.0/i'     => 'Windows Vista',
        '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     => 'Windows XP',
        '/windows xp/i'         => 'Windows XP',
        '/windows nt 5.0/i'     => 'Windows 2000',
        '/windows me/i'         => 'Windows ME',
        '/win98/i'              => 'Windows 98',
        '/win95/i'              => 'Windows 95',
        '/win16/i'              => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i'        => 'Mac OS 9',
        '/linux/i'              => 'Linux',
        '/ubuntu/i'             => 'Ubuntu',
        '/iphone/i'             => 'iPhone',
        '/ipod/i'               => 'iPod',
        '/ipad/i'               => 'iPad',
        '/android/i'            => 'Android',
        '/blackberry/i'         => 'BlackBerry',
        '/webos/i'              => 'Mobile'
    );

    private $browser_array = array(
        '/msie/i'       => 'Internet Explorer',
        '/firefox/i'    => 'Firefox',
        '/safari/i'     => 'Safari',
        '/chrome/i'     => 'Chrome',
        '/opera/i'      => 'Opera',
        '/netscape/i'   => 'Netscape',
        '/maxthon/i'    => 'Maxthon',
        '/konqueror/i'  => 'Konqueror',
        '/mobile/i'     => 'Handheld Browser'
    );

    public function __construct($user_agent = null){
        if(is_null($user_agent))
            $this->user_agent = '';
            if(isset($_SERVER['HTTP_USER_AGENT']))
                $this->user_agent = $_SERVER['HTTP_USER_AGENT'];
        else
            $this->user_agent = $user_agent;
    }

    public function getOS()
    {
        $os_platform = "Unknown OS Platform";
        foreach ($this->os_array as $regex => $value) {
            if (preg_match($regex, $this->user_agent)) {
                $os_platform = $value;
            }
        }
        return $os_platform;
    }

    public function getOSVersion()
    {
        return php_uname("r");
    }

    public function getOSBits()
    {
        if (preg_match('/64;/i', $this->user_agent)) {
            return 64;
        }
        return 32;
    }

    public function getBrowser()
    {
        $browser = "Unknown Browser";
        foreach ($this->browser_array as $regex => $value) {
            if (preg_match($regex, $this->user_agent)) {
                $browser = $value;
            }
        }
        return $browser;
    }

    public function getBrowserVersion()
    {
        $browser = 0;
        foreach ($this->browser_array as $regex => $value) {
            if (preg_match($regex, $this->user_agent)) {
                $browser = preg_split($regex, $this->user_agent)[1];
            }
        }
        return floatval(substr($browser, 1));
    }
}
?>
