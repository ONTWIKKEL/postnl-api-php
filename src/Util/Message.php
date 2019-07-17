<?php
declare(strict_types=1);
/**
 * Copyright (c) 2015 Michael Dowling, https://github.com/mtdowling <mtdowling@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Firstred\PostNL\Util;

use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\ResponseFactory;
use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Message
 */
class Message
{
    const RFC7230_HEADER_REGEX = "(^([^()<>@,;:\\\"/[\]?={}\x01-\x20\x7F]++):[ \t]*+((?:[ \t]*+[\x21-\x7E\x80-\xFF]++)*+)[ \t]*+\r?\n)m";
    const RFC7230_HEADER_FOLD_REGEX = "(\r?\n[ \t]++)";

    /**
     * Returns the string representation of an HTTP message.
     *
     * @param MessageInterface $message Message to convert to a string.
     *
     * @return string
     */
    public static function str(MessageInterface $message)
    {
        if ($message instanceof RequestInterface) {
            $msg = trim($message->getMethod().' '.$message->getRequestTarget())
                .' HTTP/'.$message->getProtocolVersion();
            if (!$message->hasHeader('host')) {
                $msg .= "\r\nHost: ".$message->getUri()->getHost();
            }
        } elseif ($message instanceof ResponseInterface) {
            $msg = 'HTTP/'.$message->getProtocolVersion().' '
                .$message->getStatusCode().' '
                .$message->getReasonPhrase();
        } else {
            throw new InvalidArgumentException('Unknown message type');
        }

        foreach ($message->getHeaders() as $name => $values) {
            $msg .= "\r\n{$name}: ".implode(', ', $values);
        }

        return "{$msg}\r\n\r\n".$message->getBody();
    }

    /**
     * Parses a response message string into a response object.
     *
     * @param string $message Response message string.
     *
     * @return ResponseInterface
     */
    public static function parseResponse(string $message): ResponseInterface
    {
        $data = static::parseMessage($message);
        // According to https://tools.ietf.org/html/rfc7230#section-3.1.2 the space
        // between status-code and reason-phrase is required. But browsers accept
        // responses without space and reason as well.
        if (!preg_match('/^HTTP\/.* [0-9]{3}( .*|$)/', $data['start-line'])) {
            throw new InvalidArgumentException(sprintf("Invalid response string: %s", $data['start-line']));
        }
        $parts = explode(' ', $data['start-line'], 3);

        /** @var ResponseFactory $factory */
        $factory = Psr17FactoryDiscovery::findResponseFactory();

        return $factory->createResponse(
            $parts[1],
            $data['headers'],
            $data['body'],
            explode('/', $parts[0])[1],
            isset($parts[2]) ? $parts[2] : null
        );
    }

    /**
     * Parses an HTTP message into an associative array.
     *
     * The array contains the "start-line" key containing the start line of
     * the message, "headers" key containing an associative array of header
     * array values, and a "body" key containing the body of the message.
     *
     * @param string $message HTTP request or response to parse.
     *
     * @return array
     *
     * @internal
     */
    private static function parseMessage($message): array
    {
        if (!$message) {
            throw new InvalidArgumentException('Invalid message');
        }
        $message = ltrim($message, "\r\n");
        $messageParts = preg_split("/\r?\n\r?\n/", $message, 2);
        if (false === $messageParts || 2 !== count($messageParts)) {
            throw new InvalidArgumentException('Invalid message: Missing header delimiter');
        }
        list($rawHeaders, $body) = $messageParts;
        $rawHeaders .= "\r\n"; // Put back the delimiter we split previously
        $headerParts = preg_split("/\r?\n/", $rawHeaders, 2);
        if (false === $headerParts || 2 !== count($headerParts)) {
            throw new InvalidArgumentException('Invalid message: Missing status line');
        }
        list($startLine, $rawHeaders) = $headerParts;
        if (preg_match("/(?:^HTTP\/|^[A-Z]+ \S+ HTTP\/)(\d+(?:\.\d+)?)/i", $startLine, $matches) && $matches[1] === '1.0') {
            // Header folding is deprecated for HTTP/1.1, but allowed in HTTP/1.0
            $rawHeaders = preg_replace(self::RFC7230_HEADER_FOLD_REGEX, ' ', $rawHeaders);
        }
        /** @var array[] $headerLines */
        $count = preg_match_all(self::RFC7230_HEADER_REGEX, $rawHeaders, $headerLines, PREG_SET_ORDER);
        // If these aren't the same, then one line didn't match and there's an invalid header.
        if (substr_count($rawHeaders, "\n") !== $count) {
            // Folding is deprecated, see https://tools.ietf.org/html/rfc7230#section-3.2.4
            if (preg_match(self::RFC7230_HEADER_FOLD_REGEX, $rawHeaders)) {
                throw new InvalidArgumentException('Invalid header syntax: Obsolete line folding');
            }
            throw new InvalidArgumentException('Invalid header syntax');
        }
        $headers = [];
        foreach ($headerLines as $headerLine) {
            $headers[$headerLine[1]][] = $headerLine[2];
        }

        return [
            'start-line' => $startLine,
            'headers'    => $headers,
            'body'       => $body,
        ];
    }
}
