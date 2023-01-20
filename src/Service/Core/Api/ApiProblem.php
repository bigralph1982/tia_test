<?php

namespace App\Service\Core\Api;

/**
 * A wrapper for holding data to be used for a application/problem+json response
 */
class ApiProblem {

    private static $titles_codes = array(
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        412 => 'Precondition Failed',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error',
        501 => 'Not Implemented'
    );
    private $statusCode;
    private $type;
    private $title;
    private $extraData = array();

    public function __construct($statusCode, $type = null, $title = null) {

        if (!$statusCode) {
            $statusCode = 404;
            if ($title && $title == "A Token was not found in the TokenStorage.") {
                $statusCode = 401;
            }
        }
        $this->statusCode = $statusCode;
        if ($type) {
            $this->type = $type;
            if ($title) {
                $this->title = $title;
            }
        } else {
            $this->type = 'error';
            $this->title = self::$titles_codes[$statusCode];
        }
    }

    public function toArray() {
        return array_merge(
                $this->extraData,
                array(
                    'error' => true,
                    'status' => $this->statusCode,
                    'type' => $this->type,
                    'title' => $this->title,
                )
        );
    }

    public function set($name, $value) {
        $this->extraData[$name] = $value;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getTitle() {
        return $this->title;
    }

}
