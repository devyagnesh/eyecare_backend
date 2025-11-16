<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Change frame_photo from string to JSON to support multiple images
            $table->json('frame_photos')->nullable()->after('frame_photo');
        });
        
        // Migrate existing single frame_photo to frame_photos array
        $orders = DB::table('orders')->whereNotNull('frame_photo')->get();
        foreach ($orders as $order) {
            DB::table('orders')
                ->where('id', $order->id)
                ->update(['frame_photos' => json_encode([$order->frame_photo])]);
        }
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('frame_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('frame_photo')->nullable()->after('frame_photos');
        });
        
        // Migrate first frame_photo from array back to single column
        $orders = DB::table('orders')->whereNotNull('frame_photos')->get();
        foreach ($orders as $order) {
            $photos = json_decode($order->frame_photos, true);
            if (is_array($photos) && !empty($photos)) {
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update(['frame_photo' => $photos[0]]);
            }
        }
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('frame_photos');
        });
    }
};
