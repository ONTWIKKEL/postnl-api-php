<?php
declare(strict_types=1);
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2017-2019 Michael Dekker (https://github.com/firstred)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and
 * associated documentation files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
 * is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 * NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author    Michael Dekker <git@michaeldekker.nl>
 *
 * @copyright 2017-2019 Michael Dekker
 *
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace Firstred\PostNL\Service;

use Exception;
use Firstred\PostNL\Entity\AbstractEntity;
use Firstred\PostNL\Entity\Request\ConfirmShipmentRequest;
use Firstred\PostNL\Entity\Response\ConfirmingResponseShipment;
use Firstred\PostNL\Exception\CifDownException;
use Firstred\PostNL\Exception\HttpClientException;
use Firstred\PostNL\Http\Client;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ConfirmingService
 */
class ConfirmingService extends AbstractService
{
    // API Version
    const VERSION = '2.0';

    // Endpoints
    const LIVE_ENDPOINT = 'https://api.postnl.nl/shipment/v1_10/confirm';
    const SANDBOX_ENDPOINT = 'https://api-sandbox.postnl.nl/shipment/v1_10/confirm';

    /**
     * Generate a single barcode via REST
     *
     * @param ConfirmShipmentRequest $confirming
     *
     * @return ConfirmingResponseShipment
     *
     * @throws HttpClientException
     * @throws CifDownException
     * @throws Exception
     *
     * @since 1.0.0
     * @since 2.0.0 Strict typing
     */
    public function confirmShipment(ConfirmShipmentRequest $confirming): ConfirmingResponseShipment
    {
        $request = $this->buildConfirmRequest($confirming);
        $response = Client::getInstance()->doRequest($request);
        $object = $this->processConfirmResponse($response);

        if ($object instanceof ConfirmingResponseShipment) {
            return $object;
        }

        if ($response->getStatusCode() === 200) {
            throw new CifDownException('Invalid API Response', 0, null, $request, $response);
        }

        throw new HttpClientException('Unable to confirm', 0, null, $request, $response);
    }

    /**
     * @param ConfirmShipmentRequest $confirming
     *
     * @return RequestInterface
     *
     * @since 1.0.0
     */
    public function buildConfirmRequest(ConfirmShipmentRequest $confirming): RequestInterface
    {
        $body = json_decode(json_encode($confirming), true);
        $body['Message'] = [
            'MessageID'        => '1',
            'MessageTimeStamp' => date('d-m-Y 00:00:00'),
            'Printertype'      => 'GraphicFile|PDF',
        ];
        $body['Customer'] = $this->postnl->getCustomer();

        return Psr17FactoryDiscovery::findRequestFactory()->createRequest(
            'POST',
            $this->postnl->getSandbox() ? static::SANDBOX_ENDPOINT : static::LIVE_ENDPOINT
        )
            ->withHeader('Accept', 'application/json')
            ->withHeader('Content-Type', 'application/json;charset=UTF-8')
            ->withHeader('apikey', $this->postnl->getApiKey())
            ->withBody(Psr17FactoryDiscovery::findStreamFactory()->createStream(json_encode($body)))
        ;
    }

    /**
     * Process Confirm REST Response
     *
     * @param ResponseInterface $response
     *
     * @return null|ConfirmingResponseShipment
     *
     *
     * @since 1.0.0
     * @since 2.0.0 Strict typing
     */
    public function processConfirmResponse(ResponseInterface $response): ?ConfirmingResponseShipment
    {
        static::validateResponse($response);
        $body = json_decode((string) $response->getBody(), true);
        if (isset($body['ConfirmingResponseShipments'])) {
            /** @var ConfirmingResponseShipment $object */
            $object = AbstractEntity::jsonDeserialize($body['ConfirmingResponseShipments']);

            return $object;
        }

        return null;
    }

    /**
     * Confirm multiple shipments
     *
     * @param ConfirmShipmentRequest[] $confirms ['uuid' => ConfirmShipmentRequest, ...]
     *
     * @return ConfirmingResponseShipment[]
     *
     * @throws Exception
     *
     * @since 1.0.0
     */
    public function confirmShipments(array $confirms): array
    {
        $httpClient = Client::getInstance();

        foreach ($confirms as $confirm) {
            $httpClient->addOrUpdateRequest(
                (string) $confirm->getId(),
                $this->buildConfirmRequest($confirm)
            );
        }

        $confirmingResponses = [];
        foreach ($httpClient->doRequests() as $uuid => $response) {
            try {
                $confirming = $this->processConfirmResponse($response);
                if (!$confirming instanceof ConfirmingResponseShipment) {
                    throw new HttpClientException('Invalid API Response', 0, null, null, $response);
                }
            } catch (Exception $e) {
                $confirming = $e;
            }

            $confirmingResponses[$uuid] = $confirming;
        }

        return $confirmingResponses;
    }
}
