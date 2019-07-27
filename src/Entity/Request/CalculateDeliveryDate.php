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

namespace Firstred\PostNL\Entity\Request;

use Firstred\PostNL\Entity\AbstractEntity;
use Firstred\PostNL\Misc\ValidateAndFix;
use ReflectionException;
use TypeError;

/**
 * Class CalculateDeliveryDate
 *
 * This class is both the container and can be the actual CalculateDeliveryDate object itself!
 */
class CalculateDeliveryDate extends AbstractEntity
{
    /**
     * Date/time of preparing the shipment for sending
     *
     * @pattern ^(?:[0-3]\d-[01]\d-[12]\d{3}\s+)[0-2]\d:[0-5]\d(?:[0-5]\d)$
     *
     * @example 03-07-2019 17:00:00
     *
     * @var string|null $ShippingDate
     *
     * @since   2.0.0
     */
    protected $shippingDate;

    /**
     * The duration it takes for the shipment to be delivered to PostNL in days. A value of 1 means that the parcel will be delivered to PostNL on the same day as the date specified in ShippingDate. A value of 2 means the parcel will arrive at PostNL a day later etc
     *
     * @pattern ^\d{1,10}$
     *
     * @example 2
     *
     * @var int|null $shippingDuration
     *
     * @since   2.0.0
     */
    protected $shippingDuration;

    /**
     * Cut-off time
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @var string|null $cutOffTime
     *
     * @since   2.00
     */
    protected $cutOffTime;

    /**
     * Zip / postal code
     *
     * @var string|null $postalCode
     *
     * @pattern ^.{0,10}$
     *
     * @example 2132WT
     *
     * @since   2.0.0
     */
    protected $postalCode;

    /**
     * Country code
     *
     * @pattern ^(?:NL|BE))$
     *
     * @example NL
     *
     * @var string|null $countryCode
     *
     * @since   2.0.0
     */
    protected $countryCode;

    /**
     * Origin country code
     *
     * @pattern ^(?:NL|BE))$
     *
     * @example NL
     *
     * @var string|null $originCountryCode
     *
     * @since   2.0.0
     */
    protected $originCountryCode;

    /**
     * City
     *
     * @pattern ^.{0,35}$
     *
     * @example Hoofddorp
     *
     * @var string|null $city
     *
     * @since   2.0.0
     */
    protected $city;

    /**
     * The street name of the delivery address.
     *
     * @pattern ^.{0,95}$
     *
     * @example Siriusdreef
     *
     * @var string|null $street
     *
     * @since   2.0.0
     */
    protected $street;

    /**
     * House number
     *
     * @pattern ^\d{1,10}$
     *
     * @example 42
     *
     * @var int|null $houseNumber
     *
     * @since   2.0.0
     */
    protected $houseNumber;

    /**
     * House number extension
     *
     * @var string|null $houseNrExt
     *
     * @pattern ^.{0,35}$
     *
     * @example A
     *
     * @since   2.0.0
     */
    protected $houseNrExt;

    /**
     * Available options: Daytime, Evening, Morning, Noon, Sunday, Sameday, Afternoon, MyTime, Pickup
     *
     * @pattern ^(?:Daytime|Evening|Morning|Noon|Sunday|Sameday|Afternoon|MyTime|Pickup)$
     *
     * @example Daytime
     *
     * @var string[]|null $options
     *
     * @since   2.0.0
     */
    protected $options;

    /**
     * Cut-off time Monday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @var string|null $cutOffTimeMonday
     *
     * @since 2.0.0
     */
    protected $cutOffTimeMonday;

    /**
     * Available on Monday
     *
     * @pattern N/A
     *
     * @example N/A
     *
     * @var bool|null $availableMonday
     *
     * @since   2.0.0
     */
    protected $availableMonday;

    /**
     * Cut-off time Tuesday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @var string|null $cutOffTimeTuesday
     *
     * @since 2.0.0
     */
    protected $cutOffTimeTuesday;

    /**
     * Available on Tuesday
     *
     * @pattern N/A
     *
     * @example N/A
     *
     * @var bool|null $availableTuesday
     *
     * @since   2.0.0
     */
    protected $availableTuesday;

    /**
     * Cut-off time Wednesday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @var string|null $cutOffTimeWednesday
     *
     * @since 2.0.0
     */
    protected $cutOffTimeWednesday;

    /**
     * Available on Wednesday
     *
     * @pattern N/A
     *
     * @example N/A
     *
     * @var bool|null $availableWednesday
     *
     * @since   2.0.0
     */
    protected $availableWednesday;

    /**
     * Cut-off time Thursday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @var string|null $cutOffTimeThursday
     *
     * @since 2.0.0
     */
    protected $cutOffTimeThursday;

    /**
     * Available on Thursday
     *
     * @pattern N/A
     *
     * @example N/A
     *
     * @var bool|null $availableThursday
     *
     * @since   2.0.0
     */
    protected $availableThursday;

    /**
     * Cut-off time Friday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @var string|null $cutOffTimeFriday
     *
     * @since 2.0.0
     */
    protected $cutOffTimeFriday;

    /**
     * Available on Friday
     *
     * @pattern N/A
     *
     * @example N/A
     *
     * @var bool|null $availableFriday
     *
     * @since   2.0.0
     */
    protected $availableFriday;

    /**
     * Cut-off time Saturday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @var string|null $cutOffTimeSaturday
     *
     * @since 2.0.0
     */
    protected $cutOffTimeSaturday;

    /**
     * Available on Saturday
     *
     * @pattern N/A
     *
     * @example N/A
     *
     * @var bool|null $availableSaturday
     *
     * @since   2.0.0
     */
    protected $availableSaturday;

    /**
     * Cut-off time Sunday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @var string|null $cutOffTimeSunday
     *
     * @since 2.0.0
     */
    protected $cutOffTimeSunday;

    /**
     * Available on Sunday
     *
     * @pattern N/A
     *
     * @example N/A
     *
     * @var bool|null $availableSunday
     *
     * @since   2.0.0
     */
    protected $availableSunday;

    /**
     * CalculateDeliveryDate constructor.
     *
     * @throws ReflectionException
     * @throws TypeError
     *
     * @since 2.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get zip / postal code
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$postalCode
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * Set the zip / postcode
     *
     * @pattern ^.{0,10}$
     *
     * @param string|null $postcode
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 2132WT
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$postalCode
     */
    public function setPostalCode(?string $postcode = null): CalculateDeliveryDate
    {
        $this->postalCode = ValidateAndFix::postcode($postcode);

        return $this;
    }

    /**
     * Get city
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$city
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @pattern ^.{0,35}$
     *
     * @param string|null $city
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example Hoofddorp
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$city
     */
    public function setCity(?string $city): CalculateDeliveryDate
    {
        $this->city = ValidateAndFix::city($city);

        return $this;
    }

    /**
     * Get country code
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$countryCode
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * Set country code
     *
     * @pattern ^(?:NL|BE)$
     *
     * @param string|null $countryCode
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example NL
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$countryCode
     */
    public function setCountryCode(?string $countryCode): CalculateDeliveryDate
    {
        $this->countryCode = ValidateAndFix::countryCodeNlBe($countryCode);

        return $this;
    }

    /**
     * Get house number
     *
     * @return int|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$houseNumber
     */
    public function getHouseNumber(): ?int
    {
        return $this->houseNumber;
    }

    /**
     * Set house number
     *
     * @pattern ^\d{1,10}$
     *
     * @param string|int|float|null $houseNumber
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 42
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$houseNumber
     */
    public function setHouseNumber($houseNumber): CalculateDeliveryDate
    {
        $this->houseNumber = ValidateAndFix::integer($houseNumber);

        return $this;
    }

    /**
     * Get house number extension
     *
     * Get house number extension
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$houseNrExt
     */
    public function getHouseNrExt(): ?string
    {
        return $this->houseNrExt;
    }

    /**
     * Set house number extension
     *
     * @pattern ^.{0,35}$
     *
     * @param string|null $houseNrExt
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example A
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$houseNrExt
     */
    public function setHouseNrExt(?string $houseNrExt): CalculateDeliveryDate
    {
        $this->houseNrExt = ValidateAndFix::genericString($houseNrExt);

        return $this;
    }

    /**
     * Get options
     *
     * @return string[]|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$options
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * Set options
     *
     * @pattern ^(?:Daytime|Evening|Morning|Noon|Sunday|Sameday|Afternoon|MyTime|Pickup)$
     *
     * @param string[]|null $options
     *
     * @return static
     *
     * @throws TypeError
     *
     * @example Daytime
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$options
     */
    public function setOptions(?array $options): CalculateDeliveryDate
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get origin country code
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$originCountryCode
     */
    public function getOriginCountryCode(): ?string
    {
        return $this->originCountryCode;
    }

    /**
     * Set origin country code
     *
     * @pattern ^(?:NL|BE)$
     *
     * @param string|null $originCountryCode
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example NL
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$originCountryCode
     */
    public function setOriginCountryCode(?string $originCountryCode): CalculateDeliveryDate
    {
        $this->originCountryCode = ValidateAndFix::countryCodeNlBe($originCountryCode);

        return $this;
    }

    /**
     * Get shipping date
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$shippingDate
     */
    public function getShippingDate(): ?string
    {
        return $this->shippingDate;
    }

    /**
     * Set shipping date
     *
     * @pattern ^(?:[0-3]\d-[01]\d-[12]\d{3}\s+)[0-2]\d:[0-5]\d(?:[0-5]\d)$
     *
     * @param string|null $shippingDate
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 03-07-2019 17:00:00
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$shippingDate
     */
    public function setShippingDate(?string $shippingDate): CalculateDeliveryDate
    {
        $this->shippingDate = ValidateAndFix::dateTime($shippingDate);

        return $this;
    }

    /**
     * Get shipping duration
     *
     * @return int|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$shippingDuration
     */
    public function getShippingDuration(): ?int
    {
        return $this->shippingDuration;
    }

    /**
     * Set shipping duration
     *
     * @pattern ^\d{1,10}$
     *
     * @param int|null $shippingDuration
     *
     * @return static
     *
     * @throws TypeError
     *
     * @example 2
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$shippingDuration
     */
    public function setShippingDuration(?int $shippingDuration): CalculateDeliveryDate
    {
        $this->shippingDuration = $shippingDuration;

        return $this;
    }

    /**
     * Get street
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$street
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * Set street
     *
     * @pattern ^.{0,95}$
     *
     * @param string|null $street
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example Siriusdreef
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$street
     */
    public function setStreet(?string $street): CalculateDeliveryDate
    {
        $this->street = ValidateAndFix::street($street);

        return $this;
    }

    /**
     * Get cut-off time
     *
     * @return mixed
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$cutOffTime
     */
    public function getCutOffTime()
    {
        return $this->cutOffTime;
    }

    /**
     * Set cut-off time
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @param mixed $cutOffTime
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 15:00:00
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$cutOffTime
     */
    public function setCutOffTime($cutOffTime): CalculateDeliveryDate
    {
        $this->cutOffTime = ValidateAndFix::time($cutOffTime);

        return $this;
    }

    /**
     * Get cut-off time Monday
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$cutOffTimeMonday
     */
    public function getCutOffTimeMonday(): ?string
    {
        return $this->cutOffTimeMonday;
    }

    /**
     * Set cut-off time Monday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @param string|null $cutOffTimeMonday
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 15:00:00
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$cutOffTimeMonday
     */
    public function setCutOffTimeMonday(?string $cutOffTimeMonday): CalculateDeliveryDate
    {
        $this->cutOffTimeMonday = ValidateAndFix::time($cutOffTimeMonday);

        return $this;
    }

    /**
     * Get available Monday
     *
     * @return bool|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$availableMonday
     */
    public function getAvailableMonday(): ?bool
    {
        return $this->availableMonday;
    }

    /**
     * Set available Monday
     *
     * @pattern N/A
     *
     * @param bool|null $availableMonday
     *
     * @return static
     *
     * @throws TypeError
     *
     * @example N/A
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$availableMonday
     */
    public function setAvailableMonday(?bool $availableMonday): CalculateDeliveryDate
    {
        $this->availableMonday = $availableMonday;

        return $this;
    }

    /**
     * Get cut-off time Tuesday
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$cutOffTimeTuesday
     */
    public function getCutOffTimeTuesday(): ?string
    {
        return $this->cutOffTimeTuesday;
    }

    /**
     * Set cut-off time Tuesday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @example 15:00:00
     *
     * @param string|null $cutOffTimeTuesday
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$cutOffTimeTuesday
     */
    public function setCutOffTimeTuesday(?string $cutOffTimeTuesday): CalculateDeliveryDate
    {
        $this->cutOffTimeTuesday = ValidateAndFix::time($cutOffTimeTuesday);

        return $this;
    }

    /**
     * Get available Tuesday
     *
     * @return bool|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$availableTuesday
     */
    public function getAvailableTuesday(): ?bool
    {
        return $this->availableTuesday;
    }

    /**
     * Set available Tuesday
     *
     * @pattern N/A
     *
     * @param bool|null $availableTuesday
     *
     * @return static
     *
     * @throws TypeError
     *
     * @example N/A
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$availableTuesday
     */
    public function setAvailableTuesday(?bool $availableTuesday): CalculateDeliveryDate
    {
        $this->availableTuesday = $availableTuesday;

        return $this;
    }

    /**
     * Get cut-off time Wednesday
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$cutOffTimeWednesday
     */
    public function getCutOffTimeWednesday(): ?string
    {
        return $this->cutOffTimeWednesday;
    }

    /**
     * Set cut-off time Wednesday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @param string|null $cutOffTimeWednesday
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 15:00:00
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$cutOffTimeWednesday
     */
    public function setCutOffTimeWednesday(?string $cutOffTimeWednesday): CalculateDeliveryDate
    {
        $this->cutOffTimeWednesday = ValidateAndFix::time($cutOffTimeWednesday);

        return $this;
    }

    /**
     * Get available Wednesday
     *
     * @return bool|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$availableWednesday
     */
    public function getAvailableWednesday(): ?bool
    {
        return $this->availableWednesday;
    }

    /**
     * Set available Wednesday
     *
     * @pattern N/A
     *
     * @param bool|null $availableWednesday
     *
     * @return static
     *
     * @throws TypeError
     *
     * @example N/A
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$availableWednesday
     */
    public function setAvailableWednesday(?bool $availableWednesday): CalculateDeliveryDate
    {
        $this->availableWednesday = $availableWednesday;

        return $this;
    }

    /**
     * Get cut-off time Thursday
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$cutOffTimeThursday
     */
    public function getCutOffTimeThursday(): ?string
    {
        return $this->cutOffTimeThursday;
    }

    /**
     * Set cut-off time Thursday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @param string|null $cutOffTimeThursday
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 15:00:00
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$cutOffTimeThursday
     */
    public function setCutOffTimeThursday(?string $cutOffTimeThursday): CalculateDeliveryDate
    {
        $this->cutOffTimeThursday = ValidateAndFix::time($cutOffTimeThursday);

        return $this;
    }

    /**
     * Get available Thursday
     *
     * @return bool|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$availableThursday
     */
    public function getAvailableThursday(): ?bool
    {
        return $this->availableThursday;
    }

    /**
     * Set available Thursday
     *
     * @pattern N/A
     *
     * @param bool|null $availableThursday
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example N/A
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$availableThursday
     */
    public function setAvailableThursday(?bool $availableThursday): CalculateDeliveryDate
    {
        $this->availableThursday = ValidateAndFix::time($availableThursday);

        return $this;
    }

    /**
     * Get cut-off time Friday
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$cutOffTimeFriday
     */
    public function getCutOffTimeFriday(): ?string
    {
        return $this->cutOffTimeFriday;
    }

    /**
     * Set cut-off time Friday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @param string|null $cutOffTimeFriday
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 15:00:00
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$cutOffTimeFriday
     */
    public function setCutOffTimeFriday(?string $cutOffTimeFriday): CalculateDeliveryDate
    {
        $this->cutOffTimeFriday = ValidateAndFix::time($cutOffTimeFriday);

        return $this;
    }

    /**
     * Get available Friday
     *
     * @return bool|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$availableFriday
     */
    public function getAvailableFriday(): ?bool
    {
        return $this->availableFriday;
    }

    /**
     * Set available Friday
     *
     * @pattern N/A
     *
     * @param bool|null $availableFriday
     *
     * @return static
     *
     * @throws TypeError
     *
     * @example N/A
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$availableFriday
     */
    public function setAvailableFriday(?bool $availableFriday): CalculateDeliveryDate
    {
        $this->availableFriday = $availableFriday;

        return $this;
    }

    /**
     * Get cut-off time Saturday
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$cutOffTimeSaturday
     */
    public function getCutOffTimeSaturday(): ?string
    {
        return $this->cutOffTimeSaturday;
    }

    /**
     * Set cut-off time Saturday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @param string|null $cutOffTimeSaturday
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 15:00:00
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$cutOffTimeSaturday
     */
    public function setCutOffTimeSaturday(?string $cutOffTimeSaturday): CalculateDeliveryDate
    {
        $this->cutOffTimeSaturday = ValidateAndFix::time($cutOffTimeSaturday);

        return $this;
    }

    /**
     * Get available Saturday
     *
     * @return bool|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$availableSaturday
     */
    public function getAvailableSaturday(): ?bool
    {
        return $this->availableSaturday;
    }

    /**
     * Set available Saturday
     *
     * @pattern N/A
     *
     * @param bool|null $availableSaturday
     *
     * @return static
     *
     * @throws TypeError
     *
     * @example N/A
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$availableSaturday
     */
    public function setAvailableSaturday(?bool $availableSaturday): CalculateDeliveryDate
    {
        $this->availableSaturday = $availableSaturday;

        return $this;
    }

    /**
     * Get cut-off time Sunday
     *
     * @return string|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$cutOffTimeSunday
     */
    public function getCutOffTimeSunday(): ?string
    {
        return $this->cutOffTimeSunday;
    }

    /**
     * Set cut-off time Sunday
     *
     * @pattern ^(?:2[0-3]|[01]?[0-9]):(?:[0-5]?[0-9]):(?:[0-5]?[0-9])$
     *
     * @param string|null $cutOffTimeSunday
     *
     * @return static
     *
     * @throws TypeError
     * @throws ReflectionException
     *
     * @example 15:00:00
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$cutOffTimeSunday
     */
    public function setCutOffTimeSunday(?string $cutOffTimeSunday): CalculateDeliveryDate
    {
        $this->cutOffTimeSunday = ValidateAndFix::time($cutOffTimeSunday);

        return $this;
    }

    /**
     * Get available Sunday
     *
     * @return bool|null
     *
     * @since 2.0.0
     *
     * @see   CalculateDeliveryDate::$availableSunday
     */
    public function getAvailableSunday(): ?bool
    {
        return $this->availableSunday;
    }

    /**
     * Set available Sunday
     *
     * @pattern N/A
     *
     * @param bool|null $availableSunday
     *
     * @return static
     *
     * @throws TypeError
     *
     * @example N/A
     *
     * @since   2.0.0
     *
     * @see     CalculateDeliveryDate::$availableSunday
     */
    public function setAvailableSunday(?bool $availableSunday): CalculateDeliveryDate
    {
        $this->availableSunday = $availableSunday;

        return $this;
    }
}
