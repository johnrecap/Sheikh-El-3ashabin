<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OtpController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\DamageController;
use App\Http\Controllers\Admin\OutletController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\BarcodeController;
use App\Http\Controllers\Admin\BenefitController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CookiesController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\AnalyticController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PosOrderController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TimezoneController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Auth\DeactivateController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductSeoController;
use App\Http\Controllers\Admin\SimpleUserController;
use App\Http\Controllers\Admin\SmsGatewayController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Admin\ActiveOrderController;
use App\Http\Controllers\Admin\CountryCodeController;
use App\Http\Controllers\Admin\DeliveryBoyController;
use App\Http\Controllers\Admin\MenuSectionController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\OnlineOrderController;
use App\Http\Controllers\Admin\ReturnOrderController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Auth\RefreshTokenController;
use App\Http\Controllers\Frontend\OverviewController;
use App\Http\Controllers\Admin\DeliveryZoneController;
use App\Http\Controllers\Admin\MenuTemplateController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OrderHistoryController;
use App\Http\Controllers\Admin\ProductBrandController;
use App\Http\Controllers\Admin\AdministratorController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\TokenStoreController;
use App\Http\Controllers\Admin\MyOrderDetailsController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\ProductSectionController;
use App\Http\Controllers\Admin\ProductsReportController;
use App\Http\Controllers\Admin\AnalyticSectionController;
use App\Http\Controllers\Admin\CustomerAddressController;
use App\Http\Controllers\Admin\EmployeeAddressController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\DeliveryBoyOrderController;
use App\Http\Controllers\Admin\ProductAttributeController;
use App\Http\Controllers\Admin\ProductVariationController;
use App\Http\Controllers\Admin\PromotionProductController;
use App\Http\Controllers\Admin\PushNotificationController;
use App\Http\Controllers\Admin\NotificationAlertController;
use App\Http\Controllers\Admin\DeliveryBoyAddressController;
use App\Http\Controllers\Admin\CreditBalanceReportController;
use App\Http\Controllers\Admin\AdministratorAddressController;
use App\Http\Controllers\Admin\DeliveryBoyDashboardController;
use App\Http\Controllers\Admin\ProductSectionProductController;
use App\Http\Controllers\Admin\ProductAttributeOptionController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Http\Controllers\Frontend\OrderController as FrontendOrderController;
use App\Http\Controllers\Frontend\CouponController as FrontendCouponController;
use App\Http\Controllers\Frontend\OutletController as FrontendOutletController;
use App\Http\Controllers\Frontend\SliderController as FrontendSliderController;
use App\Http\Controllers\Frontend\AddressController as FrontendAddressController;
use App\Http\Controllers\Frontend\BenefitController as FrontendBenefitController;
use App\Http\Controllers\Frontend\CookiesController as FrontendCookiesController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\SettingController as FrontendSettingController;
use App\Http\Controllers\Frontend\LanguageController as FrontendLanguageController;
use App\Http\Controllers\Frontend\WishlistController as FrontendWishlistController;
use App\Http\Controllers\Frontend\PromotionController as FrontendPromotionController;
use App\Http\Controllers\Frontend\SubscriberController as FrontendSubscriberController;
use App\Http\Controllers\Frontend\CountryCodeController as FrontendCountryCodeController;
use App\Http\Controllers\Frontend\DeliveryZoneController as FrontendDeliveryZoneController;
use App\Http\Controllers\Frontend\ProductBrandController as FrontendProductBrandController;
use App\Http\Controllers\Frontend\PaymentGatewayController as FrontendPaymentGatewayController;
use App\Http\Controllers\Frontend\ProductSectionController as FrontendProductSectionController;
use App\Http\Controllers\Frontend\ProductCategoryController as FrontendProductCategoryController;
use App\Http\Controllers\Frontend\ProductVariationController as FrontendProductVariationController;
use App\Http\Controllers\Frontend\PromotionProductController as FrontendPromotionProductController;
use App\Http\Controllers\Frontend\ProductSectionProductController as FrontendProductSectionProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::match(['get', 'post'], '/login', function () {
    return response()->json(['errors' => 'unauthenticated'], 401);
})->name('login');

Route::match(['get', 'post'], '/refresh-token', [RefreshTokenController::class, 'refreshToken'])->middleware(['installed']);

Route::prefix('auth')->middleware(['installed', 'apiKey', 'localization'])->name('auth.')->namespace('Auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);

    Route::prefix('forgot-password')->name('forgot-password.')->group(function () {
        Route::post('/', [ForgotPasswordController::class, 'forgotPassword']);
        Route::post('/otp-phone', [ForgotPasswordController::class, 'otpPhone']);
        Route::post('/otp-email', [ForgotPasswordController::class, 'otpEmail']);
        Route::post('/verify-phone', [ForgotPasswordController::class, 'verifyPhone']);
        Route::post('/verify-email', [ForgotPasswordController::class, 'verifyEmail']);
        Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
    });

    Route::prefix('signup')->name('signup.')->group(function () {
        Route::post('/otp-phone', [SignupController::class, 'otpPhone']);
        Route::post('/otp-email', [SignupController::class, 'otpEmail']);
        Route::post('/verify-phone', [SignupController::class, 'verifyPhone']);
        Route::post('/verify-email', [SignupController::class, 'verifyEmail']);
        Route::post('/register', [SignupController::class, 'register']);
        Route::post('/login-verify', [SignupController::class, 'signupLoginVerify']);
        Route::post('/register-validation', [SignupController::class, 'validateRegister']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::post('/delete-account', [DeactivateController::class, 'deleteAccount']);
    });

    Route::post('/authcheck', function () {
        if (Auth::check()) {
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    });
});

/* all routes must be singular word*/
Route::prefix('profile')->name('profile.')->middleware(['installed', 'apiKey', 'auth:sanctum', 'localization'])->group(function () {
    Route::get('/', [ProfileController::class, 'profile']);
    Route::match(['post', 'put', 'patch'], '/', [ProfileController::class, 'update']);
    Route::match(['put', 'patch'], '/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/change-image', [ProfileController::class, 'changeImage']);
});

Route::prefix('admin')->name('admin.')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('timezone')->name('timezone.')->group(function () {
        Route::get('/', [TimezoneController::class, 'index']);
    });

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/total-sales', [DashboardController::class, 'totalSales']);
        Route::get('/total-orders', [DashboardController::class, 'totalOrders']);
        Route::get('/total-customers', [DashboardController::class, 'totalCustomers']);
        Route::get('/total-products', [DashboardController::class, 'totalProducts']);
        Route::get('/order-statistics', [DashboardController::class, 'orderStatistics']);
        Route::get('/sales-summary', [DashboardController::class, 'salesSummary']);
        Route::get('/order-summary', [DashboardController::class, 'orderSummary']);
        Route::get('/customer-states', [DashboardController::class, 'customerStates']);
        Route::get('/top-customers', [DashboardController::class, 'topCustomers']);
        Route::get('/top-products', [DashboardController::class, 'topProducts']);
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::prefix('system-backup')->name('backup.')->group(function () {
            Route::get('/', [BackupController::class, 'index']);
            Route::post('/', [BackupController::class, 'store']);
            Route::post('/restore', [BackupController::class, 'restore']);
            Route::get('/download/{filename}', [BackupController::class, 'download']);
            Route::delete('/{filename}', [BackupController::class, 'destroy']);
        });

        Route::prefix('company')->name('company.')->group(function () {
            Route::get('/', [CompanyController::class, 'index']);
            Route::match(['put', 'patch'], '/', [CompanyController::class, 'update']);
        });

        Route::prefix('site')->name('site.')->group(function () {
            Route::get('/', [SiteController::class, 'index']);
            Route::match(['put', 'patch'], '/', [SiteController::class, 'update']);
        });

        Route::prefix('delivery-zone')->name('delivery-zone.')->group(function () {
            Route::get('/', [DeliveryZoneController::class, 'index']);
            Route::get('/show/{deliveryZone}', [DeliveryZoneController::class, 'show']);
            Route::post('/', [DeliveryZoneController::class, 'store']);
            Route::match(['put', 'patch'], '/{deliveryZone}', [DeliveryZoneController::class, 'update']);
            Route::delete('/{deliveryZone}', [DeliveryZoneController::class, 'destroy']);
        });

        Route::prefix('theme')->name('theme.')->group(function () {
            Route::get('/', [ThemeController::class, 'index']);
            Route::post('/', [ThemeController::class, 'update']);
        });

        Route::prefix('analytic')->name('analytic.')->group(function () {
            Route::get('/', [AnalyticController::class, 'index']);
            Route::get('/show/{analytic}', [AnalyticController::class, 'show']);
            Route::post('/', [AnalyticController::class, 'store']);
            Route::match(['put', 'patch'], '/{analytic}', [AnalyticController::class, 'update']);
            Route::delete('/{analytic}', [AnalyticController::class, 'destroy']);
        });

        Route::prefix('analytic-section')->name('analytic-section.')->group(function () {
            Route::get('/{analytic}', [AnalyticSectionController::class, 'index']);
            Route::post('/{analytic}', [AnalyticSectionController::class, 'store']);
            Route::match(
                ['put', 'patch'],
                '/{analytic}/{analyticSection}',
                [AnalyticSectionController::class, 'update']
            );
            Route::delete('/{analytic}/{analyticSection}', [AnalyticSectionController::class, 'destroy']);
        });

        Route::prefix('mail')->name('mail.')->group(function () {
            Route::get('/', [MailController::class, 'index']);
            Route::match(['put', 'patch'], '/', [MailController::class, 'update']);
        });

        Route::prefix('currency')->name('currency.')->group(function () {
            Route::get('/', [CurrencyController::class, 'index']);
            Route::get('/show/{currency}', [CurrencyController::class, 'show']);
            Route::post('/', [CurrencyController::class, 'store']);
            Route::match(['put', 'patch'], '/{currency}', [CurrencyController::class, 'update']);
            Route::delete('/{currency}', [CurrencyController::class, 'destroy']);
        });

        Route::prefix('tax')->name('tax.')->group(function () {
            Route::get('/', [TaxController::class, 'index']);
            Route::get('/show/{tax}', [TaxController::class, 'show']);
            Route::post('/', [TaxController::class, 'store']);
            Route::match(['put', 'patch'], '/{tax}', [TaxController::class, 'update']);
            Route::delete('/{tax}', [TaxController::class, 'destroy']);
        });

        Route::prefix('product-category')->name('product-category.')->group(function () {
            Route::get('/', [ProductCategoryController::class, 'index']);
            Route::get('/depth-tree', [ProductCategoryController::class, 'depthTree']);
            Route::get('/show/{productCategory}', [ProductCategoryController::class, 'show']);
            Route::post('/', [ProductCategoryController::class, 'store']);
            Route::match(['post', 'put', 'patch'], '/{productCategory}', [ProductCategoryController::class, 'update']);
            Route::delete('/{productCategory}', [ProductCategoryController::class, 'destroy']);
            Route::get('/ancestors-and-self/{productCategory:slug}', [ProductCategoryController::class, 'ancestorsAndSelf']);
            Route::get('/tree', [ProductCategoryController::class, 'tree']);
        });

        Route::prefix('product-brand')->name('product-brand.')->group(function () {
            Route::get('/', [ProductBrandController::class, 'index']);
            Route::get('/show/{productBrand}', [ProductBrandController::class, 'show']);
            Route::post('/', [ProductBrandController::class, 'store']);
            Route::match(['post', 'put', 'patch'], '/{productBrand}', [ProductBrandController::class, 'update']);
            Route::delete('/{productBrand}', [ProductBrandController::class, 'destroy']);
        });

        Route::prefix('supplier')->name('supplier.')->group(function () {
            Route::get('/', [SupplierController::class, 'index']);
            Route::get('/show/{supplier}', [SupplierController::class, 'show']);
            Route::post('/', [SupplierController::class, 'store']);
            Route::match(['post', 'put',  'patch'], '/{supplier}', [SupplierController::class, 'update']);
            Route::delete('/{supplier}', [SupplierController::class, 'destroy']);
        });

        Route::prefix('otp')->name('otp.')->group(function () {
            Route::get('/', [OtpController::class, 'index']);
            Route::match(['put', 'patch'], '/', [OtpController::class, 'update']);
        });

        Route::prefix('social-media')->name('social-media.')->group(function () {
            Route::get('/', [SocialMediaController::class, 'index']);
            Route::match(['put', 'patch'], '/', [SocialMediaController::class, 'update']);
        });

        Route::prefix('sms-gateway')->name('sms-gateway.')->group(function () {
            Route::get('/', [SmsGatewayController::class, 'index']);
            Route::match(['put', 'patch'], '/', [SmsGatewayController::class, 'update']);
        });

        Route::prefix('slider')->name('slider.')->group(function () {
            Route::get('/', [SliderController::class, 'index']);
            Route::get('/show/{slider}', [SliderController::class, 'show']);
            Route::post('/', [SliderController::class, 'store']);
            Route::match(['post', 'put', 'patch'], '/{slider}', [SliderController::class, 'update']);
            Route::delete('/{slider}', [SliderController::class, 'destroy']);
        });

        Route::prefix('language')->name('language.')->group(function () {
            Route::get('/', [LanguageController::class, 'index']);
            Route::post('/', [LanguageController::class, 'store']);
            Route::get('/show/{language}', [LanguageController::class, 'show']);
            Route::match(['post', 'put', 'patch'], '/update/{language}', [LanguageController::class, 'update']);
            Route::delete('/{language}', [LanguageController::class, 'destroy']);

            Route::get('/file-list/{language:code}', [LanguageController::class, 'fileList']);
            Route::post('/file-text', [LanguageController::class, 'fileText']);
            Route::post('/file-text/store', [LanguageController::class, 'fileTextStore']);
        });

        Route::prefix('cookies')->name('cookies.')->group(function () {
            Route::get('/', [CookiesController::class, 'index']);
            Route::match(['put', 'patch'], '/', [CookiesController::class, 'update']);
        });

        Route::prefix('page')->name('page.')->group(function () {
            Route::get('/', [PageController::class, 'index']);
            Route::get('/show/{page}', [PageController::class, 'show']);
            Route::post('/', [PageController::class, 'store']);
            Route::match(['post', 'put', 'patch'], '/{page}', [PageController::class, 'update']);
            Route::delete('/{page}', [PageController::class, 'destroy']);
        });

        Route::prefix('license')->name('license.')->group(function () {
            Route::get('/', [LicenseController::class, 'index']);
            Route::match(['put', 'patch'], '/', [LicenseController::class, 'update']);
        });

        Route::prefix('menu-section')->name('menu-section.')->group(function () {
            Route::get('/', [MenuSectionController::class, 'index']);
        });

        Route::prefix('menu-template')->name('menu-template.')->group(function () {
            Route::get('/', [MenuTemplateController::class, 'index']);
            Route::get('/show/{menuTemplate}', [MenuTemplateController::class, 'show']);
            Route::post('/', [MenuTemplateController::class, 'store']);
            Route::match(['put', 'patch'], '/{menuTemplate}', [MenuTemplateController::class, 'update']);
            Route::delete('/{menuTemplate}', [MenuTemplateController::class, 'destroy']);
        });

        Route::prefix('product-attribute')->name('product-attribute.')->group(function () {
            Route::get('/', [ProductAttributeController::class, 'index']);
            Route::get('/show/{productAttribute}', [ProductAttributeController::class, 'show']);
            Route::post('/', [ProductAttributeController::class, 'store']);
            Route::match(['put', 'patch'], '/{productAttribute}', [ProductAttributeController::class, 'update']);
            Route::delete('/{productAttribute}', [ProductAttributeController::class, 'destroy']);
        });

        Route::prefix('product-attribute-option')->name('product-attribute-option.')->group(function () {
            Route::get('/{productAttribute}', [ProductAttributeOptionController::class, 'index']);
            Route::get('/{productAttribute}/show/{productAttributeOption}', [ProductAttributeOptionController::class, 'show']);
            Route::post('/{productAttribute}', [ProductAttributeOptionController::class, 'store']);
            Route::match(['put', 'patch'], '/{productAttribute}/{productAttributeOption}', [ProductAttributeOptionController::class, 'update']);
            Route::delete('/{productAttribute}/{productAttributeOption}', [ProductAttributeOptionController::class, 'destroy']);
        });

        Route::prefix('unit')->name('unit.')->group(function () {
            Route::get('/', [UnitController::class, 'index']);
            Route::get('/show/{unit}', [UnitController::class, 'show']);
            Route::post('/', [UnitController::class, 'store']);
            Route::match(['put', 'patch'], '/{unit}', [UnitController::class, 'update']);
            Route::delete('/{unit}', [UnitController::class, 'destroy']);
        });

        Route::prefix('barcode')->name('barcode.')->group(function () {
            Route::get('/', [BarcodeController::class, 'index']);
        });

        Route::prefix('payment-gateway')->name('payment-gateway.')->group(function () {
            Route::get('/', [PaymentGatewayController::class, 'index']);
            Route::match(['put', 'patch'], '/', [PaymentGatewayController::class, 'update']);
        });

        Route::prefix('notification')->name('notification.')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::post('/', [NotificationController::class, 'update']);
        });

        Route::prefix('role')->name('role.')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/show/{role}', [RoleController::class, 'show']);
            Route::match(['put', 'patch'], '/{role}', [RoleController::class, 'update']);
            Route::delete('/{role}', [RoleController::class, 'destroy']);
        });

        Route::prefix('permission')->name('permission.')->group(function () {
            Route::get('/{role}', [PermissionController::class, 'index']);
            Route::match(['put', 'patch'], '/{role}', [PermissionController::class, 'update']);
        });

        Route::prefix('benefit')->name('benefit.')->group(function () {
            Route::get('/', [BenefitController::class, 'index']);
            Route::get('/show/{benefit}', [BenefitController::class, 'show']);
            Route::post('/', [BenefitController::class, 'store']);
            Route::match(['post', 'put', 'patch'], '/{benefit}', [BenefitController::class, 'update']);
            Route::delete('/{benefit}', [BenefitController::class, 'destroy']);
        });

        Route::prefix('notification-alert')->name('notification-alert.')->group(function () {
            Route::get('/', [NotificationAlertController::class, 'index']);
            Route::match(['put', 'patch'], '/', [NotificationAlertController::class, 'update']);
        });

        Route::prefix('outlet')->name('outlet.')->group(function () {
            Route::get('/', [OutletController::class, 'index']);
            Route::get('/show/{outlet}', [OutletController::class, 'show']);
            Route::post('/', [OutletController::class, 'store']);
            Route::match(['put', 'patch'], '/{outlet}', [OutletController::class, 'update']);
            Route::delete('/{outlet}', [OutletController::class, 'destroy']);
        });
    });

    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/show/{product}', [ProductController::class, 'show']);
        Route::get('/pos-product/{product}', [ProductController::class, 'posProduct']);
        Route::post('/', [ProductController::class, 'store']);
        Route::match(['post', 'put', 'patch'], '/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
        Route::post('/upload-image/{product}', [ProductController::class, 'uploadImage']);
        Route::get('/delete-image/{product}/{index}', [ProductController::class, 'deleteImage']);
        Route::get('/export', [ProductController::class, 'export']);
        Route::get('/generate-sku', [ProductController::class, 'generateSku']);
        Route::post('/offer/{product}', [ProductController::class, 'productOffer']);
        Route::get('/purchasable-product', [ProductController::class, 'purchasableProducts']);
        Route::get('/simple-product', [ProductController::class, 'simpleProducts']);
        Route::get('/download-barcode/{product}', [ProductController::class, 'downloadBarcode']);
        Route::get('/barcode-product/{barcode}', [ProductController::class, 'barcodeProduct']);

        Route::prefix('variation')->name('variation.')->group(function () {
            Route::get('/{product}', [ProductVariationController::class, 'index']);
            Route::get('/{product}/tree', [ProductVariationController::class, 'tree']);
            Route::get('/{product}/single-tree', [ProductVariationController::class, 'singleTree']);
            Route::get('/{product}/tree-with-selected', [ProductVariationController::class, 'treeWithSelected']);
            Route::post('/{product}/store', [ProductVariationController::class, 'store']);
            Route::match(['post', 'put', 'patch'], '/{product}/update/{productVariation}', [ProductVariationController::class, 'update']);
            Route::delete('/{product}/destroy/{productVariation}', [ProductVariationController::class, 'destroy']);
            Route::get('/{product}/show/{productVariation}', [ProductVariationController::class, 'show']);
            Route::get('/ancestors-and-self/{productVariation}', [ProductVariationController::class, 'ancestorsToString']);
            Route::get('/barcode-variation-product/{productVariation}', [ProductVariationController::class, 'barcodeVariationProduct']);
            Route::get('/download-barcode/{productVariation}', [ProductVariationController::class, 'downloadBarcode']);
        });

        Route::get('/initial-variation/{product}', [ProductVariationController::class, 'initialVariation']);
        Route::get('/children-variation/{productVariation}', [ProductVariationController::class, 'childrenVariation']);
        Route::get('/ancestors-and-self-id/{productVariation}', [ProductVariationController::class, 'ancestorsAndSelfId']);

        Route::prefix('seo')->name('seo.')->group(function () {
            Route::get('/{product}', [ProductSeoController::class, 'index']);
            Route::match(['post', 'put', 'patch'], '/{product}/update', [ProductSeoController::class, 'update']);
        });
    });

    Route::prefix('country-code')->name('country-code.')->group(function () {
        Route::get('/', [CountryCodeController::class, 'index']);
        Route::get('/show/{country}', [CountryCodeController::class, 'show']);
        Route::get('/calling-code/{callingCode}', [CountryCodeController::class, 'callingCode']);
    });

    Route::prefix('administrator')->name('administrator.')->group(function () {
        Route::get('/', [AdministratorController::class, 'index']);
        Route::get('/show/{administrator}', [AdministratorController::class, 'show']);
        Route::post('/', [AdministratorController::class, 'store']);
        Route::match(['post', 'put', 'patch'], '/{administrator}', [AdministratorController::class, 'update']);
        Route::delete('/{administrator}', [AdministratorController::class, 'destroy']);
        Route::get('/export', [AdministratorController::class, 'export']);
        Route::post('/change-password/{administrator}', [AdministratorController::class, 'changePassword']);
        Route::post('/change-image/{administrator}', [AdministratorController::class, 'changeImage']);
        Route::get('/my-order/{administrator}', [AdministratorController::class, 'myOrder']);
        Route::get('/address/{administrator}', [AdministratorAddressController::class, 'index']);
        Route::get('/address/show/{administrator}/{address}', [AdministratorAddressController::class, 'show']);
        Route::post('/address/{administrator}', [AdministratorAddressController::class, 'store']);
        Route::match(['put', 'patch'], '/address/{administrator}/{address}', [AdministratorAddressController::class, 'update']);
        Route::delete('/address/{administrator}/{address}', [AdministratorAddressController::class, 'destroy']);
    });

    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/show/{customer}', [CustomerController::class, 'show']);
        Route::match(['post', 'put', 'patch'], '/{customer}', [CustomerController::class, 'update']);
        Route::delete('/{customer}', [CustomerController::class, 'destroy']);
        Route::get('/export', [CustomerController::class, 'export']);
        Route::post('/change-password/{customer}', [CustomerController::class, 'changePassword']);
        Route::post('/change-image/{customer}', [CustomerController::class, 'changeImage']);
        Route::get('/my-order/{customer}', [CustomerController::class, 'myOrder']);
        Route::get('/address/{customer}', [CustomerAddressController::class, 'index']);
        Route::get('/address/show/{customer}/{address}', [CustomerAddressController::class, 'show']);
        Route::post('/address/{customer}', [CustomerAddressController::class, 'store']);
        Route::match(['put', 'patch'], '/address/{customer}/{address}', [CustomerAddressController::class, 'update']);
        Route::delete('/address/{customer}/{address}', [CustomerAddressController::class, 'destroy']);
    });

    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index']);
        Route::post('/', [EmployeeController::class, 'store']);
        Route::get('/show/{employee}', [EmployeeController::class, 'show']);
        Route::match(['put', 'patch'], '/{employee}', [EmployeeController::class, 'update']);
        Route::delete('/{employee}', [EmployeeController::class, 'destroy']);
        Route::get('/export', [EmployeeController::class, 'export']);
        Route::post('/change-password/{employee}', [EmployeeController::class, 'changePassword']);
        Route::post('/change-image/{employee}', [EmployeeController::class, 'changeImage']);
        Route::get('/my-order/{employee}', [EmployeeController::class, 'myOrder']);
        Route::get('/address/{employee}', [EmployeeAddressController::class, 'index']);
        Route::get('/address/show/{employee}/{address}', [EmployeeAddressController::class, 'show']);
        Route::post('/address/{employee}', [EmployeeAddressController::class, 'store']);
        Route::match(['put', 'patch'], '/address/{employee}/{address}', [EmployeeAddressController::class, 'update']);
        Route::delete('/address/{employee}/{address}', [EmployeeAddressController::class, 'destroy']);
    });


    Route::prefix('delivery-boy')->name('delivery-boy.')->group(function () {
        Route::get('/', [DeliveryBoyController::class, 'index']);
        Route::post('/', [DeliveryBoyController::class, 'store']);
        Route::get('/show/{deliveryBoy}', [DeliveryBoyController::class, 'show']);
        Route::match(['put', 'patch'], '/{deliveryBoy}', [DeliveryBoyController::class, 'update']);
        Route::delete('/{deliveryBoy}', [DeliveryBoyController::class, 'destroy']);
        Route::get('/export', [DeliveryBoyController::class, 'export']);

        Route::post('/change-password/{deliveryBoy}', [DeliveryBoyController::class, 'changePassword']);
        Route::post('/change-image/{deliveryBoy}', [DeliveryBoyController::class, 'changeImage']);

        Route::get('/my-order/{deliveryBoy}', [DeliveryBoyController::class, 'myOrder']);
        Route::get('/delivered-order/{deliveryBoy}', [DeliveryBoyOrderController::class, 'deliveredOrder']);
        Route::get('/delivered-order/show/{deliveryBoy}/{order}', [DeliveryBoyOrderController::class, 'deliveredOrderDetails']);
        Route::get('/order-summary', [DeliveryBoyOrderController::class, 'orderSummary']);

        Route::get('/address/{deliveryBoy}', [DeliveryBoyAddressController::class, 'index']);
        Route::get('/address/show/{deliveryBoy}/{address}', [DeliveryBoyAddressController::class, 'show']);
        Route::post('/address/{deliveryBoy}', [DeliveryBoyAddressController::class, 'store']);
        Route::match(['put', 'patch'], '/address/{deliveryBoy}/{address}', [DeliveryBoyAddressController::class, 'update']);
        Route::delete('/address/{deliveryBoy}/{address}', [DeliveryBoyAddressController::class, 'destroy']);

        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/order-overview', [DeliveryBoyDashboardController::class, 'orderOverview']);
            Route::get('/order-statistics', [DeliveryBoyDashboardController::class, 'orderStatistics']);
            Route::get('/order-summary', [DeliveryBoyDashboardController::class, 'orderSummary']);

            Route::prefix('active-order')->name('active-order.')->group(function () {
                Route::get('/', [DeliveryBoyDashboardController::class, 'activeOrder']);
                Route::get('/show/{order}', [DeliveryBoyDashboardController::class, 'activeOrderShow']);
                Route::post('/change-status/{order}', [DeliveryBoyDashboardController::class, 'changeStatus']);
                Route::get('/next-delivery-order', [DeliveryBoyDashboardController::class, 'nextDeliveryOrder']);
            });
        });

        Route::prefix('order-history')->name('order-history.')->group(function () {
            Route::get('/', [OrderHistoryController::class, 'index']);
            Route::get('/show/{order}', [OrderHistoryController::class, 'show']);
            Route::get('/export', [OrderHistoryController::class, 'export']);
        });

        Route::prefix('active-order')->name('active-order.')->group(function () {
            Route::get('/', [ActiveOrderController::class, 'index']);
            Route::get('/show/{order}', [ActiveOrderController::class, 'show']);
            Route::post('/change-status/{order}', [ActiveOrderController::class, 'changeStatus']);
            Route::get('/export', [ActiveOrderController::class, 'export']);
        });
    });


    Route::prefix('my-order')->name('my-order.')->group(function () {
        Route::get('/show/{user}/{order}', [MyOrderDetailsController::class, 'orderDetails']);
    });

    Route::prefix('promotion')->name('promotion.')->group(function () {
        Route::get('/', [PromotionController::class, 'index']);
        Route::get('/show/{promotion}', [PromotionController::class, 'show']);
        Route::post('/', [PromotionController::class, 'store']);
        Route::match(['post', 'put', 'patch'], '/{promotion}', [PromotionController::class, 'update']);
        Route::delete('/{promotion}', [PromotionController::class, 'destroy']);
        Route::get('/export', [PromotionController::class, 'export']);
        Route::post('/change-image/{promotion}', [PromotionController::class, 'changeImage']);

        Route::get('/product/{promotion}', [PromotionProductController::class, 'index']);
        Route::post('/product/{promotion}', [PromotionProductController::class, 'store']);
        Route::delete('/product/{promotion}/{promotionProduct}', [PromotionProductController::class, 'destroy']);
    });

    Route::prefix('product-section')->name('product-section.')->group(function () {
        Route::get('/', [ProductSectionController::class, 'index']);
        Route::get('/show/{productSection}', [ProductSectionController::class, 'show']);
        Route::post('/', [ProductSectionController::class, 'store']);
        Route::match(['post', 'put', 'patch'], '/{productSection}', [ProductSectionController::class, 'update']);
        Route::delete('/{productSection}', [ProductSectionController::class, 'destroy']);
        Route::get('/export', [ProductSectionController::class, 'export']);

        Route::get('/product/{productSection}', [ProductSectionProductController::class, 'index']);
        Route::post('/product/{productSection}', [ProductSectionProductController::class, 'store']);
        Route::delete('/product/{productSection}/{productSectionProduct}', [ProductSectionProductController::class, 'destroy']);
    });

    Route::prefix('transaction')->name('transaction.')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::get('/export', [TransactionController::class, 'export']);
    });

    Route::prefix('sales-report')->name('sales-report.')->group(function () {
        Route::get('/', [SalesReportController::class, 'index']);
        Route::get('/export', [SalesReportController::class, 'export']);
    });

    Route::prefix('credit-balance-report')->name('credit-balance-report.')->group(function () {
        Route::get('/', [CreditBalanceReportController::class, 'index']);
        Route::get('/export', [CreditBalanceReportController::class, 'export']);
    });

    Route::prefix('push-notification')->name('push-notification.')->group(function () {
        Route::get('/', [PushNotificationController::class, 'index']);
        Route::post('/', [PushNotificationController::class, 'store']);
        Route::get('/show/{pushNotification}', [PushNotificationController::class, 'show']);
        Route::delete('/{pushNotification}', [PushNotificationController::class, 'destroy']);
        Route::get('/export', [PushNotificationController::class, 'export']);
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [SimpleUserController::class, 'index']);
    });
    Route::prefix('coupon')->name('coupon.')->group(function () {
        Route::get('/', [CouponController::class, 'index']);
        Route::get('/show/{coupon}', [CouponController::class, 'show']);
        Route::post('/', [CouponController::class, 'store']);
        Route::match(['post', 'put', 'patch'], '/{coupon}', [CouponController::class, 'update']);
        Route::delete('/{coupon}', [CouponController::class, 'destroy']);
        Route::get('/export', [CouponController::class, 'export']);
    });

    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::get('/', [PurchaseController::class, 'index']);
        Route::post('/', [PurchaseController::class, 'store']);
        Route::get('/show/{purchase}', [PurchaseController::class, 'show']);
        Route::get('/edit/{purchase}', [PurchaseController::class, 'edit']);
        Route::match(['post', 'put', 'patch'], '/update/{purchase}', [PurchaseController::class, 'update']);
        Route::delete('/{purchase}', [PurchaseController::class, 'destroy']);
        Route::get('/export', [PurchaseController::class, 'export']);
        Route::get('/download-attachment/{purchase}', [PurchaseController::class, 'downloadAttachment']);
        Route::get('/payment/{purchase}', [PurchaseController::class, 'paymentHistory']);
        Route::post('/payment/{purchase}', [PurchaseController::class, 'payment']);
        Route::get('/payment/download-attachment/{purchasePayment}', [PurchaseController::class, 'paymentDownloadAttachment']);
        Route::delete('/payment/{purchase}/{purchasePayment}', [PurchaseController::class, 'paymentDestroy']);
    });

    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index']);
        Route::get('/export', [StockController::class, 'export']);
    });

    Route::prefix('return-order')->name('return-order.')->group(function () {
        Route::get('/', [ReturnOrderController::class, 'index']);
        Route::post('/', [ReturnOrderController::class, 'store']);
        Route::get('/show/{returnOrder}', [ReturnOrderController::class, 'show']);
        Route::get('/edit/{returnOrder}', [ReturnOrderController::class, 'edit']);
        Route::match(['post', 'put', 'patch'], '/update/{returnOrder}', [ReturnOrderController::class, 'update']);
        Route::delete('/{returnOrder}', [ReturnOrderController::class, 'destroy']);
        Route::get('/export', [ReturnOrderController::class, 'export']);
        Route::get('/download-attachment/{returnOrder}', [ReturnOrderController::class, 'downloadAttachment']);
    });

    Route::prefix('damage')->name('damage.')->group(function () {
        Route::get('/', [DamageController::class, 'index']);
        Route::post('/', [DamageController::class, 'store']);
        Route::get('/show/{damage}', [DamageController::class, 'show']);
        Route::get('/edit/{damage}', [DamageController::class, 'edit']);
        Route::match(['post', 'put', 'patch'], '/update/{damage}', [DamageController::class, 'update']);
        Route::delete('/{damage}', [DamageController::class, 'destroy']);
        Route::get('/export', [DamageController::class, 'export']);
        Route::get('/download-attachment/{damage}', [DamageController::class, 'downloadAttachment']);
    });

    Route::prefix('online-order')->name('onlineOrder.')->group(function () {
        Route::get('/', [OnlineOrderController::class, 'index']);
        Route::get('/show/{order}', [OnlineOrderController::class, 'show']);
        Route::delete('/{order}', [OnlineOrderController::class, 'destroy']);
        Route::get('/export', [OnlineOrderController::class, 'export']);
        Route::post('/change-status/{order}', [OnlineOrderController::class, 'changeStatus']);
        Route::post('/change-payment-status/{order}', [OnlineOrderController::class, 'changePaymentStatus']);
        Route::post('/select-delivery-boy/{order}', [OnlineOrderController::class, 'selectDeliveryBoy']);
        Route::post('/update-product-quantity/{order}', [OnlineOrderController::class, 'updateProductQuantity']);
        Route::post('/delete-product/{order}', [OnlineOrderController::class, 'deleteProduct']);
    });

    Route::prefix('products-report')->name('products-report.')->group(function () {
        Route::get('/', [ProductsReportController::class, 'index']);
        Route::get('/export', [ProductsReportController::class, 'export']);
    });

    Route::prefix('pos-order')->name('posOrder.')->group(function () {
        Route::get('/', [PosOrderController::class, 'index']);
        Route::get('show/{order}', [PosOrderController::class, 'show']);
        Route::delete('/{order}', [PosOrderController::class, 'destroy']);
        Route::get('/export', [PosOrderController::class, 'export']);
        Route::post('/change-status/{order}', [PosOrderController::class, 'changeStatus']);
        Route::post('/change-payment-status/{order}', [PosOrderController::class, 'changePaymentStatus']);
    });

    Route::prefix('pos')->name('pos.')->group(function () {
        Route::post('/', [PosController::class, 'store']);
    });

    Route::prefix('subscriber')->name('subscriber.')->group(function () {
        Route::get('/', [SubscriberController::class, 'index']);
        Route::delete('/{subscriber}', [SubscriberController::class, 'destroy']);
        Route::get('/export', [SubscriberController::class, 'export']);
        Route::post('/send-email', [SubscriberController::class, 'sendEmail']);
    });
});

Route::prefix('frontend')->name('frontend.')->middleware(['installed', 'apiKey', 'localization'])->group(function () {
    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [FrontendSettingController::class, 'index']);
    });

    Route::prefix('country-code')->name('country-code.')->group(function () {
        Route::get('/', [FrontendCountryCodeController::class, 'index']);
        Route::get('/show/{country}', [FrontendCountryCodeController::class, 'show']);
        Route::get('/calling-code/{callingCode}', [FrontendCountryCodeController::class, 'callingCode']);
    });

    Route::prefix('address')->name('address.')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [FrontendAddressController::class, 'index']);
        Route::get('/show/{address}', [FrontendAddressController::class, 'show']);
        Route::post('/', [FrontendAddressController::class, 'store']);
        Route::match(['put', 'patch'], '/{address}', [FrontendAddressController::class, 'update']);
        Route::delete('/{address}', [FrontendAddressController::class, 'destroy']);
    });

    Route::prefix('language')->name('language.')->group(function () {
        Route::get('/', [FrontendLanguageController::class, 'index']);
        Route::get('/show/{language}', [FrontendLanguageController::class, 'show']);
    });

    Route::prefix('slider')->name('slider.')->group(function () {
        Route::get('/', [FrontendSliderController::class, 'index']);
    });

    Route::prefix('product-category')->name('product-category.')->group(function () {
        Route::get('/', [FrontendProductCategoryController::class, 'index']);
        Route::get('/ancestors-and-self/{productCategory:slug}', [FrontendProductCategoryController::class, 'ancestorsAndSelf']);
        Route::get('/tree', [FrontendProductCategoryController::class, 'tree']);
        Route::get('/show/{productCategory:slug}', [FrontendProductCategoryController::class, 'show']);
    });

    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [FrontendProductController::class, 'index']);
        Route::get('/show/{product:slug}', [FrontendProductController::class, 'show']);
        Route::get('/popular-products', [FrontendProductController::class, 'mostPopularProducts']);
        Route::get('/flash-sale-products', [FrontendProductController::class, 'flashSaleProducts']);
        Route::post('/category-wise-products', [FrontendProductController::class, 'categoryWiseProducts']);
        Route::get('/offer-products', [FrontendProductController::class, 'offerProducts']);
        Route::get('/daily-deals-products', [FrontendProductController::class, 'dailyDealsProducts']);
        Route::get('/wishlist-products', [FrontendProductController::class, 'wishlistProducts'])->middleware(['auth:sanctum']);
        Route::get('/related-products/{product:slug}', [FrontendProductController::class, 'relatedProducts']);
        Route::get('/initial-variation/{product}', [FrontendProductVariationController::class, 'initialVariation']);
        Route::get('/children-variation/{productVariation}', [FrontendProductVariationController::class, 'childrenVariation']);
        Route::get('/variation/ancestors-and-self/{productVariation}', [FrontendProductVariationController::class, 'ancestorsToString']);
        Route::get('/all-variation/{product:slug}', [FrontendProductVariationController::class, 'allVariation']);
        Route::get('/show-with-trashed/{product:slug}', [FrontendProductController::class, 'showWithTrashed'])->withTrashed();
    });

    Route::prefix('page')->name('page.')->group(function () {
        Route::get('/', [FrontendPageController::class, 'index']);
        Route::get('/show/{page:slug}', [FrontendPageController::class, 'show']);
        Route::get('/page-info/{page}', [FrontendPageController::class, 'show']);
    });

    Route::prefix('promotion')->name('promotion.')->group(function () {
        Route::get('/', [FrontendPromotionController::class, 'index']);
        Route::get('/show/{promotion:slug}', [FrontendPromotionController::class, 'show']);
        Route::get('/products/{promotion:slug}', [FrontendPromotionProductController::class, 'index']);
    });

    Route::prefix('product-section')->name('productSection.')->group(function () {
        Route::get('/', [FrontendProductSectionController::class, 'index']);
        Route::get('/show/{productSection:slug}', [FrontendProductSectionController::class, 'show']);
        Route::get('/products/{productSection:slug}', [FrontendProductSectionProductController::class, 'index']);
    });

    Route::prefix('product-brand')->name('product-brand.')->group(function () {
        Route::get('/', [FrontendProductBrandController::class, 'index']);
    });

    Route::prefix('benefit')->name('benefit.')->group(function () {
        Route::get('/', [FrontendBenefitController::class, 'index']);
    });

    Route::prefix('wishlist')->middleware(['auth:sanctum'])->name('wishlist.')->group(function () {
        Route::get('/', [FrontendWishlistController::class, 'index']);
        Route::post('/toggle', [FrontendWishlistController::class, 'toggle']);
    });

    Route::prefix('coupon')->name('coupon.')->group(function () {
        Route::get('/', [FrontendCouponController::class, 'index']);
        Route::post('/coupon-checking', [FrontendCouponController::class, 'couponChecking']);
    });

    Route::prefix('payment-gateway')->name('payment-gateway.')->group(function () {
        Route::get('/', [FrontendPaymentGatewayController::class, 'index']);
    });

    Route::prefix('order')->name('order.')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [FrontendOrderController::class, 'index']);
        Route::get('/show/{frontendOrder}', [FrontendOrderController::class, 'show']);
        Route::post('/', [FrontendOrderController::class, 'store']);
        Route::post('/change-status/{frontendOrder}', [FrontendOrderController::class, 'changeStatus']);
    });

    Route::prefix('device-token')->name('device-token.')->middleware(['auth:sanctum'])->group(function () {
        Route::post('/web', [TokenStoreController::class, 'webToken']);
        Route::post('/mobile', [TokenStoreController::class, 'deviceToken']);
    });

    Route::prefix('subscriber')->name('subscriber.')->group(function () {
        Route::post('/', [FrontendSubscriberController::class, 'store']);
    });

    Route::prefix('overview')->name('overview.')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/total-orders', [OverviewController::class, 'totalOrders']);
        Route::get('/total-complete-orders', [OverviewController::class, 'totalCompletedOrders']);
        Route::get('/total-return-orders', [OverviewController::class, 'totalReturnedOrders']);
        Route::get('/wallet-balance', [OverviewController::class, 'walletBalance']);
    });


    Route::prefix('cookies')->name('cookies.')->group(function () {
        Route::get('/', [FrontendCookiesController::class, 'get']);
        Route::post('/', [FrontendCookiesController::class, 'set']);
    });

    Route::prefix('outlet')->name('outlet.')->group(function () {
        Route::get('/', [FrontendOutletController::class, 'index']);
    });

    Route::prefix('check-delivery-zone')->name('check-delivery-zone.')->group(function () {
        Route::get('/', [FrontendDeliveryZoneController::class, 'deliveryZone']);
    });

    // Guest order route (no authentication required)
    Route::prefix('guest-order')->name('guest-order.')->group(function () {
        Route::post('/', [FrontendOrderController::class, 'storeGuest']);
    });
});
