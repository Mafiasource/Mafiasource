<?PHP

/*
Copyright (c) 2009 Robert Hafner original, 2017 Michael Carrein modifications
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
following conditions are met:

    * Redistributions of source code must retain the above copyright notice, this list of conditions and the following
      disclaimer.
    * Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the
      following disclaimer in the documentation and/or other materials provided with the distribution.
    * Neither the name of the <ORGANIZATION> nor the names of its contributors may be used to endorse or promote
      products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

class SessionManager extends SessionHandler
{
    // Always call on any served page that uses sessions (in our case, always)
    static function sessionStart($name, $limit = 0, $path = '/', $domain = null, $secure = null)
    {
        ini_set('session.name', $name . '_Session');
        ini_set('session.auto_start', 'Off');
        //ini_set('session.use_trans_sid', 0);
        ini_set('session.cookie_doimain', $domain);
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_cookies', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_lifetime', 1440);
        ini_set('session.cookie_secure', $secure);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.cache_expire', 30);
        //ini_set('session.sid_length', 256);
        //ini_set('session.sid_bits_per_character', 6);
        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 100);
        ini_set('session.gc_maxlifetime', 1440);
    	session_name($name . '_Session');
    	$https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);
    	session_set_cookie_params($limit, $path, $domain, $https, true);
    	session_start();
    	if(self::validateSession()) // Is this sess valid?
    	{
    		if(!self::preventHijacking()) // Is this sess request a hijacking attempt?
    		{
    			$_SESSION = array(); // Yes, clear all sess variables
    			$_SESSION['_IPaddress'] = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
                            ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    			$_SESSION['_userAgent'] = isset($_SERVER['HTTP_USER_AGENT']) 
                            ? $_SERVER['HTTP_USER_AGENT'] : 'Undefined';
    			self::regenerateSession();
    		} // .5% chance @ regeneration | make sure current session is atleast 5 minutes old.
            elseif((random_int(1, 1000) <= 5) && $_SESSION['_lastNewSession'] < (time()-300))
            {
    			self::regenerateSession();
            }
    	} else {
            //var_dump($_SESSION);
    		$_SESSION = array();
    		session_destroy();
    		session_start();
            self::regenerateSession();
    	}
	}
    
	static function regenerateSession()
	{
		if(isset($_SESSION['_OBSOLETE']) || (isset($_SESSION['_OBSOLETE']) && $_SESSION['_OBSOLETE'] === true)) return;
		$_SESSION['_OBSOLETE'] = true;
		$_SESSION['_EXPIRES'] = time() + 10; // avoid session overlapping (queued requests) by letting an old session expire after 10 seconds.
		session_regenerate_id(false);
		$newSession = session_id();
		session_write_close();
		session_id($newSession);
		session_start();
		unset($_SESSION['_OBSOLETE']);
		unset($_SESSION['_EXPIRES']);
        $_SESSION['_lastNewSession'] = time();
	}
    
	static protected function validateSession()
	{
        if(!isset($_SESSION['_lastNewSession'])) $_SESSION['_lastNewSession'] = time();
        
        if(isset($_SESSION['_OBSOLETE']) && !isset($_SESSION['_EXPIRES']))
			return false;
            
        if(isset($_SESSION['_OBSOLETE']) && isset($_SESSION['_EXPIRES']) && $_SESSION['_EXPIRES'] < time())
            return false;
            
        return true;
	}
	
    static protected function preventHijacking()
    {
        $aolProxies = array('195.93.', '205.188', '198.81.', '207.200', '202.67.', '64.12.9');
        if(!isset($_SESSION['_IPaddress']) || !isset($_SESSION['_userAgent']))
            return false;
        
        if(isset($_SERVER['HTTP_USER_AGENT']))
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
        else
            $userAgent = 'Undefined';
        
        if( $_SESSION['_userAgent'] != $userAgent
          && !( strpos($_SESSION['_userAgent'], '‘Trident’') !== false
          && strpos($userAgent, '‘Trident’') !== false))
            return false;
            
        $sessionIpSegment = substr($_SESSION['_IPaddress'], 0, 7);
        $remoteIpHeader = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        $remoteIpSegment = substr($remoteIpHeader, 0, 7);
        if($_SESSION['_IPaddress'] != $remoteIpHeader
            && !(in_array($sessionIpSegment, $aolProxies) && in_array($remoteIpSegment, $aolProxies)))
            return false;
            
        if($_SESSION['_userAgent'] != $userAgent)
            return false;
            
        return true;
    }
    
    // Always call after any served page that uses sessions
    static function sessionWriteClose()
    {
        session_write_close();
    }
}
