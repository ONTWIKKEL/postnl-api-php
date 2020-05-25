<?php

declare(strict_types=1);

/**
 * The MIT License (MIT).
 *
 * Copyright (c) 2017-2020 Michael Dekker (https://github.com/firstred)
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
 * @copyright 2017-2020 Michael Dekker
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace Firstred\PostNL\Entity;

use Firstred\PostNL\Exception\InvalidArgumentException;

/**
 * Class Geocode.
 */
interface GeocodeInterface extends EntityInterface
{
    /**
     * Get latitude.
     *
     * @return float|null
     *
     * @since 2.0.0
     * @see   Geocode::$latitude
     */
    public function getLatitude(): ?float;

    /**
     * Set latitude.
     *
     * @pattern ^\d{1,2}\.\d{1,15}$
     *
     * @param float|string|null $latitude
     *
     * @return static
     *
     * @throws InvalidArgumentException
     *
     * @example 52.156439
     *
     * @since   2.0.0
     * @see     Geocode::$latitude
     */
    public function setLatitude($latitude): GeocodeInterface;

    /**
     * Get longitude.
     *
     * @return float|null
     *
     * @since 2.0.0
     * @see   Geocode::$longitude
     */
    public function getLongitude(): ?float;

    /**
     * Set longitude.
     *
     * @pattern ^\d{1,2}\.\d{1,15}$
     *
     * @param float|null $longitude
     *
     * @return static
     *
     * @example 52.156439
     *
     * @since   2.0.0
     * @see     Geocode::$longitude
     */
    public function setLongitude(?float $longitude): GeocodeInterface;

    /**
     * Get Rijksdriehoek X-coordinate.
     *
     * @return float|null
     *
     * @since 2.0.0
     * @see   Geocode::$rdxCoordinate
     */
    public function getRdxCoordinate(): ?float;

    /**
     * Set Rijksdriehoek X-coordinate.
     *
     * @pattern ^\d{1,10}\.\d{1,15}$
     *
     * @param float|null $rdxCoordinate
     *
     * @return static
     *
     * @example 199735.12
     *
     * @since   2.0.0
     * @see     Geocode::$rdxCoordinate
     */
    public function setRdxCoordinate(?float $rdxCoordinate): GeocodeInterface;

    /**
     * Get Rijksdriehoek Y-coordinate.
     *
     * @return float|null
     *
     * @since 2.0.0
     * @see   Geocode::$rdyCoordinate
     */
    public function getRdyCoordinate(): ?float;

    /**
     * Set Rijksdriehoek Y-coordinate.
     *
     * @pattern ^\d{1,10}\.\d{1,15}$
     *
     * @param float|null $rdyCoordinate
     *
     * @return static
     *
     * @example 199735.12
     *
     * @since   2.0.0
     * @see     Geocode::$rdyCoordinate
     */
    public function setRdyCoordinate(?float $rdyCoordinate): GeocodeInterface;
}