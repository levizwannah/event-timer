<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendums', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('duration');
            $table->timestamp('ended_at')->nullable()->after('started_at');
        });
    }

    public function down(): void
    {
        Schema::table('agendums', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'ended_at']);
        });
    }
};
