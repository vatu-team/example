<?php

/**
 * Test: Suite is running.
 *
 * @package   Vatu\Wordpress\Tests
 * @author    Vatu <hello@vatu.dev>
 * @link      https://vatu.dev/
 * @license   GNU General Public License v3.0
 * @copyright 2022-2024 Vatu Ltd.
 */

declare(strict_types=1);

namespace Vatu\Wordpress\Tests;

class Test extends TestCase
{
	/**
	 * Test Suite is running.
	 *
	 * @coversNothing
	 */
	public function testIsRun(): void
	{
		self::assertTrue( true );
	}
}
