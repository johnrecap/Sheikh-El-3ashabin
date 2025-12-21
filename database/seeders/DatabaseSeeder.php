<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CompanyTableSeeder::class);
        $this->call(ThemeTableSeeder::class);
        $this->call(CookiesTableSeeder::class);
        $this->call(SiteTableSeeder::class);
        $this->call(DeliveryZoneTableSeeder::class);
        $this->call(MenuTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(RolePermissionTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(BarcodeTableSeeder::class);
        $this->call(MenuTemplateTableSeeder::class);
        $this->call(BenefitTableSeeder::class);
        $this->call(MenuSectionTableSeeder::class);
        $this->call(PaymentGatewayTableSeeder::class);
        $this->call(SmsGatewayTableSeeder::class);
        $this->call(NotificationAlertTableSeeder::class);
        $this->call(OtpTableSeeder::class);
        $this->call(SocialMediaTableSeeder::class);
        $this->call(PaymentGatewayDataTableSeeder::class);
        $this->call(MailTableSeeder::class);
        $this->call(SliderTableSeeder::class);
        $this->call(ProductBrandTableSeeder::class);
        $this->call(ProductCategoryTableSeeder::class);
        $this->call(TaxTableSeeder::class);
        $this->call(UnitTableSeeder::class);
        $this->call(SupplierTableSeeder::class);
        $this->call(PushNotificationTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(CouponTableSeeder::class);
        $this->call(PromotionTableSeeder::class);
        $this->call(ProductSectionTableSeeder::class);
        $this->call(OutletTableSeeder::class);
        $this->call(PageTableSeeder::class);
        $this->call(WishlistTableSeeder::class);
        $this->call(PurchaseTableSeeder::class);
        $this->call(OrderTableSeeder::class);
        $this->call(PosOrderTableSeeder::class);

        $this->call(DamageTableSeeder::class);
        $this->call(ReturnOrderTableSeeder::class);

        $this->call(CurrencyTableSeeder::class);
        $this->call(SubscriberTableSeeder::class);
        $this->call(NotificationTableSeeder::class);

        $this->call(LicenseTableSeeder::class);
    }
}