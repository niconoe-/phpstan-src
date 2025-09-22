<?php

class event {
	public static function add($_event, $_option = []) {
		// $_option est optionnel avec valeur par défaut
	}

	public static function callAdd() {
		self::add('a',[]); // Appel avec 2 paramètres (1 requis + 1 optionnel)
	}
}
