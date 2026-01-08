<?php

namespace App\Console\Commands;

use App\Models\PaymentGateway;
use Illuminate\Console\Command;

class LinkPaymentGatewayImages extends Command
{
    protected $signature = 'payment-gateway:link-images';
    protected $description = 'Link payment gateway images from seeder folder to media library';

    public function handle()
    {
        $gateways = PaymentGateway::all();
        $linked = 0;

        foreach ($gateways as $gateway) {
            // Check if already has image
            if ($gateway->getFirstMediaUrl('payment-gateway')) {
                $this->info("✓ {$gateway->name} already has image");
                continue;
            }

            // Try to find image in seeder folder
            $imagePath = public_path('/images/seeder/payment-gateway/' . strtolower(str_replace(' ', '', $gateway->slug)) . '.png');

            if (!file_exists($imagePath)) {
                // Try with different naming conventions
                $imagePath = public_path('/images/seeder/payment-gateway/' . $gateway->slug . '.png');
            }

            if (file_exists($imagePath)) {
                $gateway->addMedia($imagePath)
                    ->preservingOriginal()
                    ->toMediaCollection('payment-gateway');
                $this->info("✓ Linked image for {$gateway->name}");
                $linked++;
            } else {
                $this->warn("✗ No image found for {$gateway->name} (tried: {$gateway->slug}.png)");
            }
        }

        $this->newLine();
        $this->info("Done! Linked {$linked} images.");

        return Command::SUCCESS;
    }
}
