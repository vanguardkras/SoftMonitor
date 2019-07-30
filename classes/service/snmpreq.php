<?php

/**
 * Class for checking SNMP and ICMP data for monitored devices.
 *
 * @author shayan-ma
 */
class SnmpReq
{
    /** SNMP parameters. */
    
    /**
     * Authentication password.
     * @var string 
     */
    private $auth_pass;
    
    /**
     * Authentication protocol. Eigher 'MD5' or 'SHA'.
     * @var string 
     */
    private $auth_protocol = 'MD5';
    
    /**
     * Comunity string.
     * @var string 
     */
    private $community;
    
    /**
     * An IP-address or a domain name.
     * @var string 
     */
    private $ip;
    
    /**
     * Password phrase for a connection.
     * @var string 
     */
    private $priv_passphrase = 'SeCrEtPhRaSeFoRtHeCoNnEcTiOn';
    
    /**
     * Priv Protocol for SNMP. Either 'AES' or 'DES'.
     * @var string 
     */
    private $priv_protocol = 'AES';
    
    /**
     * Security level. Either 'authNoPriv' or 'authPriv'.
     * @var string 
     */
    private $sec_level = 'authNoPriv';
    
    /**
     * Security name. Login in other words.
     * @var type 
     */
    private $sec_name;
    
    /**
     * SNMP-version. 3, 2, or 1.
     * @var type 
     */
    private $version;
    
    public $words = LIC_RESTR;
    
    /**
     * Removes type from an SNMP-result.
     * @param string $res
     * @return mixed
     */
    private static function cleanRes(string $res)
    {
        $res = explode(':', $res);
        return trim($res[1]);
    }
    
    /**
     * Gets results from an SNMP-request.
     * @param string $oid
     * @param int $timeout
     * @param int $attempts
     * @return mixed
     */
    public function get(string $oid, int $timeout, int $attempts) 
    {
        if ($this->version == 1 || $this->version == 2) {
            if (isset($this->community) && isset($this->ip)) {
                if (
                        !$result = @snmp2_get(
                        $this->ip,
                        $this->community,
                        $oid,
                        $timeout * 1000,
                        $attempts
                        )) {
                    return false;
                }
            } else {
                return false;
            }
        } elseif ($this->version == 3) {
            if (
                    !$result = @snmp3_get(
                    $this->ip,
                    $this->sec_name,
                    $this->sec_level,
                    $this->auth_protocol,
                    $this->auth_pass,
                    $this->priv_protocol,
                    $this->priv_passphrase,
                    $oid,
                    $timeout * 1000,
                    $attempts
                    )) {
                return false;
            }
        }
        return self::cleanRes($result);
    }

    /**
     * Sets authentication password.
     * @param string $pass
     * @return \SnmpReq
     */
    public function setAuthPass(string $pass): SnmpReq
    {
        $this->auth_pass = $pass;
        return $this;
    }
    
    /**
     * Sets authentication protocol.
     * 
     * Possible variants: 'MD5', 'SHA'
     * @param string $protocol
     * @return \SnmpReq
     * @throws \Exception
     */
    public function setAuthProtocol(string $protocol): SnmpReq
    {
        $protocols = ['MD5', 'SHA'];
        try {
            if (in_array($protocol, $protocols)) {
                $this->auth_protocol = $protocol;
                return $this;
            } else {
                throw new \Exception('Auth protocol should be either MD5 or SHA.');
            }
        } catch (Exception $ex) {
            $trace = $ex->getTrace();
            echo $ex->getMessage() . 
                    ' FILE: ' . $trace[0]['file'] .
                    ' LINE: ' . $trace[0]['line'];
        }
    }
    
    /**
     * Sets community string.
     * @param string $community
     * @return \SnmpReq
     */
    public function setCommunity(string $community): SnmpReq
    {
        $this->community = $community;
        return $this;
    }
    
    /**
     * Sets the device's IP address.
     * @param string $ip
     * @return \SnmpReq
     */
    public function setIp(string $ip): SnmpReq
    {
        $this->ip = $ip;
        return $this;
    }
    
    /**
     * Sets Priv Protocol.
     * 
     * Possible variants: 'DES', 'AES'
     * @param string $protocol
     * @return \SnmpReq
     * @throws \Exception
     */
    public function setPrivProtocol(string $protocol): SnmpReq
    {
        $protocols = ['AES', 'DES'];
        try {
            if (in_array($protocol, $protocols)) {
                $this->priv_protocol = $protocol;
                return $this;
            } else {
                throw new \Exception('Privace protocol should be either DES or AES.');
            }
        } catch (Exception $ex) {
            $trace = $ex->getTrace();
            echo $ex->getMessage() . 
                    ' FILE: ' . $trace[0]['file'] .
                    ' LINE: ' . $trace[0]['line'];
        }
    }
    
    /**
     * Sets security Name (login in other words).
     * @param string $name
     * @return \SnmpReq
     */
    public function setSecName(string $name): SnmpReq
    {
        $this->sec_name = $name;
        return $this;
    }
    
    /**
     * Sets SNMP-version.
     * 
     * @param int $version
     * @return \SnmpReq
     */
    public function setSnmpVersion(int $version): SnmpReq
    {
        $this->version = $version;
        return $this;
    }
    
    /**
     * Sends ping to a certain host.
     * @param string $host IP-address or domain name.
     * @param int $timeout Timeout in ms.
     * @return mixed Response time.
     */
    public static function ping(string $host, int $timeout = 100) 
    {
        /* ICMP ping packet with a pre-calculated checksum */
        $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
        
        $socket = socket_create(AF_INET, SOCK_RAW, 1);
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, 
                ['sec' => 0, 'usec' => $timeout * 1000]);
        
        if (@socket_connect($socket, $host, null)) {
            $ts = microtime(true);
            socket_send($socket, $package, 16, 0);
            if (@socket_read($socket, 255)) {
                $result = intval(1000 * (microtime(true) - $ts));
            } else {
                $result = false;
            }
            socket_close($socket);
            return $result;
        } else {
            return false;
        }
    }
    
}
