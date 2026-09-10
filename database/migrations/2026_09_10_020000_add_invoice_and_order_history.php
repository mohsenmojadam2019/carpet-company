<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('public_token', 64)->nullable()->unique()->after('order_number');
            $table->string('invoice_number')->nullable()->unique()->after('public_token');
            $table->string('payment_gateway')->default('zarinpal')->after('payment_status');
            $table->timestamp('shipped_at')->nullable()->after('paid_at');
            $table->timestamp('completed_at')->nullable()->after('shipped_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
        });

        DB::table('orders')->select('id')->orderBy('id')->get()->each(function ($row): void {
            DB::table('orders')->where('id', $row->id)->whereNull('public_token')->update(['public_token' => Str::random(48)]);
        });

        Schema::create('order_status_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->string('note', 1000)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropUnique(['public_token']);
            $table->dropUnique(['invoice_number']);
            $table->dropColumn(['public_token','invoice_number','payment_gateway','shipped_at','completed_at','cancelled_at']);
        });
    }
};
