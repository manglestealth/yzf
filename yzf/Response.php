<?php
namespace yzf;

class Response
{
    private $status;

    private $headers;

    private $cookies;

    private $body;

    private $length;

    private static $message = array(
        // [Informational 1xx]
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        // [Successful 2xx]
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        // [Redirection 3xx]
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        // [Client Error 4xx]
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        // [Server Error 5xx]
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );

    public function __construct()
    {
        $this->status(200);
        $this->header('Content-Type', 'text/html');
    }

    public function status($status = null)
    {
        if (!is_null($status)) {
            $this->status = intval($status);
        }
        return $this->status;
    }

    public function headers()
    {
        return $this->headers;
    }

    public function header($key, $value = null)
    {
        if (!is_null($value)) {
            $this->headers[$key] = $value;
        }

        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }

    public function body($body = null)
    {
        if (!is_null($body)) {
            $this->body = '';
            $this->length = 0;
            $this->write($body);
        }
        return $this->body;
    }

    public function write($body)
    {
        $body = (string)$body;
        $this->length += strlen($body);
        $this->body .= $body;
        $this->header('Content-length', $this->length);
        return $body;
    }

    public function setCookie($name, $value, $expire, $path = null, $domain = null, $secure = null, $httponly = false)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
    }

    public function deleteCookie($name)
    {
        setcookie($name, '', -3600);
    }

    public function finalize()
    {
        if (in_array($this->status, array(204, 304))) {
            unset($this->headers['Content-Type']);
        }
    }

    public static function getMessage($status)
    {
        return self::$message[$status];
    }

    public function canHaveBody()
    {
        return ($this->status < 100 || $this->status >= 200) && $this->status != 204 && $this->status != 304;
    }
    protected function sendHeaders()
    {
        $this->finalize();

        header('HTTP/1.1 ' . Response::getMessage($this->status()));
        flush();
    }

    public function send()
    {
        if(!headers_sent()){
            $this->sendHeaders();
        }

        if($this->canHaveBody()){
            echo $this->body;
        }
    }
}