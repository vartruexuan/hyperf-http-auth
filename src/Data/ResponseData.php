<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2021/1/4
 * Time: 10:56
 */

namespace Vartruexuan\HyperfHttpAuth\Data;

use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Class ResponseData
 *
 * @package App\Data
 */
class ResponseData
{

    /**
     * @Inject
     * @var ResponseInterface
     */
    public $response;
    /**
     * json xml raw
     * @var string
     */
    private $dataType = 'json';

    /**
     * 获取当前数据类型
     *
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * 设置当前数据类型
     *
     * @param $type
     *
     * @return $this
     */
    public function setDataType($type)
    {
        $this->dataType = $type;
        return $this;
    }

    /**
     * 返回成功
     *
     * @param $data
     * @param $message
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendSuccess($data = [], $message = ''): PsrResponseInterface
    {
        return $this->sendData(ErrorCode::SUCCESS, $message, $data);
    }

    /**
     * 返回错误
     *
     * @param       $message
     * @param int   $code
     * @param array $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendError($message = '', $code = ErrorCode::SERVER_ERROR, $data = []): PsrResponseInterface
    {
        return $this->sendData($code, $message, $data);
    }

    /**
     * 返回消息
     *
     * @param        $code
     * @param string $message
     * @param array  $data
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendData($code, $message = '', $data = []): PsrResponseInterface
    {
        $dataType = $this->dataType;
        return $this->response->{$dataType}([
            'code' => $code,
            'data' => $data,
            'message' => $message ? $message : ErrorCode::getMessage($code),
        ]);
    }

}
