<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Khóa ngoại
            $table->foreignId('organizer_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('venue_id')->nullable()->constrained('venues')->onDelete('set null');

            // Thông tin sự kiện
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();

            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->dateTime('registration_end')->nullable();

            $table->integer('max_attendees')->nullable();
            $table->integer('current_attendees')->default(0);
            $table->string('featured_image', 500)->nullable();

            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_free')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
