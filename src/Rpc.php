<?phpnamespace yqn\sdkmiddle;class Rpc{    public function __construct($config)    {        $this->conn = fsockopen($config["host"], $config["port"], $errno, $errstr, $config["time_out"]);        if (!$this->conn) {            return;        }        stream_set_timeout($this->conn, $config["time_out"]);    }    /**     * 请求RPC服务器     * @param $method 方法名称     * @param $params 参数     * @return array     */    public function Call($method, $params)    {        if (!$this->conn) {            return ["code" => 500, "msg" => "rpc连接信息不正确"];        }        $err = fwrite($this->conn, json_encode(array(                'method' => $method,                'params' => array($params),                'id' => 0,            )) . "\n");        if ($err === false)            return ["code" => 500, "msg" => "RPC不能写入信息"];        $line = fgets($this->conn);        if ($line === false) {            return ["code" => 500, "msg" => "未获取信息"];        }        $re_arr = json_decode($line, true);        if (is_null($re_arr)) {            return ["code" => 500, "msg" => "获取信息超时"];        }        return $re_arr["result"];    }}