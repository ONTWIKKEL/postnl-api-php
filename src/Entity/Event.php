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

namespace Firstred\PostNL\Entity;

use Firstred\PostNL\Entity\Response\CompleteStatusResponseEvent;

/**
 * Class Event
 */
class Event extends AbstractEntity
{
    /** @var CompleteStatusResponseEvent|null $completeStatusResponseEvent */
    protected $completeStatusResponseEvent;

    /**
     * @param CompleteStatusResponseEvent|null $completeStatusResponseEvent
     */
    public function __construct(?CompleteStatusResponseEvent $completeStatusResponseEvent = null)
    {
        parent::__construct();

        $this->setCompleteStatusResponseEvent($completeStatusResponseEvent);
    }

    /**
     * @return CompleteStatusResponseEvent|null
     *
     * @since 2.0.0 Strict typing
     */
    public function getCompleteStatusResponseEvent(): ?CompleteStatusResponseEvent
    {
        return $this->completeStatusResponseEvent;
    }

    /**
     * @param CompleteStatusResponseEvent|null $completeStatusResponseEvent
     *
     * @return static
     *
     * @since 2.0.0 Strict typing
     */
    public function setCompleteStatusResponseEvent(?CompleteStatusResponseEvent $completeStatusResponseEvent): Event
    {
        $this->completeStatusResponseEvent = $completeStatusResponseEvent;

        return $this;
    }
}
