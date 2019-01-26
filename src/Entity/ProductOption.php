<?php
declare(strict_types=1);
/**
 * The MIT License (MIT)
 *
 * *Copyright (c) 2017-2019 Michael Dekker (https://github.com/firstred)
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

namespace Firstred\PostNL\Entity;

use Firstred\PostNL\Service\BarcodeService;
use Firstred\PostNL\Service\ConfirmingService;
use Firstred\PostNL\Service\DeliveryDateService;
use Firstred\PostNL\Service\LabellingService;
use Firstred\PostNL\Service\LocationService;
use Firstred\PostNL\Service\ShippingStatusService;
use Firstred\PostNL\Service\TimeframeService;

/**
 * Class ProductOption
 *
 * @method string|null getCharacteristic()
 * @method string|null getOption()
 *
 * @method ProductOption setCharacteristic(string|null $characteristic = null)
 * @method ProductOption setOption(string|null $option = null)
 */
class ProductOption extends AbstractEntity
{
    /** @var string[][] $defaultProperties */
    public static $defaultProperties = [
        'Barcode'        => [
            'Characteristic' => BarcodeService::DOMAIN_NAMESPACE,
            'Option'         => BarcodeService::DOMAIN_NAMESPACE,
        ],
        'Confirming'     => [
            'Characteristic' => ConfirmingService::DOMAIN_NAMESPACE,
            'Option'         => ConfirmingService::DOMAIN_NAMESPACE,
        ],
        'Labelling'      => [
            'Characteristic' => LabellingService::DOMAIN_NAMESPACE,
            'Option'         => LabellingService::DOMAIN_NAMESPACE,
        ],
        'ShippingStatus' => [
            'Characteristic' => ShippingStatusService::DOMAIN_NAMESPACE,
            'Option'         => ShippingStatusService::DOMAIN_NAMESPACE,
        ],
        'DeliveryDate'   => [
            'Characteristic' => DeliveryDateService::DOMAIN_NAMESPACE,
            'Option'         => DeliveryDateService::DOMAIN_NAMESPACE,
        ],
        'Location'       => [
            'Characteristic' => LocationService::DOMAIN_NAMESPACE,
            'Option'         => LocationService::DOMAIN_NAMESPACE,
        ],
        'Timeframe'      => [
            'Characteristic' => TimeframeService::DOMAIN_NAMESPACE,
            'Option'         => TimeframeService::DOMAIN_NAMESPACE,
        ],
    ];
    // @codingStandardsIgnoreStart
    /** @var string|null $Characteristic */
    protected $Characteristic;
    /** @var string|null $Option */
    protected $Option;
    // @codingStandardsIgnoreEnd

    /**
     * @param string|null $characteristic
     * @param string|null $option
     */
    public function __construct($characteristic = null, $option = null)
    {
        parent::__construct();

        $this->setCharacteristic($characteristic);
        $this->setOption($option);
    }
}
