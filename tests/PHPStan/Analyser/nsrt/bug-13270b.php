<?php declare(strict_types=1);

namespace Bug13270b;

use function PHPStan\Testing\assertType;

class Test
{
	/**
	 * @param mixed[] $data
	 * @return mixed[]
	 */
	public function parseData(array $data): array
	{
		if (isset($data['price'])) {
			assertType('mixed~null', $data['price']);
			if (!array_key_exists('priceWithVat', $data['price'])) {
				$data['price']['priceWithVat'] = null;
			}
			assertType("mixed", $data['price']);
			if (!array_key_exists('priceWithoutVat', $data['price'])) {
				$data['price']['priceWithoutVat'] = null;
			}
			assertType('mixed', $data['price']);
		}
		return $data;
	}
}
