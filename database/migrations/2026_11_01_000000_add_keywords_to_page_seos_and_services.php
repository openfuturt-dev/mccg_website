<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_seos', function (Blueprint $table) {
            $table->text('keywords')->nullable()->after('meta_description');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->text('keywords')->nullable()->after('meta_description');
        });

        foreach (['page_seos' => 'pages', 'services' => 'services'] as $table => $group) {
            foreach (config("seo_keywords.$group") as $slug => $keywords) {
                DB::table($table)->where('slug', $slug)->whereNull('keywords')->update(['keywords' => implode(', ', $keywords)]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('page_seos', fn (Blueprint $table) => $table->dropColumn('keywords'));
        Schema::table('services', fn (Blueprint $table) => $table->dropColumn('keywords'));
    }
};
