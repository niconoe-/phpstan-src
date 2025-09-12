<?php

namespace Bug13492;

/**
 * @property CustomerService $customers
 */
class StripeClient
{

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		return $this->getService();
	}

	public function getService(): CustomerService
	{
		return new CustomerService();
	}

}

class CustomerService
{

	public function create(): Customer
	{
		return new Customer();
	}

}

/**
 * @property null|(object{address?: (object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject), carrier?: null|string, name?: string, phone?: null|string, tracking_number?: null|string}&StripeObject) $shipping Mailing and shipping address for the customer. Appears on invoices emailed to this customer.
 * @property null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject) $address The customer's address.
 */
class Customer extends StripeObject
{

}

class StripeObject
{
	/** @return mixed */
	public function &__get(string $k)
	{

	}
}

function (): void {
	$stripe = new StripeClient();
	$customer = $stripe->customers->create();
};
