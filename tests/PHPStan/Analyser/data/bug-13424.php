<?php

namespace Bug13424;

final class DemoFile
{
	/** @param mixed[] $args */
	public function run( $args ): void {
		if ( ! empty( $args['bar'] ) ) {
			$hello = (object) array(
				'a' => 'b',
			);
		} else {
			$hello = new Hello( $args );
		}
	}
}
