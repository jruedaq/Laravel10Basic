<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $category = new Category();
        $category->name = $title = 'Default';
        $category->slug = Str::slug($title);
        $category->save();

        Schema::table('products', static function (Blueprint $table) use ($category) {
            $table->unsignedBigInteger('category_id')->after('id')->default($category->id);
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Category::where(['slug' => Str::slug('Default')])->delete();
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
};
