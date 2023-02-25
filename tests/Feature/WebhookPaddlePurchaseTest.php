<?php

use App\Jobs\HandlePaddlePurchaseJob;
use Spatie\WebhookClient\Models\WebhookCall;

function getValidPaddleRequestData(): array
{
    return [
        'event_time' => '1970-01-01 00:00:01',
        'p_country' => 'US',
        'p_coupon' => null,
        'p_coupon_savings' => null,
        'p_currency' => 'USD',
        'p_custom_data' => null,
        'p_earnings' => '{"vendor_id":"55.5500"}',
        'p_order_id' => '123456',
        'p_paddle_fee' => '3.45',
        'p_price' => '59',
        'p_product_id' => 'product_id',
        'p_quantity' => '1',
        'p_salge_gross' => '59',
        'p_tax_amount' => '0',
        'p_used_price_override' => '1',
        'passthrough' => 'Example passthrough',
        'quantity' => '1',
        'p_signature' => null,
    ];
}

function getInvalidPaddleRequestData(): array
{
    return [];
}

it('stores a paddle purchase request', function () {
    // Arrange
    Queue::fake();
    $this->assertDatabaseCount(WebhookCall::class, 0);

    // Act
    $this->post('webhooks', getValidPaddleRequestData());

    // Assert
    $this->assertDatabaseCount(WebhookCall::class, 1);
});

it('does not store invalid paddle purchase request', function () {
    // Arrange
    $this->assertDatabaseCount(WebhookCall::class, 0);

    // Act
    $this->post('webhooks', getInvalidPaddleRequestData());

    // Assert
    $this->assertDatabaseCount(WebhookCall::class, 0);
});

it('dispatches a job for valid paddle request', function () {
    // Arrange
    Queue::fake();

    // Act
    $this->post('webhooks', getValidPaddleRequestData());

    // Assert
    Queue::assertPushed(HandlePaddlePurchaseJob::class);

});

it('does not dispatch a job for invalid paddle request', function () {
    // Arrange
    Queue::fake();

    // Act
    $this->post('webhooks', getInvalidPaddleRequestData());

    // Assert
    Queue::assertNothingPushed();
});
