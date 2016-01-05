<?php

namespace Seliton\Client\Resource;

class Order extends Resource {
	protected static $_name = 'order';
	protected static $namePlural = 'orders';
	protected static $fields = array (
		'id',
		'invoiceNumber',
		'invoicePreparedBy',
		'invoiceDate',
		'timestamp',
		'iPAddress',
		'isDirectPayment',
		'status',
		'paymentStatus',
		'languageCode',
		'customerInstructions',
		'customerEmail',
		'customerReferringHash',
		'shippingMethodCode',
		'shippingTrackingNo',
		'shippingNotes',
		'shippingModuleName',
		'customerLanguageShippingModuleName',
		'shippingMethodName',
		'customerLanguageShippingMethodName',
		'paymentModuleName',
		'customerLanguagePaymentModuleName',
		'paymentModuleIntegration',
		'paymentNotes',
		'paymentTransactionNo',
		'checkoutModuleName',
		'customerLanguageCheckoutModuleName',
		'billingFirstName',
		'billingLastName',
		'billingCompany',
		'billingPhone',
		'billingFax',
		'billingAddress1',
		'billingAddress2',
		'billingCity',
		'billingCityID',
		'billingState',
		'billingCustomerLanguageState',
		'billingStateCode',
		'billingCountry',
		'billingCustomerLanguageCountry',
		'billingCountryCode2',
		'billingZip',
		'billingVatNumber',
		'billingTaxNumber',
		'billingTaxOffice',
		'billingPersonalNumber',
		'billingInvoiceContactPerson',
		'billingIsCompany',
		'billingBankName',
		'billingBankAccount',
		'shipToBillingAddress',
		'shippingFirstName',
		'shippingLastName',
		'shippingCompany',
		'shippingPhone',
		'shippingFax',
		'shippingAddress1',
		'shippingAddress2',
		'shippingCity',
		'shippingCityID',
		'shippingState',
		'shippingCustomerLanguageState',
		'shippingStateCode',
		'shippingCountry',
		'shippingCustomerLanguageCountry',
		'shippingCountryCode2',
		'shippingZip',
		'shippingVatNumber',
		'couponCode',
		'total',
		'currencyCode',
		'totalPayAmount',
		'totalPayCurrencyCode',
		'quantitiesInStockReduced',
		'customerCurrencyCode',
		'customerCurrencyTotal',
		'discountFreeShipping',
		'terms',
		'items',
		'totalLines',
	);
	protected static $externalFields = array (
		'customerID',
		'shippingModuleID',
		'paymentModuleID',
		'checkoutModuleID',
	);

	protected function convertField($name, $value)
	{
		if ($name == 'items') {
			$items = array ();
			foreach ($value as $item) {
				$items[] = array (
					'orderItemID' => $item->orderItemID,
					'orderID' => $item->orderID,
					'parentOrderItemID' => $item->parentOrderItemID,
					'productID' => $item->productID,
					'productVariantID' => $item->productVariantID,
					'productImageID' => $item->productImageID,
					'orderItemProductCode' => $item->orderItemProductCode,
					'orderItemProductName' => $item->orderItemProductName,
					'orderItemCustomerLanguageProductName' => $item->orderItemCustomerLanguageProductName,
					'orderItemCategoryName' => $item->orderItemCategoryName,
					'orderItemCustomerLanguageCategoryName' => $item->orderItemCustomerLanguageCategoryName,
					'orderItemBrandName' => $item->orderItemBrandName,
					'orderItemCustomerLanguageBrandName' => $item->orderItemCustomerLanguageBrandName,
					'orderItemQty' => $item->orderItemQty,
					'orderItemDiscount' => $item->orderItemDiscount,
					'orderItemPrice' => $item->orderItemPrice,
					'orderItemDistributorPrice' => $item->orderItemDistributorPrice,
					'orderItemTotal' => $item->orderItemTotal,
					'orderItemTaxesAmount' => $item->orderItemTaxesAmount,
					'orderItemTaxesRate' => $item->orderItemTaxesRate,
					'orderItemCustomerCurrencyDiscount' => $item->orderItemCustomerCurrencyDiscount,
					'orderItemCustomerCurrencyPrice' => $item->orderItemCustomerCurrencyPrice,
					'orderItemCustomerCurrencyTotal' => $item->orderItemCustomerCurrencyTotal,
					'orderItemCustomerCurrencyTaxesAmount' => $item->orderItemCustomerCurrencyTaxesAmount,
					'orderItemBonusPointsSpent' => $item->orderItemBonusPointsSpent,
					'shippingModuleID' => $item->shippingModuleID,
					'orderItemShippingMethodCode' => $item->orderItemShippingMethodCode,
					'orderItemShippingModuleName' => $item->orderItemShippingModuleName,
					'orderItemCustomerLanguageShippingModuleName' => $item->orderItemCustomerLanguageShippingModuleName,
					'orderItemShippingMethodName' => $item->orderItemShippingMethodName,
					'orderItemCustomerLanguageShippingMethodName' => $item->orderItemCustomerLanguageShippingMethodName,
					'vendorID' => $item->vendorID,
					'orderItemVendorName' => $item->orderItemVendorName,
					'vendorOrderID' => $item->vendorOrderID,
					'vendorOrderCreateTimestamp' => $item->vendorOrderCreateTimestamp,
					'orderItemOriginalProductCode' => $item->orderItemOriginalProductCode,
				);
			}
			return $items;
		}
		if ($name == 'totalLines') {
			$totalLines = array ();
			foreach ($value as $totalLine) {
				$totalLines[] = array (
					'orderTotalLineID' => $totalLine->orderTotalLineID,
					'orderID' => $totalLine->orderID,
					'orderTotalLineType' => $totalLine->orderTotalLineType,
					'orderTotalLineName' => $totalLine->orderTotalLineName,
					'orderTotalLineCustomerLanguageName' => $totalLine->orderTotalLineCustomerLanguageName,
					'orderTotalLineAmount' => $totalLine->orderTotalLineAmount,
					'orderTotalLineCustomerCurrencyAmount' => $totalLine->orderTotalLineCustomerCurrencyAmount,
					'orderTotalLineSort' => $totalLine->orderTotalLineSort,

				);
			}
			return $totalLines;
		}
		return parent::convertField($name, $value);
	}
}
