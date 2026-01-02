<?php

namespace Database\Seeders;

use App\Enums\AnalyticSection as AnalyticSectionEnum;
use App\Enums\Status;
use App\Models\Analytic;
use App\Models\AnalyticSection;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;

class AnalyticTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $envService = new EnvEditor();
        if ($envService->getValue('DEMO')) {
            Analytic::create([
                'name' => 'Google',
                'status' => Status::ACTIVE,
            ]);

            AnalyticSection::create([
                'analytic_id' => 1,
                'name'        => 'GA4 header script',
                'data'        => "  <!-- Google Tag Manager -->
                                    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                                    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                                    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                                    })(window,document,'script','dataLayer','GTM-NJCPFXCP');</script>
                                    <!-- End Google Tag Manager -->
                                 ",
                'section' => AnalyticSectionEnum::HEAD,
            ]);

            AnalyticSection::create([
                'analytic_id' => 1,
                'name'        => 'GA4 body script',
                'data'        => '<!-- Google Tag Manager (noscript) -->
                                    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NJCPFXCP"
                                    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
                                    <!-- End Google Tag Manager (noscript) -->
                                ',
                'section' => AnalyticSectionEnum::BODY,
            ]);
        }
    }
}
